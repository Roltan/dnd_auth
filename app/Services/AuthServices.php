<?php

namespace App\Services;

use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class AuthServices
{
    public function login(Request $request): Response
    {
        $user = User::query()
            ->where('email', $request->email)
            ->first();

        if (!$user or !Hash::check($request->password, $user->password))
            return response(['status' => true, 'error' => 'Invalid credentials'], 401);

        return response([
            'status' => true,
            'token' => $user->createToken($user->name)->plainTextToken
        ]);
    }
}
