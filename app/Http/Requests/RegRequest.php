<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'filled', 'min:1'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'regex:/^.*(?=.{8,})(?=.*[a-zA-Z])(?=.*\d).*$/', 'confirmed'],
//            'rule' => ['accepted']
        ];
    }
}
