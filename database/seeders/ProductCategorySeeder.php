<?php

namespace Database\Seeders;

use App\Models\ProductCategory;
use Illuminate\Database\Seeder;

class ProductCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $productCategories = [
            [
                'name' => 'for girls',
                'description' => 'for girls',
            ],
            [
                'name' => 'for boys',
                'description' => 'for boys',
            ],
            [
                'name' => 'for babies',
                'description' => 'for babies',
            ],
            [
                'name' => 'for play',
                'description' => 'for play',
            ],

        ];

        foreach ($productCategories as $productCategory) {
            ProductCategory::firstOrCreate($productCategory);
        }
    }
}
