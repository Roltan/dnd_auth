<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmailRequest;
use App\Http\Requests\PasswordRequest;
use App\Http\Requests\ResetRequest;
use App\Mail\PasswordResetMail;
use App\Services\PasswordService;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;

class PasswordController extends Controller
{
    public function __construct(
        private PasswordService $passwordService
    )
    {
    }

    public function forgot(EmailRequest $request): Response
    {
        $user = User::query()
            ->where('email', $request->email)
            ->first();

        if (!$user)
            return response(['status' => false, 'message' => 'user not found'], 404);

        $token = app('auth.password.broker')->createToken($user);

        $resetLink = '/password/reset/?token=' . $token . '&email=' . $request->email;

        Mail::to($user->email)->send(new PasswordResetMail($resetLink));

        return response(['status' => true]);
    }

    public function viewReset(ResetRequest $request): Response
    {
        $token = $request->token;
        $email = $request->email;

        if (!Password::tokenExists(User::where('email', $email)->first(), $token))
            return response(['status' => false, 'message' => 'user not found'], 404);

        return response([
            'status' => true,
            'email' => $email
        ]);
    }

    public function change(PasswordRequest $request): Response
    {
        $user = User::query()
            ->where('email', $request->email)
            ->first();
        if ($user == null)
            return response(['status' => false, 'message' => 'user not found'], 404);

        $user->update([
            'password' => Hash::make($request->password)
        ]);
        return response(['status' => true]);
    }
}
