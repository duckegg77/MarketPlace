<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;

class BuyerAreaController extends Controller
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
            'orders_count' => Order::query()->where('buyer_id', $user->id)->count(),
            'recent_orders' => Order::query()->where('buyer_id', $user->id)->latest()->limit(10)->get(),
        ]);
    }
}
