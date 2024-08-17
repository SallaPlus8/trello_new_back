<?php

namespace App\Http\Requests\Label;

use Illuminate\Foundation\Http\FormRequest;

class StoreLabelRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'card_id' => 'required|exists:cards,id',
            'hex_color' => 'required|string|max:7', // Assuming hex color is a 7-character string including #
            'title' => 'required|string|max:255'
        ];
    }
}
