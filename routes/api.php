<?php

use App\Http\Controllers\Auth\LoginUserController;
use App\Http\Controllers\Product\ProductController;
use App\Http\Controllers\Product\SearchProductController;
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
    Route::post('product', [ProductController::class, 'store'])->name('store.product');
    Route::put('product/{product}', [ProductController::class, 'update'])->name('update.product');
    Route::delete('product/{product}', [ProductController::class, 'destroy'])->name('delete.product');
    Route::get('product/{product}', [ProductController::class, 'show'])
        ->name('show.product')
        ->withoutMiddleware('role:administrator|editor');
});

Route::get('product/search', [SearchProductController::class, '__invoke'])
    ->name('search.product');
