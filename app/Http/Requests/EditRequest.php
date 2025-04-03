<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EditRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id' => ['required', 'numeric', 'min:1'],
            'name' => ['required', 'string', 'filled', 'min:1'],
            'email' => ['required', 'email', 'unique:App\Models\User,email'],
        ];
    }
}
