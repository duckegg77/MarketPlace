<?php

use App\Http\Controllers\Web\AdminDashboardController;
use App\Http\Controllers\Web\BuyerAreaController;
use App\Http\Controllers\Web\MarketplaceController;
use App\Http\Controllers\Web\VendorAreaController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => view('welcome'));
Route::get('/marketplace', [MarketplaceController::class, 'index'])->name('marketplace.index');

Route::middleware('auth')->group(function () {
    Route::get('/admin/dashboard', AdminDashboardController::class);
    Route::get('/buyer/area', BuyerAreaController::class);
    Route::get('/vendor/area', VendorAreaController::class);
});
