<?php

namespace App\Repositories\Product;

use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Arr;
use Torann\LaravelRepository\Repositories\AbstractRepository;

class ProductRepository extends AbstractRepository implements ProductRepositoryInterface
{
    protected string $model = Product::class;

    protected int $cacheMinutes = 1;

    /**
     * @var array
     */
    protected $searchable = [
        'name',
        'sku',
        'price',
        'rate',
        'quantity' => 'product_stock:quantity,product_id,id',
        'description',
        'additional_information',
        'category' => 'product_category_id',
        'query' => [
            'name',
            'sku',
            'description',
            'additional_information',
            'price',
            'rate',
        ],
    ];

    public function update(Model $entity, array $attributes): bool
    {
        $productAttributes = Arr::except($attributes, 'quantity');

        return parent::update($entity, $productAttributes);
    }

    public function applySearch(array $queries): ProductRepositoryInterface
    {
        if ($query = Arr::get($queries, 'query', null)) {
            return $this->search($query);
        }

        if (empty($queries) && ! empty(request()->all())) {
            return $this;
        }

        $comparison = Arr::get($queries, 'comparison', 'strict');

        if (empty($comparison) || $comparison === 'strict') {
            return $this->search(Arr::except($queries, ['comparison']));
        }

        if ($comparison === 'contains') {
            return $this->addScopeQuery(function (Builder $query) use ($queries) {

                $filters = Arr::except($queries, [
                    'comparison',
                    'category',
                    'quantity',
                    'price',
                    'page',
                    'skipPage',
                ]);

                foreach ($filters as $column => $value) {
                    $query->where($query->qualifyColumn($column), 'LIKE', "%{$value}%");
                }

                return $query;
            })->search(Arr::only($queries, ['category', 'quantity']));
        }

        return $this;
    }

    public function sell(Product $product, Authenticatable $purchaser, int $quantity = 1): Product
    {
        $product->stock()->decrement('quantity', $quantity);

        $product->sales()->create([
            'purchaser_id' => $purchaser->id,
            'total_price' => $product->price,
            ...compact('quantity'),
        ])->save();

        return $product->load(['stock', 'sales'])->refresh();
    }

    public function sold(): Builder|Collection
    {
        return $this->cacheCallback(__FUNCTION__, func_get_args(), function () {

            $this->newQuery();

            return $this // @phpstan-ignore-line
                ->query
                ->with(['category', 'sales'])
                ->sold()
                ->get();
        });
    }
}
