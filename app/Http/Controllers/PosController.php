<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckoutPosRequest;
use App\Http\Requests\StorePosCartItemRequest;
use App\Http\Requests\UpdatePosCartItemRequest;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\MedicineCategory;
use App\Services\PosService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class PosController extends Controller
{
    public function __construct(
        protected PosService $posService
    ) {}

    public function index()
    {
        abort_unless(auth()->user()->can('pos.view'), 403);

        $cart = $this->posService->getOrCreateCart(auth()->user());

        $customers = $this->posService->getCustomers();

        $categories = MedicineCategory::query()
            ->orderBy('name')
            ->get([
                'id',
                'name',
            ]);

        return view('pos.index', compact(
            'cart',
            'customers',
            'categories'
        ));
    }

    public function medicines(Request $request)
    {
        abort_unless(auth()->user()->can('pos.view'), 403);

        return response()->json([
            'success' => true,
            'medicines' => $this->posService->getMedicinesForPos(
                $request->filled('medicine_category_id')
                    ? (int) $request->input('medicine_category_id')
                    : null
            ),
        ]);
    }

    public function cart()
    {
        abort_unless(auth()->user()->can('pos.view'), 403);

        $cart = $this->posService->getOrCreateCart(auth()->user());

        return response()->json([
            'success' => true,
            'cart' => $cart,
        ]);
    }

    public function searchMedicines(Request $request)
    {
        abort_unless(auth()->user()->can('pos.view'), 403);

        return response()->json([
            'success' => true,
            'medicines' => $this->posService->searchMedicines(
                (string) $request->input('search', ''),
                $request->filled('medicine_category_id')
                    ? (int) $request->input('medicine_category_id')
                    : null
            ),
        ]);
    }

    public function customers()
    {
        abort_unless(auth()->user()->can('pos.view'), 403);

        return response()->json([
            'success' => true,
            'customers' => $this->posService->getCustomers(),
        ]);
    }

    public function addItem(StorePosCartItemRequest $request)
    {
        abort_unless(auth()->user()->can('pos.view'), 403);

        try {
            $cart = $this->posService->getOrCreateCart(
                auth()->user()
            );

            $item = $this->posService->addItem(
                $cart,
                (int) $request->medicine_id,
                (int) $request->quantity,
                (int) ($request->free_quantity ?? 0)
            );

            return response()->json([
                'success' => true,
                'item' => $item,
                'cart' => $cart->fresh([
                    'items.medicine',
                    'customer',
                ]),
            ]);
        } catch (ValidationException $e) {
            throw $e;
        }
    }

    public function updateItem(UpdatePosCartItemRequest $request, CartItem $cartItem) 
    {
        abort_unless(auth()->user()->can('pos.view'), 403);

        try {
            $cart = $cartItem->cart;

            if ($cart->user_id !== auth()->id()) {
                abort(403);
            }

            $item = $this->posService->updateItem(
                $cart,
                $cartItem,
                (int) $request->quantity,
                (int) ($request->free_quantity ?? 0)
            );

            return response()->json([
                'success' => true,
                'item' => $item,
                'cart' => $cart->fresh([
                    'items.medicine',
                    'customer',
                ]),
            ]);
        } catch (ValidationException $e) {
            throw $e;
        }
    }

    public function removeItem(CartItem $cartItem)
    {
        abort_unless(auth()->user()->can('pos.view'), 403);

        $cart = $cartItem->cart;

        if ($cart->user_id !== auth()->id()) {
            abort(403);
        }

        $this->posService->removeItem(
            $cart,
            $cartItem
        );

        return response()->json([
            'success' => true,
            'cart' => $cart->fresh([
                'items.medicine',
                'customer',
            ]),
        ]);
    }

    public function updateCart(Request $request)
    {
        abort_unless(auth()->user()->can('pos.view'), 403);

        $validated = $request->validate([
            'customer_id' => [
                'nullable',
                'integer',
                'exists:customers,id',
            ],
            'discount' => [
                'nullable',
                'numeric',
                'min:0',
            ],
            'tax' => [
                'nullable',
                'numeric',
                'min:0',
            ],
            'shipping' => [
                'nullable',
                'numeric',
                'min:0',
            ],
            'other_charges' => [
                'nullable',
                'numeric',
                'min:0',
            ],
            'paid_amount' => [
                'nullable',
                'numeric',
                'min:0',
            ],
            'notes' => [
                'nullable',
                'string',
            ],
        ]);

        $cart = $this->posService->getOrCreateCart(
            auth()->user()
        );

        $cart = $this->posService->updateCart(
            $cart,
            $validated
        );

        return response()->json([
            'success' => true,
            'cart' => $cart,
        ]);
    }

    public function hold()
    {
        abort_unless(auth()->user()->can('pos.hold'), 403);

        $cart = $this->posService->getOrCreateCart(
            auth()->user()
        );

        $cart = $this->posService->hold($cart);

        return response()->json([
            'success' => true,
            'message' => 'Cart held successfully.',
            'cart' => $cart,
        ]);
    }

    public function cancel()
    {
        abort_unless(auth()->user()->can('pos.cancel'), 403);

        $cart = $this->posService->getOrCreateCart(
            auth()->user()
        );

        $cart = $this->posService->cancel($cart);

        return response()->json([
            'success' => true,
            'message' => 'Cart cancelled successfully.',
        ]);
    }

    public function resume(Cart $cart)
    {
        abort_unless(auth()->user()->can('pos.view'), 403);

        if ($cart->user_id !== auth()->id()) {
            abort(403);
        }

        $cart = $this->posService->resume($cart);

        return response()->json([
            'success' => true,
            'cart' => $cart,
        ]);
    }

    public function checkout(CheckoutPosRequest $request)
    {
        abort_unless(auth()->user()->can('pos.checkout'), 403);

        try {
            $cart = $this->posService->getOrCreateCart(
                auth()->user()
            );

            $sale = $this->posService->checkout(
                $cart,
                $request->validated()
            );

            // return response()->json([
            //     'success' => true,
            //     'message' => 'Sale completed successfully.',
            //     'sale' => $sale,
            //     'redirect' => route('pos.index'),
            // ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Sale completed successfully.',
                'sale_id' => $sale->id,
                'receipt_url' => route(
                    'sales.receipt.thermal',
                    $sale
                ),
            ]);
        } catch (ValidationException $e) {
            throw $e;
        } catch (\Throwable $e) {
            report($e);

            return response()->json([
                'success' => false,
                'message' => 'Unable to complete the sale.',
            ], 500);
        }
    }
}