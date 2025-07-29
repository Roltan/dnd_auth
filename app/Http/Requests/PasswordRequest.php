<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'regex:/^.*(?=.{8,})(?=.*[a-zA-Z])(?=.*\d).*$/', 'confirmed'],
            'token' => ['required', 'string']
        ];
    }
}
