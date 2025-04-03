<?php

namespace App\Services;

use App\Http\Requests\EmailRequest;
use App\Http\Requests\PasswordRequest;
use App\Mail\PasswordResetMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;

class PasswordService
{
    public function forgot(EmailRequest $request): Response
    {
        $user = User::query()
            ->where('email', $request->email)
            ->first();

        if ($user == null)
            return response(['status' => false, 'error' => 'Неверная почта'], 404);

        $token = app('auth.password.broker')->createToken($user);

        $resetLink = '/password/reset/?token=' . $token . '&email=' . $request->email;

        Mail::to($user->email)->send(new PasswordResetMail($resetLink));

        return response(['status' => true]);
    }

    public function viewResetPassword(Request $request): Response
    {
        $token = $request->query('token');
        $email = $request->query('email');

        if (!Password::tokenExists(User::where('email', $email)->first(), $token))
            return response(['status' => true, 'error' => 'Не верная почта'], 400);

        return response([
            'status' => true,
            'email' => $email
        ]);
    }

    public function changePassword(PasswordRequest $request): Response
    {
        $user = User::where('email', $request->email)->first();
        if ($user == null)
            return response(['status' => false, 'error' => 'Пользователь не найден'], 404);

        $user->update([
            'password' => Hash::make($request->password)
        ]);
        return response(['status' => true]);
    }
}
