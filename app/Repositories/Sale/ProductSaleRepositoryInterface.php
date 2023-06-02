<?php

namespace App\Repositories\Sale;

use Torann\LaravelRepository\Contracts\RepositoryContract;

interface ProductSaleRepositoryInterface extends RepositoryContract
{
    public function totalProfit(): float;
}
