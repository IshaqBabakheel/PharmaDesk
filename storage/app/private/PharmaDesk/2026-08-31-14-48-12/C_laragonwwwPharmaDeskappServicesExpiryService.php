<?php

namespace App\Services;

use App\Models\PurchaseItem;
use App\Models\PurchaseReturnItem;
use App\Models\SaleItem;
use App\Models\SaleReturnItem;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ExpiryService
{
    /**
     * Get expiry batches.
     */
    public function getExpiryBatches(
        ?string $filter = 'all'
    ) {
        $today = now()->startOfDay();

        $query = PurchaseItem::query()
            ->with('medicine')
            ->whereNotNull('expiry_date');


        /*
        |--------------------------------------------------------------------------
        | Only batches that can still contain stock
        |--------------------------------------------------------------------------
        */

        $items = $query
            ->orderBy('expiry_date')
            ->orderBy('id')
            ->get();


        $items = $items->map(function (
            PurchaseItem $purchaseItem
        ) use ($today) {

            /*
            |--------------------------------------------------------------------------
            | Received
            |--------------------------------------------------------------------------
            */

            $received =
                (int) $purchaseItem->quantity
                + (int) $purchaseItem->free_quantity;


            /*
            |--------------------------------------------------------------------------
            | Completed Purchase Returns
            |--------------------------------------------------------------------------
            */

            $purchaseReturned =
                (int) PurchaseReturnItem::query()
                    ->where(
                        'purchase_item_id',
                        $purchaseItem->id
                    )
                    ->whereHas(
                        'purchaseReturn',
                        function ($query) {

                            $query
                                ->where(
                                    'status',
                                    'Completed'
                                )
                                ->whereNull(
                                    'deleted_at'
                                );
                        }
                    )
                    ->sum('quantity');


            /*
            |--------------------------------------------------------------------------
            | Completed Sales
            |--------------------------------------------------------------------------
            */

            $sold =
                (int) SaleItem::query()
                    ->where(
                        'purchase_item_id',
                        $purchaseItem->id
                    )
                    ->whereHas(
                        'sale',
                        function ($query) {

                            $query
                                ->where(
                                    'status',
                                    'completed'
                                )
                                ->whereNull(
                                    'deleted_at'
                                );
                        }
                    )
                    ->sum(
                        DB::raw(
                            'quantity + free_quantity'
                        )
                    );


            /*
            |--------------------------------------------------------------------------
            | Completed Sale Returns
            |--------------------------------------------------------------------------
            */

            $saleReturned =
                (int) SaleReturnItem::query()
                    ->where('purchase_item_id', $purchaseItem->id)
                    ->whereHas('saleReturn',
                        function ($query) {
                            $query->where('status', 'completed')
                                ->whereNull('deleted_at');
                        }
                    )
                    ->sum(
                        DB::raw(
                            'quantity + free_quantity'
                        )
                    );


            /*
            |--------------------------------------------------------------------------
            | Available Batch Quantity
            |--------------------------------------------------------------------------
            */
            $availableQuantity = max(0,
                $received
                    - $purchaseReturned
                    - $sold
                    + $saleReturned
            );


            /*
            |--------------------------------------------------------------------------
            | Days Remaining
            |--------------------------------------------------------------------------
            */
            $expiryDate = Carbon::parse($purchaseItem->expiry_date)->startOfDay();
            $daysRemaining = $today->diffInDays($expiryDate, false);

            /*
            |--------------------------------------------------------------------------
            | Status
            |--------------------------------------------------------------------------
            */
            if ($daysRemaining < 0) 
            {
                $expiryStatus = 'expired';
            } elseif ($daysRemaining <= 7) {
                $expiryStatus = 'critical';
            } elseif ($daysRemaining <= 30) {
                $expiryStatus = 'warning';
            } elseif ($daysRemaining <= 90) {
                $expiryStatus = 'upcoming';
            } else {
                $expiryStatus = 'safe';
            }

            /*
            |--------------------------------------------------------------------------
            | Attach Calculated Values
            |--------------------------------------------------------------------------
            */
            $purchaseItem->available_quantity = $availableQuantity;
            $purchaseItem->days_remaining = $daysRemaining;
            $purchaseItem->expiry_status = $expiryStatus;

            return $purchaseItem;
        });


        /*
        |--------------------------------------------------------------------------
        | Ignore Fully Consumed Batches
        |--------------------------------------------------------------------------
        */
        $items = $items->filter(
            function ($item) {
                return $item->available_quantity > 0;
            }
        );


        /*
        |--------------------------------------------------------------------------
        | Apply Filter
        |--------------------------------------------------------------------------
        */
        $items = $items->filter(
            function ($item) use ($filter) {

                return match ($filter) {
                    'expired' => $item->expiry_status === 'expired',
                    '7' => $item->days_remaining >= 0 && $item->days_remaining <= 7,
                    '30' => $item->days_remaining >= 0 && $item->days_remaining <= 30,
                    '60' => $item->days_remaining >= 0 && $item->days_remaining <= 60,
                    '90' => $item->days_remaining >= 0 && $item->days_remaining <= 90,
                    default => true,
                };
            }
        );


        return $items->values();
    }


    /**
     * Get expiry statistics.
     */
    public function getStatistics(): array
    {
        $items = $this->getExpiryBatches('all');

        return [
            'expired_batches' => $items->where('expiry_status', 'expired')->count(),
            'critical_batches' => $items->where('expiry_status', 'critical')->count(),
            'warning_batches' => $items->where('expiry_status', 'warning')->count(),
            'upcoming_batches' => $items->where('expiry_status', 'upcoming')->count(),
            'expired_quantity' => $items->where('expiry_status', 'expired')->sum('available_quantity'),
            'total_expiry_batches' => $items->count(),
            'total_expiry_quantity' => $items->sum('available_quantity'),
        ];
    }
}
