<?php

namespace App\Repositories\Product;

use Closure;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
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

    public function applySearch(array $queries): LengthAwarePaginator;
}
