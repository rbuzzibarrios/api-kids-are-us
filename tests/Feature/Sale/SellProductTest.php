<?php

namespace Tests\Feature\Sale;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use LaravelIdea\Helper\App\Models\_IH_User_C;
use Tests\TestCase;

class SellProductTest extends TestCase
{
    /**
     * @var User|User[]|Collection|Model|_IH_User_C|mixed
     */
    private mixed $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->user()->create();
    }

    public function test_should_validate_available_stock(): void
    {
        $this->actingAs($this->user);

        $product = Product::factory()->hasStock(['quantity' => 0])->createQuietly(); // @phpstan-ignore-line

        $this
            ->putJson(route('sell.product', ['product' => $product->sku]), ['qty' => 1])
            ->assertInternalServerError()
            ->assertJson([
                'status' => 'error',
                'message' => __('sale.store.error'),
            ]);
    }

    public function test_should_sale_product_successfully(): void
    {
        $this->actingAs($this->user);

        /** @var Product $product */
        $product = Product::factory()->hasStock(['quantity' => 789])->createQuietly(); // @phpstan-ignore-line

        $this
            ->putJson(route('sell.product', ['product' => $product->sku]), ['qty' => 1])
            ->assertOk()
            ->assertExactJson([
                'status' => 'success',
                'message' => __('sale.store.success'),
            ]);

        $product->load(['stock', 'sales'])->refresh();

        $this->assertDatabaseHas('product_sales', [
            'quantity' => 1,
            'product_id' => $product->id,
            'purchaser_id' => $this->user->id,
        ]);

        $this->assertEquals(788, $product->stock->quantity);
    }
}
