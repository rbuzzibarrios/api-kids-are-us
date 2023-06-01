<?php

namespace App\Http\Controllers\Sale;

use App\Http\Controllers\Controller;
use App\Http\Requests\Sale\SellProductRequest;
use App\Models\Product;
use App\Repositories\Product\ProductRepositoryInterface;
use Illuminate\Support\Facades\Log;

class SellProductController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(
        SellProductRequest $request,
        Product $product,
        ProductRepositoryInterface $productRepository
    ) {
        try {
            $productRepository->sell($product, \Auth::user(), $request->validated('qty'));

            return response()->success(['message' => __('sale.create.success')]);
        } catch (\Exception $exception) {
            Log::error($exception->getMessage(), [$exception->getTraceAsString()]);

            return response()->error(__('sale.create.error'));
        }
    }
}
