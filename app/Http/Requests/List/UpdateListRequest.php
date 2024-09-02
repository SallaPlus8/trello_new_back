<?php

namespace App\Http\Requests\List;

use Illuminate\Foundation\Http\FormRequest;

class UpdateListRequest extends FormRequest
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
            "title"     => "required",
            "list_id"  => "required|exists:the_lists,id",
            "board_id"  => "required|exists:boards,id",
        ];
    }
}
