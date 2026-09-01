<?php

namespace Database\Seeders;


use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Medicine;


use Illuminate\Database\Seeder;



class SaleSeeder extends Seeder
{

    public function run(): void
    {


        Sale::factory()
        ->count(50)
        ->create()
        ->each(function($sale){



            $medicines = Medicine::query()
                ->inRandomOrder()
                ->limit(
                    rand(1,5)
                )
                ->get();




            $subtotal = 0;



            foreach($medicines as $medicine)
            {


                /*
                |--------------------------------------------------------------------------
                | Demo Batch Data
                |--------------------------------------------------------------------------
                |
                | Later this will come from purchase_items
                |
                */


                $quantity =
                    rand(1,10);



                $sellingPrice =
                    rand(
                        50,
                        500
                    );



                $total =
                    $quantity *
                    $sellingPrice;



                SaleItem::create([


                    'sale_id' =>
                        $sale->id,



                    'medicine_id' =>
                        $medicine->id,



                    'batch_number' =>
                        'BATCH-' .
                        rand(
                            100,
                            999
                        ),



                    'expiry_date' =>
                        now()
                        ->addMonths(
                            rand(
                                6,
                                24
                            )
                        ),



                    'quantity' =>
                        $quantity,



                    'free_quantity' =>
                        0,



                    'purchase_price' =>
                        $sellingPrice - 20,



                    'selling_price' =>
                        $sellingPrice,



                    'discount' =>
                        0,



                    'tax' =>
                        0,



                    'total' =>
                        $total,


                ]);



                $subtotal += $total;


            }



            /*
            |--------------------------------------------------------------------------
            | Recalculate Sale Total
            |--------------------------------------------------------------------------
            */


            $sale->update([

                'subtotal' =>
                    $subtotal,


                'grand_total' =>
                    $subtotal,


                'due_amount' =>
                    max(
                        $subtotal -
                        $sale->paid_amount,
                        0
                    )

            ]);



        });


    }

}