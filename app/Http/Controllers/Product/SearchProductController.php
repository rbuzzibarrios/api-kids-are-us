<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\SearchProductRequest;
use App\Repositories\Product\ProductRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;

class SearchProductController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(SearchProductRequest $request, ProductRepositoryInterface $productRepository): JsonResponse
    {
        $products = [];

        if ($request->has('query')) {
            $products = $productRepository->search($request->get('query'))->paginate();

            return response()->success(compact('products'));
        }

        $validated = $request->validated();

        if (empty($validated) && !empty($request->all())) {
            return response()->success(compact('products'));
        }

        $comparison = $request->get('comparison', 'strict');

        if (empty($comparison) || $comparison === 'strict') {
            $products = $productRepository->search(
                Arr::except($validated, ['comparison'])
            )->paginate();

            return response()->success(compact('products'));
        }

        if ($comparison === 'contains') {
            $products = $productRepository->addScopeQuery(function (Builder $query) use ($validated) {

                $filters = Arr::except($validated, [
                    'comparison',
                    'category',
                    'quantity',
                    'price',
                    'page',
                    'skipPage',
                ]);

                foreach ($filters as $column => $value) {
                    $query->where($query->qualifyColumn($column), 'LIKE', "%{$value}%");
                }

                return $query;
            })->search($request->validated(['category', 'quantity']))->paginate();
        }

        return response()->success(compact('products'));
    }
}
