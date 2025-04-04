<?php

namespace App\Services;

use App\Http\Requests\Auth\EmailRequest;
use App\Http\Requests\Auth\PasswordRequest;
use App\Mail\PasswordResetMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;

class PasswordService
{
    public function forgot(Request $request): Response
    {
        $user = User::query()
            ->where('email', $request->email)
            ->first();

        if (!$user)
            return response(['status' => false, 'error' => 'user not found'], 404);

        $token = app('auth.password.broker')->createToken($user);

        $resetLink = '/password/reset/?token=' . $token . '&email=' . $request->email;

        Mail::to($user->email)->send(new PasswordResetMail($resetLink));

        return response(['status' => true]);
    }

    public function viewReset(Request $request): Response
    {
        $token = $request->token;
        $email = $request->email;

        if (!Password::tokenExists(User::where('email', $email)->first(), $token))
            return response(['status' => false, 'error' => 'user not found'], 404);

        return response([
            'status' => true,
            'email' => $email
        ]);
    }

    public function change(Request $request): Response
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
