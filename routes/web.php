<?php

use App\Http\Controllers\Web\AdminDashboardController;
use App\Http\Controllers\Web\BuyerAreaController;
use App\Http\Controllers\Web\VendorAreaController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => view('welcome'));
Route::middleware('auth')->get('/admin/dashboard', AdminDashboardController::class);
Route::middleware('auth')->get('/buyer/area', BuyerAreaController::class);
Route::middleware('auth')->get('/vendor/area', VendorAreaController::class);
