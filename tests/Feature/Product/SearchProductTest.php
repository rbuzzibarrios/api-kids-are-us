<?php

namespace Tests\Feature\Product;

use App\Models\Product;
use App\Models\User;
use Arr;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Illuminate\Testing\Fluent\AssertableJson;
use LaravelIdea\Helper\App\Models\_IH_User_C;
use Tests\TestCase;

class SearchProductTest extends TestCase
{
    use WithFaker, DatabaseTransactions;

    /**
     * @var User|User[]|Collection|Model|_IH_User_C|mixed
     */
    private mixed $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->user()->create();
    }

    public function test_should_return_empty_products_list(): void
    {
        $this->actingAs($this->user);

        $this->getJson(route('search.product', ['name' => 'product does not exist']))
            ->assertOk()
            ->assertJsonCount(0, 'products.data');

        $this->getJson(route('search.product', [
            'name'       => 'product does not exist',
            'comparison' => 'contains',
        ]))->assertOk()->assertJsonCount(0, 'products.data');

        $this->getJson(route('search.product', ['name' => 'product does not exist']))
            ->assertOk()
            ->assertJsonCount(0, 'products.data');

        $this->getJson(route('search.product', [
            'sku'        => 'sku does not exist',
            'comparison' => 'contains',
        ]))->assertOk()->assertJsonCount(0, 'products.data');

        $this->getJson(route('search.product', [
            'sku'        => 'sku does not exist',
            'comparison' => 'strict',
        ]))->assertOk()->assertJsonCount(0, 'products.data');
    }

    public function test_should_validate_request()
    {
        $this->actingAs($this->user);

        $this->getJson(route('search.product', ['comparison' => 'no valid comparison value']))
            ->assertUnprocessable();

        $this->getJson(route('search.product', ['comparison' => '']))
            ->assertUnprocessable()
            ->assertJsonStructure([
                'errors' => [
                    'comparison',
                ],
            ])
            ->assertJsonCount(1, 'errors.comparison');
    }

    public function test_should_return_paginated_product_list(): void
    {
        $this->actingAs($this->user);

        $this->getJson(route('search.product', []))
            ->assertOk()
            ->assertJson(fn(AssertableJson $json) => $json->has('products',
                fn(AssertableJson $json) => $json->where('total', function ($total) {
                    return $total > 0;
                })->etc())->etc());

        /** @var Product $product */
        $product = Product::factory()->hasStock(1, ['quantity' => 2])->createQuietly();

        $this->getJson(route('search.product', [
            ...Arr::only($product->toArray(), ['name', 'sku', 'rate']),
            ...['quantity' => 2, 'category' => $product->product_category_id],
        ]))->assertOk()
            ->assertJsonCount(1, 'products.data');

        $this->getJson(route('search.product',
            ['query' => $product->name]))
            ->assertOk()
            ->assertJsonCount(1, 'products.data');

        $searchName = Str::substr($product->name, 3, strlen($product->name) - 3);
        $this->getJson(route('search.product', [
            ...['name' => $searchName],
            ...['quantity' => 2, 'comparison' => 'contains'],
        ]))->assertOk()
            ->assertJsonCount(1, 'products.data');

        $nameSuffix = $this->faker->unique()->numerify('###');

        Product::factory()
            ->count(28)
            ->hasStock(1, ['quantity' => 2])
            ->create(['name' => "A lot of products {$nameSuffix}"]);

        $this->getJson(route('search.product', ['name' => 'A lot of products', 'comparison' => 'contains']))
            ->assertOk()
            ->assertJsonCount(config('repositories.per_page'), 'products.data')
            ->assertJson(fn(AssertableJson $json) => $json->has('products',
                fn(AssertableJson $json) => $json
                    ->where('current_page', 1)
                    ->where('per_page', config('repositories.per_page'))
                    ->where('total', 28)
                    ->etc())
                ->etc());

        $this->getJson(route('search.product',
            ['name' => 'A lot of products', 'comparison' => 'contains', 'page' => 2]))
            ->assertOk()
            ->assertJsonCount(config('repositories.per_page'), 'products.data')
            ->assertJson(fn(AssertableJson $json) => $json->has('products',
                fn(AssertableJson $json) => $json
                    ->where('current_page', 2)
                    ->where('per_page', config('repositories.per_page'))
                    ->where('total', 28)
                    ->etc())
                ->etc());

        $this->getJson(route('search.product',
            ['name' => 'A lot of products', 'comparison' => 'contains', 'page' => 3]))
            ->assertOk()
            ->assertJsonCount(8, 'products.data')
            ->assertJson(fn(AssertableJson $json) => $json->has('products',
                fn(AssertableJson $json) => $json
                    ->where('current_page', 3)
                    ->where('per_page', config('repositories.per_page'))
                    ->where('total', 28)
                    ->etc())
                ->etc());
    }
}
