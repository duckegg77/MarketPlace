<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Dispute;
use App\Models\Order;
use App\Models\Product;
use App\Models\Report;
use App\Models\User;
use App\Models\UserBlock;
use App\Models\VendorProfile;

class AdminDashboardController extends Controller
{
    public function __invoke()
    {
        return response()->json([
            'kpis' => [
                'users' => User::query()->count(),
                'vendors_pending_approval' => VendorProfile::query()->where('verification_status', 'pending')->count(),
                'products' => Product::query()->count(),
                'orders' => Order::query()->count(),
                'open_disputes' => Dispute::query()->where('status', '!=', 'resolved')->count(),
                'open_reports' => Report::query()->where('status', 'open')->count(),
                'active_blocks' => UserBlock::query()->count(),
            ],
            'recent_disputes' => Dispute::query()->latest()->limit(10)->get(),
            'recent_reports' => Report::query()->latest()->limit(10)->get(),
            'recent_orders' => Order::query()->latest()->limit(10)->get(),
        ]);
    }
}
