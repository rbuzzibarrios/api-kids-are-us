<?php

namespace App\Repositories\Product;

use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Torann\LaravelRepository\Repositories\AbstractRepository;

class ProductRepository extends AbstractRepository implements ProductRepositoryInterface
{
    protected string $model = Product::class;

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
}
