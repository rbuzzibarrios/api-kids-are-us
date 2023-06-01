<?php

namespace Tests\Unit;

use App\Models\Product;
use App\Models\User;
use App\Repositories\Product\ProductRepositoryInterface;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class SaleProductTest extends TestCase
{
    use DatabaseTransactions, DatabaseMigrations;

    public function test_can_sell_product(): void
    {
        /** @var Product $product */
        $product = Product::factory()->hasStock(1, ['quantity' => 5])->createQuietly(); // @phpstan-ignore-line

        /** @var Authenticatable $user */
        $user = User::factory()->user()->create();

        $product = app(ProductRepositoryInterface::class)->sell($product, $user, 1);

        $this->assertEquals(4, $product->stock->quantity);
        $this->assertEquals($product->price, $product->sales()->first()->total_price);
        $this->assertCount(1, $product->sales);
    }
}
