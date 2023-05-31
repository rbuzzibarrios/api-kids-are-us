<?php

namespace Tests\Feature\Product;

use App\Models\Product;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Arr;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class UpdateProductTest extends TestCase
{
    use DatabaseTransactions;

    public function test_should_validate_require_fields(): void
    {
        /** @var Authenticatable $editorUser */
        $editorUser = User::factory()->editor()->create();

        $this->actingAs($editorUser);

        $product = Product::factory()->hasStock()->createQuietly(); // @phpstan-ignore-line

        $this->putJson(
            route('update.product', ['product' => $product->sku]),
            ['namex' => 'test name']
        )->assertUnprocessable();

        $this->putJson(
            route('update.product', ['product' => $product->sku]), []
        )->assertUnprocessable();

        $this->putJson(
            route('update.product', ['product' => $product->sku]), ['name' => 'test name', 'gosh' => 'goth']
        )->assertUnprocessable();

        $this->putJson(
            route('update.product', ['product' => $product->sku]), ['name' => '', 'price' => null, 'description' => '']
        )->assertUnprocessable();

        $this->putJson(
            route('update.product', ['product' => $product->sku]), ['tags' => [5, 6, 7]]
        )->assertUnprocessable();
    }

    public function test_should_update_product_successfully(): void
    {
        /** @var Authenticatable $editorUser */
        $editorUser = User::factory()->editor()->create();

        $this->actingAs($editorUser);

        $product = Product::factory()->hasStock()->createQuietly(); // @phpstan-ignore-line

        $newProduct = Product::factory()->make(['tags' => ['tag1', 'tag2']]);

        $productData = [
            ...Arr::except($newProduct->setAppends([])->toArray(), ['title', 'sku', 'product_category_id']),
            ...['quantity' => 600, 'category' => $newProduct->getAttribute('product_category_id')],
        ];

        $response = $this->putJson(route('update.product', ['product' => $product->sku]), $productData);

        $response
            ->assertOk()
            ->assertJson(fn (AssertableJson $json) => $json->has('product',
                fn (AssertableJson $json) => $json
                    ->where('name', $productData['name'])
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
