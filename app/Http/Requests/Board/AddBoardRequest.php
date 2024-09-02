<?php

namespace App\Http\Requests\Board;

use Illuminate\Foundation\Http\FormRequest;

class AddBoardRequest extends FormRequest
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
            'name'          => "required|string",
            'workspace_id'  => "required|exists:workspaces,id",
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp', // Validation rule for the photo
        ];
    }
}
