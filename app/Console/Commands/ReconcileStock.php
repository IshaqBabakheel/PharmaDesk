<?php

namespace App\Console\Commands;

use App\Models\Medicine;
use App\Models\PurchaseItem;
use App\Models\PurchaseReturnItem;
use App\Models\SaleItem;
use App\Models\SaleReturnItem;
use App\Models\StockAdjustmentItem;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ReconcileStock extends Command
{
    protected $signature = 'stock:reconcile
                            {--create-batches : Create opening-stock batches for stock without source batches}
                            {--medicine= : Reconcile only one medicine ID}';

    protected $description = 'Reconcile medicine current stock with inventory batches';

    public function handle(): int
    {
        $query =
            Medicine::query();

        if ($medicineId = $this->option('medicine')) {

            $query->where(
                'id',
                $medicineId
            );
        }


        $medicines =
            $query->orderBy('id')->get();


        if ($medicines->isEmpty()) {

            $this->info(
                'No medicines found.'
            );

            return self::SUCCESS;
        }


        $rows = [];


        foreach ($medicines as $medicine) {

            /*
            |--------------------------------------------------------------------------
            | Source Batch Stock
            |--------------------------------------------------------------------------
            */

            $received =
                (float) PurchaseItem::query()
                    ->where(
                        'medicine_id',
                        $medicine->id
                    )
                    ->sum(
                        DB::raw(
                            'quantity + free_quantity'
                        )
                    );


            /*
            |--------------------------------------------------------------------------
            | Completed Purchase Returns
            |--------------------------------------------------------------------------
            */

            $purchaseReturned =
                (float) PurchaseReturnItem::query()
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
                    ->where(
                        'medicine_id',
                        $medicine->id
                    )
                    ->sum('quantity');


            /*
            |--------------------------------------------------------------------------
            | Completed Sales
            |--------------------------------------------------------------------------
            */

            $sold =
                (float) SaleItem::query()
                    ->where(
                        'medicine_id',
                        $medicine->id
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

            $saleReturned = (float) SaleReturnItem::query()
                    ->where('medicine_id', $medicine->id)
                    ->whereHas('saleReturn', function ($query) 
                    {
                            $query
                                ->where('status', 'completed')
                                ->whereNull('deleted_at');
                        }
                    )
                    ->sum(DB::raw('quantity + free_quantity'));

            /*
            |--------------------------------------------------------------------------
            | Completed Stock Adjustments
            |--------------------------------------------------------------------------
            */        
            
            $stockDecreased = (float) StockAdjustmentItem::query()
                ->where('medicine_id', $medicine->id)
                ->whereHas('stockAdjustment', function ($query) {
                    $query
                        ->where('type', 'decrease')
                        ->where('status', 'completed')
                        ->where('stock_applied', true)
                        ->whereNull('deleted_at');
                })
                ->sum('quantity');

       

            /*
            |--------------------------------------------------------------------------
            | Expected Stock From Known Batches
            |--------------------------------------------------------------------------
            */

            $batchStock =
                $received
                - $purchaseReturned
                - $sold
                + $saleReturned
                - $stockDecreased; 


            /*
            |--------------------------------------------------------------------------
            | Current Cached Stock
            |--------------------------------------------------------------------------
            */

            $currentStock =
                (float) $medicine->current_stock;


            /*
            |--------------------------------------------------------------------------
            | Difference
            |--------------------------------------------------------------------------
            */

            $difference =
                $currentStock
                - $batchStock;


            $rows[] = [

                $medicine->id,

                $medicine->name,

                $currentStock,

                $received,

                $purchaseReturned,

                $sold,

                $saleReturned,

                $batchStock,

                $difference,

            ];
        }


        $this->table(

            [
                'ID',
                'Medicine',
                'Current',
                'Received',
                'Purch. Returns',
                'Sold',
                'Sale Returns',
                'Batch Expected',
                'Difference',
            ],

            $rows

        );


        /*
        |--------------------------------------------------------------------------
        | No Modification
        |--------------------------------------------------------------------------
        */

        if (!$this->option('create-batches')) {

            $this->newLine();

            $this->warn(
                'No data was changed.'
            );

            return self::SUCCESS;
        }


        /*
        |--------------------------------------------------------------------------
        | Create Opening Stock
        |--------------------------------------------------------------------------
        |
        | Difference > 0 means current_stock contains physical stock
        | which has no source batch yet.
        |
        */

        foreach ($medicines as $medicine) {

            $received =
                (float) PurchaseItem::query()
                    ->where(
                        'medicine_id',
                        $medicine->id
                    )
                    ->sum(
                        DB::raw(
                            'quantity + free_quantity'
                        )
                    );


            $purchaseReturned =
                (float) PurchaseReturnItem::query()
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
                    ->where(
                        'medicine_id',
                        $medicine->id
                    )
                    ->sum('quantity');


            $sold =
                (float) SaleItem::query()
                    ->where(
                        'medicine_id',
                        $medicine->id
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


            $saleReturned =
                (float) SaleReturnItem::query()
                    ->where(
                        'medicine_id',
                        $medicine->id
                    )
                    ->whereHas(
                        'saleReturn',
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


            $stockDecreased = (float) StockAdjustmentItem::query()
                ->where('medicine_id', $medicine->id)
                ->whereHas('stockAdjustment', function ($query) {
                    $query
                        ->where('type', 'decrease')
                        ->where('status', 'completed')
                        ->where('stock_applied', true)
                        ->whereNull('deleted_at');
                })
                ->sum('quantity');



                
            $batchStock =
                $received
                - $purchaseReturned
                - $sold
                + $saleReturned
                - $stockDecreased;


            $currentStock =
                (float) $medicine->current_stock;


            $difference =
                $currentStock
                - $batchStock;


            if ($difference <= 0) {
                continue;
            }


            /*
            |--------------------------------------------------------------------------
            | Check Existing Opening Stock
            |--------------------------------------------------------------------------
            */

            $openingItem =
                PurchaseItem::query()
                    ->where(
                        'medicine_id',
                        $medicine->id
                    )
                    ->where(
                        'source',
                        'opening_stock'
                    )
                    ->first();


            DB::transaction(
                function () use (
                    $medicine,
                    $difference,
                    $openingItem
                ) {

                    if ($openingItem) {

                        $openingItem->increment(
                            'quantity',
                            (int) $difference
                        );

                        return;
                    }


                    PurchaseItem::create([

                        'purchase_id' => null,

                        'source' =>
                            'opening_stock',

                        'medicine_id' =>
                            $medicine->id,

                        'batch_number' =>
                            'OPENING-' .
                            $medicine->id,

                        'expiry_date' =>
                            null,

                        'quantity' =>
                            (int) $difference,

                        'free_quantity' =>
                            0,

                        'purchase_price' =>
                            $medicine->purchase_price ?? 0,

                        'selling_price' =>
                            $medicine->selling_price ?? 0,

                        'discount' => 0,

                        'tax' => 0,

                        'total' =>
                            $difference *
                            (float) (
                                $medicine->purchase_price ?? 0
                            ),

                    ]);
                }
            );


            $this->info(
                "Created opening stock batch for {$medicine->name}: {$difference}"
            );
        }


        $this->newLine();

        $this->info(
            'Stock reconciliation completed.'
        );


        return self::SUCCESS;
    }
}