<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(RegisterRequest $request): Response
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        return response([
            'status' => true,
            'token' => $user->createToken($user->name)->plainTextToken
        ]);
    }

    public function login(LoginRequest $request): Response
    {
        $user = User::query()
            ->where('email', $request->email)
            ->first();

        if (!$user or !Hash::check($request->password, $user->password))
            return response(['status' => false, 'message' => 'Invalid credentials'], 401);

        return response([
            'status' => true,
            'token' => $user->createToken($user->name)->plainTextToken
        ]);
    }

    public function logout(): Response
    {
        request()->user()->currentAccessToken()->delete();
        return response(['status' => true]);
    }
}
