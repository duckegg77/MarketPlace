<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function __invoke(RegisterRequest $request)
    {
        $data = $request->validated();

        $user = User::query()->create([
            'name' => $data['name'],
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'pin_hash' => Hash::make($data['pin']),
            'public_pgp_key' => $data['public_pgp_key'],
            'role' => $data['role'] ?? 'buyer',
        ]);

        $token = method_exists($user, 'createToken') ? $user->createToken('api')->plainTextToken : null;

        return response()->json(['user' => $user, 'token' => $token], 201);
    }
}
