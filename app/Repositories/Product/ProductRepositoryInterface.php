<?php

namespace App\Repositories\Product;

use Closure;
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
     * @param  array|string[]  $columns
     */
    public function count(array $columns = ['*']): int;
}
