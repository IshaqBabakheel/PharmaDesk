<?php

namespace Database\Factories;

use App\Models\Purchase;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Purchase>
 */
class PurchaseFactory extends Factory
{
    protected $model = Purchase::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        $grandTotal = fake()->randomFloat(2, 5000, 100000);
        $paid = fake()->randomFloat(2, 0, $grandTotal);

        return [
            'supplier_id' => Supplier::factory(),
            'invoice_number' => fake()->bothify('INV-#####'),
            'reference_number' => fake()->optional()->bothify('REF-#####'),
            'purchase_date' => now(),
            'subtotal' => $grandTotal,
            'discount_type' => 'Fixed',
            'discount' => fake()->randomFloat(2, 0, 1000),
            'tax_type' => 'Percentage',
            'tax' => fake()->randomFloat(2, 0, 500),
            'shipping' => fake()->randomFloat(2, 0, 500),
            'other_charges' => fake()->randomFloat(2, 0, 300),
            'grand_total' => $grandTotal,
            'paid_amount' => $paid,
            'due_amount' => $grandTotal - $paid,
            'payment_status' => $paid == 0
                ? 'Unpaid'
                : ($paid >= $grandTotal
                    ? 'Paid'
                    : 'Partially Paid'),
            'status' => fake()->randomElement([
                'Completed',
                'Completed',
                'Completed',
                'Draft',
            ]),
            'notes' => fake()->optional()->sentence(),
            'created_by' => User::factory(),
        ];
    }

    /**
     * Configure the factory to generate unique purchase numbers.
     */
    public function configure()
    {
        static $counter = 1;
        
        return $this->afterMaking(function (Purchase $purchase) use (&$counter) {
            if (empty($purchase->purchase_number)) {
                $purchase->purchase_number = 'PUR-' . now()->format('Ymd') . '-' . str_pad($counter++, 4, '0', STR_PAD_LEFT);
            }
        });
    }
}