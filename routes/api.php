<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\AuthenticationController;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('login', [AuthenticationController::class, 'login']);
Route::post('register', [AuthenticationController::class, 'register']);
Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::get('user', function (Request $request) {
            return $request->user();
        });
        Route::post('logout', [AuthenticationController::class, 'logout']);
        Route::post('update', [AuthenticationController::class, 'update']);
    });
});
Route::prefix('products')->name('products.')->group(function () {
    Route::get('/', [ProductController::class, 'index'])->name('index');
    Route::post('/', [ProductController::class, 'store'])->name('store');
    Route::get('/low-stock', [ProductController::class, 'lowStock'])->name('low-stock');
    Route::get('/{product}', [ProductController::class, 'show'])->name('show');
    Route::put('/{product}', [ProductController::class, 'update'])->name('update');
    Route::delete('/{product}', [ProductController::class, 'destroy'])->name('destroy');
    Route::post('/{product}/stock', [ProductController::class, 'adjustStock'])->name('adjust-stock');
});
