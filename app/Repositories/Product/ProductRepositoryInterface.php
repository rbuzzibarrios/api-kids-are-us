<?php

namespace App\Repositories\Product;

use App\Models\Product;
use Closure;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Torann\LaravelRepository\Contracts\RepositoryContract;

interface ProductRepositoryInterface extends RepositoryContract
{
    /**
     * Add query scope.
     *
     *
     * @return static
     */
    public function addScopeQuery(Closure $scope);

    public function applySearch(array $queries): ProductRepositoryInterface;

    /**
     * Retrieve the "count" result of the query.
     *
     *
     * @return int
     */
    public function count(array $columns = ['*']);

    public function sell(Product $product, Authenticatable $purchaser, int $quantity = 1): Product;
}
