<?php

namespace App\Http\Requests\Board;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBoardRequest extends FormRequest
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
           'name' => "required|string|min:3|unique:boards,name,$this->board_id",
           'board_id' => "required|exists:boards,id",
        ];
    }
}
