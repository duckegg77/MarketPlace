<?php

use App\Http\Controllers\Web\AdminDashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => view('welcome'));
Route::middleware('auth')->get('/admin/dashboard', AdminDashboardController::class);
