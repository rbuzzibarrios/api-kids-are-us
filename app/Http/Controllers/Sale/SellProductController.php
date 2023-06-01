<?php

namespace App\Http\Controllers\Sale;

use App\Http\Controllers\Controller;
use App\Http\Requests\Sale\SellProductRequest;
use App\Models\Product;
use App\Repositories\Product\ProductRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
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
    ): JsonResponse {
        try {
            \DB::beginTransaction();

            $productRepository->sell($product, Auth::user(), $request->validated('qty'));

            \DB::commit();

            return response()->success(['message' => __('sale.store.success')]);
        } catch (\Exception $exception) {
            Log::error($exception->getMessage(), [$exception->getTraceAsString()]);

            \DB::rollBack();

            return response()->error(__('sale.store.error'));
        }
    }
}
