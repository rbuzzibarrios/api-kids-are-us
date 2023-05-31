<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\SearchProductRequest;
use App\Repositories\Product\ProductRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class SearchProductController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(SearchProductRequest $request, ProductRepositoryInterface $productRepository)
    {
        $products = [];

        if ($request->has('query')) {
            $products = $productRepository->search($request->get('query'))->paginate();

            return response()->success(compact('products'));
        }

        $comparison = $request->get('comparison', 'strict');

        if (empty($comparison) || $comparison === 'strict') {
            $products = $productRepository->search(
                Arr::except($request->validated(), ['query', 'comparison'])
            )->paginate();

            return response()->success(compact('products'));
        }

        if ($comparison === 'contains') {
            $products = $productRepository->addScopeQuery(function (Builder $query) use ($request) {

                $filters = Arr::except($request->validated(), ['query', 'comparison', 'category']);

                foreach ($filters as $column => $value) {
                    $query->where($query->qualifyColumn($column), 'LIKE', "%{$value}%");
                }

                return $query;
            })->search([])->paginate();
        }

        return response()->success(compact('products'));
    }
}
