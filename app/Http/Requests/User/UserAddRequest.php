<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UserAddRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'          => "required|min:3",
            'email'         => "required|unique:users,email|email",
            'password'      => 'required|min:6',
            'super_admin'      => 'nullable|boolean',
            // 'photo'      => 'required|image|mimes:jpeg,jpg,png,gif',
        ];
    }
}
