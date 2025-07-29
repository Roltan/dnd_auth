<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;

class UserController extends Controller
{
    /**
     * @OA\GET(
     *     path="/info",
     *     summary="Информация об пользователе",
     *     tags={"Аунтификация"},
     *     @OA\Parameter(
     *          name="Authorization",
     *          in="header",
     *          required=true,
     *          description="Bearer Token",
     *          @OA\Schema(type="string", example="Bearer 1|pVEhLK1z9QCN6CSn5FG5djNqiWC6XZc1zoeDfMLE375c7750")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(property="id", type="int", example="1"),
     *              @OA\Property(property="name", type="string", example="test"),
     *              @OA\Property(property="email", type="string", example="test@test.ru"),
     *          )
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Authentication Error",
     *          @OA\JsonContent(ref="#/components/schemas/AuthenticationErrorResponse")
     *      )
     * )
     */
    public function info()
    {
        return request()->user()->only([
            'id',
            'name',
            'email'
        ]);
    }

    /**
     * @OA\GET(
     *     path="/check",
     *     summary="Проверка авторизации пользователя",
     *     tags={"Аунтификация"},
     *     @OA\Parameter(
     *          name="Authorization",
     *          in="header",
     *          required=false,
     *          description="Bearer Token",
     *          @OA\Schema(type="string", example="Bearer 1|pVEhLK1z9QCN6CSn5FG5djNqiWC6XZc1zoeDfMLE375c7750")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="bool", example="true"),
     *              @OA\Property(property="authenticated", type="bool", example="true"),
     *          )
     *      )
     * )
     */
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
