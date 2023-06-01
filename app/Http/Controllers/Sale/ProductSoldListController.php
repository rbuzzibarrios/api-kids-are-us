<?php

namespace App\Http\Controllers\Sale;

use App\Http\Controllers\Controller;
use App\Repositories\Product\ProductRepositoryInterface;
use Illuminate\Http\Request;

class ProductSoldListController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, ProductRepositoryInterface $productRepository)
    {
        $products = $productRepository->sold();

        return response()->success(compact('products'));
    }
}
