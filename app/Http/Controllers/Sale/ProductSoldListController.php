<?php

namespace App\Http\Controllers\Sale;

use App\Http\Controllers\Controller;
use App\Repositories\Product\ProductRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProductSoldListController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, ProductRepositoryInterface $productRepository)
    {
        try {
            $products = $productRepository->sold()->toArray();

            return response()->success(compact('products'));
        } catch (\Exception $exception) {
            Log::error($exception->getMessage(), [$exception->getTraceAsString()]);

            return response()->error(__('sale.products_sold_list.error'));
        }
    }
}
