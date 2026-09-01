<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Customer;
use App\Models\Medicine;
use App\Models\Sale;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PosService
{
    public function __construct(
        protected SaleService $saleService,
        protected PaymentService $paymentService
    ) {}

    public function getMedicinesForPos(?int $categoryId = null): Collection
    {
        return Medicine::query()
            ->where('status', 1)
            ->when(
                $categoryId,
                fn ($query) => $query->where(
                    'medicine_category_id',
                    $categoryId
                )
            )
            ->orderBy('name')
            ->limit(30)
            ->get([
                'id',
                'name',
                'generic_name',
                'barcode',
                'selling_price',
                'purchase_price',
                'current_stock',
                'medicine_category_id',
            ]);
    }

    public function getOrCreateCart(User $user): Cart
    {
        $cart = Cart::query()
            ->where('user_id', $user->id)
            ->where('status', 'active')
            ->with(['items.medicine', 'customer'])
            ->latest('id')
            ->first();

        if ($cart) {
            return $cart;
        }

        return Cart::create([
            'user_id' => $user->id,
            'status' => 'active',
        ])->load(['items.medicine', 'customer']);
    }

    public function getCustomers(): Collection
    {
        return Customer::query()
            ->orderBy('name')
            ->get(['id', 'name', 'phone']);
    }

    public function searchMedicines(string $search = '', ?int $categoryId = null): Collection 
    {
        return Medicine::query()
            ->where('status', 1)
            ->when(trim($search) !== '', function ($query) use ($search) {
                $search = trim($search);

                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('generic_name', 'like', "%{$search}%")
                        ->orWhere('barcode', 'like', "%{$search}%");
                });
            })
            ->when(
                $categoryId,
                fn ($query) => $query->where(
                    'medicine_category_id',
                    $categoryId
                )
            )
            ->orderBy('name')
            ->limit(30)
            ->get([
                'id',
                'name',
                'generic_name',
                'barcode',
                'selling_price',
                'purchase_price',
                'current_stock',
                'medicine_category_id',
            ]);
    }

    public function getMedicine(int $medicineId): Medicine
    {
        return Medicine::query()
            ->where('status', 1)
            ->findOrFail($medicineId);
    }

    public function addItem(
        Cart $cart,
        int $medicineId,
        int $quantity = 1,
        int $freeQuantity = 0
    ): CartItem {
        $this->ensureActiveCart($cart);

        $medicine = $this->getMedicine($medicineId);

        $quantity = max(1, $quantity);
        $freeQuantity = max(0, $freeQuantity);

        $existing = $cart->items()
            ->where('medicine_id', $medicine->id)
            ->first();

        $newQuantity =
            ($existing?->quantity ?? 0) + $quantity;

        $newFreeQuantity =
            ($existing?->free_quantity ?? 0) + $freeQuantity;

        $physicalQuantity =
            $newQuantity + $newFreeQuantity;

        if ((int) $medicine->current_stock < $physicalQuantity) {
            throw ValidationException::withMessages([
                'quantity' => [
                    "{$medicine->name} does not have enough stock available."
                ]
            ]);
        }

        $item = $existing ?: new CartItem([
            'cart_id' => $cart->id,
            'medicine_id' => $medicine->id,
        ]);

        $item->quantity = $newQuantity;
        $item->free_quantity = $newFreeQuantity;
        $item->purchase_price = $medicine->purchase_price ?? 0;
        $item->selling_price = $medicine->selling_price ?? 0;
        $item->discount = $existing?->discount ?? 0;
        $item->tax = $existing?->tax ?? 0;
        $item->total = $this->calculateItemTotal($item);
        $item->save();

        $this->recalculateCart($cart);

        return $item->fresh('medicine');
    }

    public function updateItem(
        Cart $cart,
        CartItem $item,
        int $quantity,
        int $freeQuantity = 0
    ): CartItem {
        $this->ensureActiveCart($cart);
        $this->ensureCartItem($cart, $item);

        $physicalQuantity =
            $quantity + $freeQuantity;

        $medicine = $item->medicine;

        if ((int) $medicine->current_stock < $physicalQuantity) {
            throw ValidationException::withMessages([
                'quantity' => [
                    "{$medicine->name} does not have enough stock available."
                ]
            ]);
        }

        $item->update([
            'quantity' => $quantity,
            'free_quantity' => $freeQuantity,
            'total' => $this->calculateItemTotal(
                $item,
                $quantity,
                $freeQuantity
            ),
        ]);

        $this->recalculateCart($cart);

        return $item->fresh('medicine');
    }

    public function removeItem(Cart $cart, CartItem $item): void
    {
        $this->ensureActiveCart($cart);
        $this->ensureCartItem($cart, $item);

        $item->delete();

        $this->recalculateCart($cart);
    }

    public function updateCart(
        Cart $cart,
        array $data
    ): Cart {
        $this->ensureActiveCart($cart);

        $cart->update([
            'customer_id' => $data['customer_id'] ?? null,
            'discount' => $data['discount'] ?? 0,
            'tax' => $data['tax'] ?? 0,
            'shipping' => $data['shipping'] ?? 0,
            'other_charges' => $data['other_charges'] ?? 0,
            'paid_amount' => $data['paid_amount'] ?? 0,
            'notes' => $data['notes'] ?? null,
        ]);

        $this->recalculateCart($cart);

        return $cart->fresh(['items.medicine', 'customer']);
    }

    public function hold(Cart $cart): Cart
    {
        $this->ensureActiveCart($cart);

        if (!$cart->items()->exists()) {
            throw ValidationException::withMessages([
                'cart' => ['Cannot hold an empty cart.']
            ]);
        }

        $cart->update([
            'status' => 'held',
        ]);

        return $cart->fresh(['items.medicine', 'customer']);
    }

    public function cancel(Cart $cart): Cart
    {
        if ($cart->status === 'checked_out') {
            throw ValidationException::withMessages([
                'cart' => ['A checked-out cart cannot be cancelled.']
            ]);
        }

        $cart->update([
            'status' => 'cancelled',
        ]);

        return $cart->fresh();
    }

    public function resume(Cart $cart): Cart
    {
        if ($cart->status !== 'held') {
            throw ValidationException::withMessages([
                'cart' => ['Only held carts can be resumed.']
            ]);
        }

        $cart->update([
            'status' => 'active',
        ]);

        return $cart->fresh(['items.medicine', 'customer']);
    }

    public function checkout(Cart $cart,array $data): Sale 
    {
        return DB::transaction(function () use ($cart, $data) {
            $this->ensureActiveCart($cart);

            $cart->load('items.medicine');

            if ($cart->items->isEmpty()) {
                throw ValidationException::withMessages([
                    'cart' => ['Cannot checkout an empty cart.']
                ]);
            }

            $this->recalculateCart($cart);

            $cart->refresh();

            $items = $cart->items->map(function (CartItem $item) {
                return [
                    'medicine_id' => $item->medicine_id,
                    'quantity' => $item->quantity,
                    'free_quantity' => $item->free_quantity,
                    'selling_price' => (float) $item->selling_price,
                    'purchase_price' => (float) $item->purchase_price,
                    'discount' => (float) $item->discount,
                    'tax' => (float) $item->tax,
                ];
            })->toArray();

            $sale = $this->saleService->store([
                'customer_id' => $data['customer_id'] ?? $cart->customer_id,
                'sale_date' => $data['sale_date'],
                'doctor_name' => $data['doctor_name'] ?? null,
                'discount_type' => 'fixed',
                'discount' => (float) $cart->discount,
                'tax_type' => 'fixed',
                'tax' => (float) $cart->tax,
                'shipping' => (float) $cart->shipping,
                'other_charges' => (float) $cart->other_charges,
                'paid_amount' => (float) $cart->paid_amount,
                'notes' => $data['notes'] ?? $cart->notes,
                'status' => 'completed',
                'items' => $items,
            ]);

            $paidAmount = (float) ($data['paid_amount'] ?? 0);


            /*
            |--------------------------------------------------------------------------
            | Create Payment Record
            |--------------------------------------------------------------------------
            |
            | A payment record is created only when actual money was received.
            | Since this transaction belongs to a Sale, the type is always "receipt".
            |
            */

            if ($paidAmount > 0) {
                $this->paymentService->store([
                    'type' => 'receipt',
                    'sale_id' => $sale->id,
                    'purchase_id' => null,
                    'customer_id' => $sale->customer_id,
                    'supplier_id' => null,
                    'amount' => $paidAmount,
                    'method' => $data['payment_method'] ?? 'cash',
                    'payment_date' => $data['sale_date'] ?? now()->toDateString(),
                    'reference_number' => $data['reference_number'] ?? null,
                    'notes' => $data['notes'] ?? null,
                    'created_by' => auth()->id(),
                    'updated_by' => auth()->id(),
                ]);

            }

            $cart->update([
                'status' => 'checked_out',
            ]);

            return $sale;
        });
    }

    protected function recalculateCart(Cart $cart): void
    {
        $cart->load('items');

        $subtotal = $cart->items->sum('total');
        $discount = (float) $cart->discount;
        $tax = (float) $cart->tax;
        $shipping = (float) $cart->shipping;
        $otherCharges = (float) $cart->other_charges;

        $grandTotal = max(
            $subtotal
            - $discount
            + $tax
            + $shipping
            + $otherCharges,
            0
        );

        $paidAmount = max(
            (float) $cart->paid_amount,
            0
        );

        $cart->update([
            'subtotal' => $subtotal,
            'grand_total' => $grandTotal,
            'due_amount' => max($grandTotal - $paidAmount, 0),
        ]);
    }

    protected function calculateItemTotal(
        CartItem $item,
        ?int $quantity = null,
        ?int $freeQuantity = null
    ): float {
        $quantity ??= (int) $item->quantity;
        $freeQuantity ??= (int) $item->free_quantity;

        return max(
            (
                $quantity *
                (float) $item->selling_price
            )
            - (float) $item->discount
            + (float) $item->tax,
            0
        );
    }

    protected function ensureActiveCart(Cart $cart): void
    {
        if ($cart->status !== 'active') {
            throw ValidationException::withMessages([
                'cart' => ['This cart is no longer active.']
            ]);
        }
    }

    protected function ensureCartItem(
        Cart $cart,
        CartItem $item
    ): void {
        if ($item->cart_id !== $cart->id) {
            throw ValidationException::withMessages([
                'item' => ['This cart item does not belong to the selected cart.']
            ]);
        }
    }
}