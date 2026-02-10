<?php

use App\Http\Controllers\Api\V1\Auth\RegisterController;
use App\Http\Controllers\Api\V1\BlockController;
use App\Http\Controllers\Api\V1\CheckoutController;
use App\Http\Controllers\Api\V1\DisputeController;
use App\Http\Controllers\Api\V1\EscrowController;
use App\Http\Controllers\Api\V1\MessageController;
use App\Http\Controllers\Api\V1\ProductController;
use App\Http\Controllers\Api\V1\ProfileController;
use App\Http\Controllers\Api\V1\RateController;
use App\Http\Controllers\Api\V1\ShippingMethodController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::post('/auth/register', RegisterController::class);
    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/shipping-methods', [ShippingMethodController::class, 'index']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/checkout', CheckoutController::class);

        Route::post('/products/{product}/ratings', [ProductController::class, 'rateProduct']);
        Route::post('/vendors/{vendor}/ratings', [ProductController::class, 'rateVendor']);
        Route::post('/favorites/toggle', [ProductController::class, 'toggleFavorite']);

        Route::post('/sub-orders/{subOrder}/messages', [MessageController::class, 'store']);
        Route::post('/reports', [MessageController::class, 'report']);

        Route::post('/disputes', [DisputeController::class, 'store']);
        Route::patch('/disputes/{dispute}/resolve', [DisputeController::class, 'resolve']);
        Route::post('/sub-orders/{subOrder}/release', [EscrowController::class, 'buyerRelease']);

        Route::post('/shipping-methods', [ShippingMethodController::class, 'store']);
        Route::patch('/profile', [ProfileController::class, 'update']);
        Route::post('/users/{user}/block', [BlockController::class, 'store']);
        Route::delete('/users/{user}/block', [BlockController::class, 'destroy']);
        Route::post('/rates/sync', [RateController::class, 'sync']);
    });
});
