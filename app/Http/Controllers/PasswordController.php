<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmailRequest;
use App\Http\Requests\PasswordRequest;
use App\Http\Requests\ResetRequest;
use App\Mail\PasswordResetMail;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;

class PasswordController extends Controller
{
    /**
     * @OA\POST(
     *     path="/password/forgot",
     *     summary="Запрос на смену пароля",
     *     tags={"Аунтификация"},
     *     @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  required={"email"},
     *                  @OA\Property(property="email", type="string", example="test@test.ru"),
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="bool", example="true"),
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="user not found",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="bool", example="false"),
     *              @OA\Property(property="message", type="string", example="user not found"),
     *          )
     *      ),
     *      @OA\Response(
     *          response="422",
     *          description="Validation error",
     *          @OA\JsonContent(ref="#/components/schemas/ValidationErrorResponse")
     *      )
     * )
     */
    public function forgot(EmailRequest $request): Response
    {
        $user = User::query()
            ->where('email', $request->email)
            ->first();

        if (!$user)
            return response(['status' => false, 'message' => 'user not found'], 404);

        $token = app('auth.password.broker')->createToken($user);

        $resetLink = '/password/reset?token=' . $token . '&email=' . $request->email;

        Mail::to($user->email)->send(new PasswordResetMail($resetLink));

        return response(['status' => true]);
    }

    /**
     * @OA\POST(
     *     path="/password/viewReset",
     *     summary="Проверка доступа к смене пароля",
     *     tags={"Аунтификация"},
     *     @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  required={"email", "token"},
     *                  @OA\Property(property="email", type="string", example="test@test.ru"),
     *                  @OA\Property(property="token", type="string", example="2d24171acbb66167b4b440041363f248875db1067c84367ada5f8240c4423778"),
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="bool", example="true"),
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="user not found",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="bool", example="false"),
     *              @OA\Property(property="message", type="string", example="user not found"),
     *          )
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="token not access",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="bool", example="false"),
     *              @OA\Property(property="message", type="string", example="token not access"),
     *          )
     *      ),
     *      @OA\Response(
     *          response="422",
     *          description="Validation error",
     *          @OA\JsonContent(ref="#/components/schemas/ValidationErrorResponse")
     *      )
     * )
     */
    public function viewReset(ResetRequest $request): Response
    {
        $user = User::query()
            ->where('email', $request->email)
            ->first();
        if (!$user)
            return response(['status' => false, 'message' => 'user not found'], 404);

        if (!Password::tokenExists($user, $request->token))
            return response(['status' => false, 'message' => 'token not access'], 403);

        return response([
            'status' => true
        ]);
    }

    /**
     * @OA\POST(
     *     path="/password/change",
     *     summary="Смена пароля",
     *     tags={"Аунтификация"},
     *     @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  required={"email", "token"},
     *                  @OA\Property(property="email", type="string", example="test@test.ru"),
     *                  @OA\Property(property="password", type="string", example="test1234"),
     *                  @OA\Property(property="password_confirmation", type="string", example="test1234"),
     *                  @OA\Property(property="token", type="string", example="2d24171acbb66167b4b440041363f248875db1067c84367ada5f8240c4423778"),
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="bool", example="true"),
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="user not found",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="bool", example="false"),
     *              @OA\Property(property="message", type="string", example="user not found"),
     *          )
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="token not access",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="bool", example="false"),
     *              @OA\Property(property="message", type="string", example="token not access"),
     *          )
     *      ),
     *      @OA\Response(
     *          response="422",
     *          description="Validation error",
     *          @OA\JsonContent(ref="#/components/schemas/ValidationErrorResponse")
     *      )
     * )
     */
    public function change(PasswordRequest $request): Response
    {
        $user = User::query()
            ->where('email', $request->email)
            ->first();
        if ($user == null)
            return response(['status' => false, 'message' => 'user not found'], 404);

        if (!Password::tokenExists($user, $request->token))
            return response(['status' => false, 'message' => 'token not access'], 403);

        $user->update([
            'password' => Hash::make($request->password)
        ]);
        Password::deleteToken($user);

        return response(['status' => true]);
    }
}
