<?php

namespace App\Http\Requests\Card;

use Illuminate\Foundation\Http\FormRequest;

class AddCardRequest extends FormRequest
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
            'the_list_id' => 'required|exists:the_lists,id',
            'text' => 'required|string',
            'description' => 'nullable|string',
            'start_time' => 'nullable|string',
            'end_time' => 'nullable|string',
            'photo' => 'nullable', // Validation rule for the photo
            'color' => 'nullable', // Validation rule for the photo
            'position' => 'nullable|integer|min:1', // Position validation
            'completed' => 'nullable|boolean', // New rule for the 'completed' field

        ];
    }
}
