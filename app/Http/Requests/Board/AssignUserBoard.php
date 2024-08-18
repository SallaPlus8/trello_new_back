<?php

namespace App\Http\Requests\Board;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AssignUserBoard extends FormRequest
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
            'user_id'     => 'required|array',
            'user_id.*'   => ['required', 'exists:users,id', 'distinct'],
            'board_id' => [
                'required',
                'exists:boards,id',
                Rule::unique('board_members')->where(function ($query) {
                    return $query->where('user_id', $this->user_id)
                        ->where('board_id', $this->board_id);
                }),
            ],
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     */
    public function messages(): array
    {
        return [
            'board_id.unique' => 'This user is already a member of the selected board.',
        ];
    }
}
