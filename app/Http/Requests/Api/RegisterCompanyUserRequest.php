<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class RegisterCompanyUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users|max:255',
            'password' => 'required|string|min:8|confirmed',
            'company_id' => 'required|exists:companies,id',
        ];
    }
}
