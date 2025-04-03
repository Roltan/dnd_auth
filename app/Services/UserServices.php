<?php

namespace App\Services;

use App\Http\Requests\EditRequest;
use App\Models\User;
use Illuminate\Http\Response;

class UserServices
{
    public function info(): Response
    {
        dd(auth('sanctum')->user());
        return response(['status' => true, 'user' => auth()->user()]);
    }

    public function edit(EditRequest $request): Response
    {
        $data = $request->only(['name', 'email']);
        $user = User::find($request->id);
        if ($user == null)
            return response(['status' => false, 'error' => 'Пользователь не найден'], 404);

        $user->update($data);
        return response(['status' => true]);
    }
}
