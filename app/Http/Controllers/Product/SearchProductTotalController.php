<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\SearchProductRequest;
use App\Repositories\Product\ProductRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class SearchProductTotalController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(SearchProductRequest $request, ProductRepositoryInterface $productRepository): JsonResponse
    {
        try {
            $total = $productRepository->applySearch($request->validated())->count();

            return response()->success(compact('total'));
        } catch (\Exception $exception) {
            Log::error($exception->getMessage(), [$exception->getTraceAsString()]);

            return response()->error(__('product.search.error'));
        }
    }
}
