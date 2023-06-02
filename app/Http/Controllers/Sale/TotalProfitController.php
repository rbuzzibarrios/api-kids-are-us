<?php

namespace App\Http\Controllers\Sale;

use App\Http\Controllers\Controller;
use App\Repositories\Sale\ProductSaleRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class TotalProfitController extends Controller
{
    public function __invoke(Request $request, ProductSaleRepositoryInterface $productSaleRepository): JsonResponse
    {
        try {
            $totalProfit = Cache::remember('total-profit', 180, function () use ($productSaleRepository) {
                return $productSaleRepository->totalProfit();
            });

            return response()->success(compact('totalProfit'));
        } catch (\Exception $exception) {
            Log::error($exception->getMessage(), [$exception->getTraceAsString()]);

            return response()->error(__('sale.total_profit.error'));
        }
    }
}
