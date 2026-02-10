<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function update(Request $request)
    {
        $data = $request->validate([
            'bio' => ['nullable', 'string', 'max:1000'],
            'avatar' => ['nullable', 'file', 'image', 'max:4096'],
        ]);

        $user = $request->user();
        $user->bio = $data['bio'] ?? $user->bio;

        if ($request->hasFile('avatar')) {
            $user->avatar_path = $request->file('avatar')->store('private/avatars', 'local');
        }

        $user->save();

        return response()->json($user);
    }
}
