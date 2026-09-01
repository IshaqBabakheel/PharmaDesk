<?php

namespace Database\Seeders;

use App\Models\ExpenseCategory;
use Illuminate\Database\Seeder;

class ExpenseCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Rent',
            'Salaries',
            'Electricity',
            'Internet',
            'Telephone',
            'Transportation',
            'Office Supplies',
            'Maintenance',
            'Marketing',
            'Software & Subscriptions',
            'Bank Charges',
            'Taxes & Government Fees',
            'Cleaning',
            'Security',
            'Miscellaneous',
        ];

        foreach ($categories as $name) {

            ExpenseCategory::firstOrCreate([
                'name' => $name,
            ], [
                'status' => true,
            ]);
        }
    }
}