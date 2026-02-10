<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\SubOrder;
use App\Models\User;
use Illuminate\Http\Request;

class VendorAreaController extends Controller
{
    public function __invoke(Request $request)
    {
        /** @var User $user */
        $user = $request->user();

        return response()->json([
            'profile' => [
                'name' => $user->name,
                'username' => $user->username,
                'bio' => $user->bio,
            ],
            'products_count' => Product::query()->where('vendor_id', $user->id)->count(),
            'sub_orders_count' => SubOrder::query()->where('vendor_id', $user->id)->count(),
            'recent_sub_orders' => SubOrder::query()->where('vendor_id', $user->id)->latest()->limit(10)->get(),
        ]);
    }
}
