<?php

namespace App\Repositories\Sale;

use App\Models\ProductSale;
use Torann\LaravelRepository\Repositories\AbstractRepository;

class ProductSaleRepository extends AbstractRepository implements ProductSaleRepositoryInterface
{
    protected string $model = ProductSale::class;

    public function totalProfit(): float
    {
        return $this->cacheCallback(__FUNCTION__, func_get_args(), function () {
            $this->newQuery();

            return $this->query->sum('total_price * quantity');
        });
    }
}
