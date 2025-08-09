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
    /**
     * @OA\POST(
     *     path="/register",
     *     summary="регистрация",
     *     tags={"Аунтификация"},
     *     @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  required={"name", "email", "password", "password_confirmation"},
     *                  @OA\Property(property="name", type="string", example="test"),
     *                  @OA\Property(property="email", type="string", example="test@test.ru"),
     *                  @OA\Property(property="password", type="string", example="test1234"),
     *                  @OA\Property(property="password_confirmation", type="string", example="test1234"),
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="bool", example="true"),
     *              @OA\Property(property="token", type="string", example="1|pVEhLK1z9QCN6CSn5FG5djNqiWC6XZc1zoeDfMLE375c7750"),
     *          )
     *      ),
     *      @OA\Response(
     *          response="422",
     *          description="Validation error",
     *          @OA\JsonContent(ref="#/components/schemas/ValidationErrorResponse")
     *      )
     * )
     */
    public function register(RegisterRequest $request): Response
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        $token = $user->createToken($user->name)->plainTextToken;
        return response([
            'status' => true,
            'token' => $user->createToken($user->name)->plainTextToken
        ])->withCookie(cookie('auth_token', $token, 7 * 24 * 60 * 60, httpOnly: false));
    }

    /**
     * @OA\POST(
     *     path="/login",
     *     summary="Авторизация",
     *     tags={"Аунтификация"},
     *     @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  required={"email", "password"},
     *                  @OA\Property(property="email", type="string", example="test@test.ru"),
     *                  @OA\Property(property="password", type="string", example="test1234"),
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="bool", example="true"),
     *              @OA\Property(property="token", type="string", example="1|pVEhLK1z9QCN6CSn5FG5djNqiWC6XZc1zoeDfMLE375c7750"),
     *          )
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Validation error",
     *          @OA\JsonContent(ref="#/components/schemas/ValidationErrorResponse")
     *      )
     * )
     */
    public function login(LoginRequest $request): Response
    {
        $user = User::query()
            ->where('email', $request->email)
            ->first();

        if (!$user or !Hash::check($request->password, $user->password))
            return response(['status' => false, 'message' => 'Invalid credentials'], 401);

        $token = $user->createToken($user->name)->plainTextToken;
        return response([
            'status' => true,
            'token' => $token,
        ])->cookie(cookie('auth_token', $token, 7 * 24 * 60 * 60, secure: false, httpOnly: false));
    }

    /**
     * @OA\GET(
     *      path="/logout",
     *      summary="выход из акаунта",
     *      tags={"Аунтификация"},
     *      @OA\Parameter(
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
     *              @OA\Property(property="status", type="bool", example="true"),
     *          )
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Authentication Error",
     *          @OA\JsonContent(ref="#/components/schemas/AuthenticationErrorResponse")
     *      )
     * )
     */
    public function logout(): Response
    {
        request()->user()->currentAccessToken()->delete();
        return response(['status' => true]);
    }
}
