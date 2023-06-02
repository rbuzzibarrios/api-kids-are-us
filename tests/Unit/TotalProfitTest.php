<?php

namespace Tests\Unit;

use App\Models\Product;
use App\Models\ProductSale;
use App\Models\User;
use App\Repositories\Sale\ProductSaleRepositoryInterface;
use Tests\TestCase;

class TotalProfitTest extends TestCase
{
    public function test_can_calculate_total_profit(): void
    {
        ProductSale::truncate();

        $user = User::factory()->createQuietly();

        Product::factory() // @phpstan-ignore-line
            ->hasStock(1, ['quantity' => 5])
            ->hasSales(2, ['total_price' => 10, 'purchaser_id' => $user->getAttribute('id')])
            ->createQuietly(['price' => 10]);

        Product::factory() // @phpstan-ignore-line
            ->hasStock(1, ['quantity' => 6])
            ->hasSales(2, ['total_price' => 20, 'purchaser_id' => $user->getAttribute('id')])
            ->createQuietly(['price' => 20]);

        $this->assertEquals(60, app(ProductSaleRepositoryInterface::class)->totalProfit());
    }
}
