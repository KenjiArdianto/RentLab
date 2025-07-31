<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminUserUnsuspendSelectedRequest extends FormRequest
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
            'selected' => ['required', 'array', 'min:1'],
            'selected.*' => ['required', 'integer', 'exists:users,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'selected.required' => 'You must select at least one user to unsuspend.',
            'selected.array' => 'The selected users must be in array format.',
            'selected.min' => 'Please select at least one user.',
            'selected.*.required' => 'Each user ID is required.',
            'selected.*.integer' => 'Each selected value must be a valid user ID.',
            'selected.*.exists' => 'One or more selected users do not exist.',
        ];
    }
}
