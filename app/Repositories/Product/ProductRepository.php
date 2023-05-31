<?php

namespace App\Repositories\Product;

use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Torann\LaravelRepository\Repositories\AbstractRepository;

class ProductRepository extends AbstractRepository implements ProductRepositoryInterface
{
    protected string $model = Product::class;

    protected $searchable = [
        'name',
        'sku',
        //        'price',
        'rate',
        'quantity',
        'description',
        'additional_information',
        'category' => 'product_category_id',
        'query' => [
            'name',
            'sku',
            'description',
            'additional_information',
        ],
    ];

    public function update(Model $entity, array $attributes): bool
    {
        $productAttributes = Arr::except($attributes, 'quantity');

        return parent::update($entity, $productAttributes);
    }
}
