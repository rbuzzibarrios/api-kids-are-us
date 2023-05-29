<?php

namespace Tests\Feature\Product;

use App\Models\Product;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Arr;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class StoreProductTest extends TestCase
{
    use WithFaker;

    public function test_should_validate_require_fields(): void
    {
        /** @var Authenticatable $administratorUser */
        $administratorUser = User::factory()->userAdmin()->create();

        $this->actingAs($administratorUser);

        $this->postJson(route('store.product'))->assertUnprocessable();
    }

    public function test_should_store_product_successfully(): void
    {
        /** @var Authenticatable $administratorUser */
        $administratorUser = User::factory()->userAdmin()->create();

        $this->actingAs($administratorUser);

        $productData = [
            ...Arr::except(Product::factory()->make()->toArray(), 'product_category_id'),
            'quantity' => rand(1, 5000),
            'category' => rand(1, 3),
            'rate' => rand(0, 5),
        ];

        $this->postJson(route('store.product'), $productData)
            ->assertOk()
            ->assertJson(fn (AssertableJson $json) => $json->has('product',
                fn (AssertableJson $json) => $json
                    ->where('name', $productData['name'])
                    ->where('sku', $productData['sku'])
                    ->where('price', number_format($productData['price'], 2, '.', ''))
                    ->where('tags', $productData['tags'])
                    ->where('description', $productData['description'])
                    ->where('additional_information', $productData['additional_information'])
                    ->where('rate', $productData['rate'])
                    ->has('stock',
                        fn (AssertableJson $json) => $json->where('quantity', $productData['quantity'])->etc())
                    ->has('category', fn (AssertableJson $json) => $json->where('id', $productData['category'])->etc())
                    ->etc()
            )->etc()
            );

        $this->assertDatabaseHas(
            'products',
            Arr::only($productData, ['name', 'sku', 'rate', 'price', 'description', 'additional_information'])
        );
    }
}
