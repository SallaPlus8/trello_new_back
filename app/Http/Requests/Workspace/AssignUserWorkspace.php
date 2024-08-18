<?php

namespace App\Http\Requests\Workspace;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AssignUserWorkspace extends FormRequest
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
    // public function rules(): array
    // {
    //     return [
    //         'workspace_id'  => 'required|exists:workspaces,id',
    //         'user_id'       => 'required|array',
    //         'user_id.*'     => ['required','exists:users,id','distinct'],
    //     ];
    // }
    public function rules(): array
    {
        return [
            'user_id'     => 'required|array',
            'user_id.*'   => ['required', 'exists:users,id', 'distinct'],
            'workspace_id' => [
                'required',
                'exists:workspaces,id',
                Rule::unique('workspace_members')->where(function ($query) {
                    return $query->where('user_id', $this->user_id)
                        ->where('workspace_id', $this->workspace_id);
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
            'workspace_id.unique' => 'This user is already a member of the selected workspace.',
        ];
    }
}
