<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\Payments\LiveRateSyncService;
use Illuminate\Http\Request;

class RateController extends Controller
{
    public function sync(Request $request, LiveRateSyncService $sync)
    {
        if (! in_array($request->user()->role, ['admin', 'moderator'], true)) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        return response()->json([
            'synced' => true,
            'rates' => $sync->sync(),
        ]);
    }
}
