<?php

namespace App\Repositories\Product;

use App\Models\Product;
use Torann\LaravelRepository\Repositories\AbstractRepository;

class ProductRepository extends AbstractRepository implements ProductRepositoryInterface
{
    protected string $model = Product::class;
}
