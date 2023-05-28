<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::factory(30)->hasStock(1)->hasSales(rand(1, 3), [ // @phpstan-ignore-line
            'purchaser_id' => User::factory()->userRole()->create(),
        ])->createQuietly();

        Product::factory(30)->hasStock(1)->hasSales(rand(1, 3), [ // @phpstan-ignore-line
            'purchaser_id' => User::factory()->userRole()->create(),
        ])->createQuietly();

        Product::factory(30)->hasStock(1)->hasSales(rand(1, 3), [ // @phpstan-ignore-line
            'purchaser_id' => User::factory()->userRole()->create(),
        ])->createQuietly();

        Product::factory(10)->hasStock(1)->createQuietly(); // @phpstan-ignore-line

        Product::factory(10)->createQuietly();
    }
}
