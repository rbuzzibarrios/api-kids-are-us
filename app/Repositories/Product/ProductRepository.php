<?php

namespace App\Repositories\Product;

use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Torann\LaravelRepository\Repositories\AbstractRepository;

class ProductRepository extends AbstractRepository implements ProductRepositoryInterface
{
    protected string $model = Product::class;

    public function update(Model $entity, array $attributes): bool
    {
        $productAttributes = Arr::except($attributes, 'quantity');

        return parent::update($entity, $productAttributes);
    }
}
