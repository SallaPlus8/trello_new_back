<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AsssinUserWorkspace extends FormRequest
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
            'workspace_id'  => 'required|exists:workspaces,id',
            'user_id'       => 'required|array',
            'user_id.*'     => ['required','exists:users,id','distinct','unique:workspace_members,id'],
        ];
    }
}
