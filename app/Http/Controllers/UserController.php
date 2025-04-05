<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;

class UserController extends Controller
{
    public function info()
    {
        return request()->user();
    }

    public function check(Request $request)
    {
        $token = $request->bearerToken();

        if (!$token)
            return response([
                'status' => true,
                'authenticated' => false
            ]);

        $accessToken = PersonalAccessToken::findToken($token);

        if (!$accessToken)
            return response([
                'status' => true,
                'authenticated' => false
            ]);

        if ($accessToken->expires_at && $accessToken->expires_at->isPast())
            return response([
                'status' => true,
                'authenticated' => false
            ]);

        $user = $accessToken->tokenable;

        if (!$user)
            return response([
                'status' => true,
                'authenticated' => false
            ]);

        return response()->json([
            'status' => true,
            'authenticated' => true,
        ]);
    }
}
