<?php

namespace App\Services;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RefreshRequest;
use App\Http\Requests\RegRequest;
use App\Models\RefreshToken;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthServices
{
    public function register(RegRequest $request): Response
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
//        Auth::login($user);

        return $this->generateTokenResponse($user);
    }

    public function login(LoginRequest $request): Response
    {
        $credentials = [
            'email' => $request->email,
            'password' => $request['password']
        ];

        if (!Auth::attempt($credentials))
            return response(['status' => false, 'error' => 'Неверные данные'], 401);

        return $this->generateTokenResponse(Auth::user());
    }

    public function logout(): Response
    {
        request()->user()->currentAccessToken()->delete();
        return response(['status' => true]);
    }

    public function refresh(RefreshRequest $request): Response
    {
        $refreshToken = RefreshToken::query()
            ->where('token', $request->refresh_token)
            ->where('expires_at', '>', now())
            ->first();

        if (!$refreshToken)
            return response(['status'=>false, 'error' => 'Не правильный рефреш токен'], 401);

        $refreshToken->delete();
        $refreshToken->user->tokens()->delete();

        return $this->generateTokenResponse($refreshToken->user);
    }

    protected function generateTokenResponse(User $user): Response
    {
        $accessToken = $user->createToken(
            'access_token',
            ['*'],
            now()->addMinutes(config('sanctum.access_token_expiration', 15))
        );

        $refreshToken = $this->createRefreshToken($user);

        return response([
            'access_token' => $accessToken->plainTextToken,
            'refresh_token' => $refreshToken->token,
            'token_type' => 'bearer',
            'expires_in' => config('sanctum.access_token_expiration', 15) * 60
        ]);
    }

    protected function createRefreshToken(User $user): RefreshToken
    {
        return RefreshToken::create([
            'user_id' => $user->id,
            'token' => Str::random(64),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'expires_at' => now()->addDays(config('sanctum.refresh_token_expiration', 30))
        ]);
    }
}
