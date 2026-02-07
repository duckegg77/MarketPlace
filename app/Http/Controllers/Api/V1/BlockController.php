<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserBlock;
use Illuminate\Http\Request;

class BlockController extends Controller
{
    public function store(Request $request, User $user)
    {
        $block = UserBlock::query()->firstOrCreate([
            'blocker_id' => $request->user()->id,
            'blocked_id' => $user->id,
        ], ['reason' => $request->string('reason')->toString()]);

        return response()->json($block, 201);
    }

    public function destroy(Request $request, User $user)
    {
        UserBlock::query()->where('blocker_id', $request->user()->id)->where('blocked_id', $user->id)->delete();
        return response()->json(status: 204);
    }
}
