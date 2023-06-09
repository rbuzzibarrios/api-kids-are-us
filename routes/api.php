<?php

use App\Http\Controllers\Auth\LoginUserController;
use App\Http\Controllers\Product\ProductController;
use App\Http\Controllers\Product\SearchProductController;
use App\Http\Controllers\Product\SearchProductTotalController;
use App\Http\Controllers\Sale\ProductSoldListController;
use App\Http\Controllers\Sale\SellProductController;
use App\Http\Controllers\Sale\TotalProfitController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('login', [LoginUserController::class, '__invoke'])->name('login');

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(['auth:sanctum', 'role:administrator|editor'])->group(function () {
    Route::prefix('product')->group(function () {
        Route::post('/', [ProductController::class, 'store'])->name('store.product');
        Route::put('/{product}', [ProductController::class, 'update'])->name('update.product');
        Route::delete('/{product}', [ProductController::class, 'destroy'])->name('delete.product');
        Route::get('/{product}', [ProductController::class, 'show'])
            ->name('show.product')
            ->withoutMiddleware('role:administrator|editor');
    });

    Route::prefix('products')
        ->withoutMiddleware('role:administrator|editor')
        ->group(function () {
            Route::get('/search', [SearchProductController::class, '__invoke'])
                ->name('search.product');
            Route::get('/search/total', [SearchProductTotalController::class, '__invoke'])
                ->name('search.product.count');
            Route::get('/search/total', [SearchProductTotalController::class, '__invoke'])
                ->name('search.product.count');
            Route::get('sold', [ProductSoldListController::class, '__invoke'])
                ->name('product.sold.list')
                ->withoutMiddleware('role:administrator|editor');
        });

    Route::put('sell/{product}', [SellProductController::class, '__invoke'])
        ->name('sell.product')
        ->withoutMiddleware('role:administrator|editor');

    Route::get('totalprofit', [TotalProfitController::class, '__invoke'])
        ->name('total-profit')
        ->withoutMiddleware('role:administrator|editor');
});
