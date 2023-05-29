<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use App\Repositories\Product\ProductRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    public function __construct(private ProductRepositoryInterface $productRepository)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        return response()->json();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request): JsonResponse
    {
        try {
            \DB::beginTransaction();

            $product = $this->productRepository->create($request->validated())->load('category', 'stock');

            \DB::commit();

            return response()->success([
                'message' => __('product.store.success'),
                ...compact('product'),
            ]);
        } catch (\Exception $exception) {
            Log::error($exception->getMessage(), [$exception->getTraceAsString()]);

            \DB::rollBack();

            return response()->error(__('product.store.error'));
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product): JsonResponse
    {
        return response()->json();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product): JsonResponse
    {
        return response()->json();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product): JsonResponse
    {
        return response()->json();
    }
}
