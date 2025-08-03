<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminUserReviewRequest extends FormRequest
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
            'comment' => ['required', 'string', 'max:250'],
            'rate' => ['required', 'integer', 'between:1,5'],
        ];
    }

    public function messages(): array
    {
        return [
            'comment.required' => 'The comment field is required.',
            'comment.string' => 'The comment must be a string.',
            'comment.max' => 'The comment must not exceed 250 characters.',
            'rate.required' => 'The rate field is required.',
            'rate.integer' => 'The rate must be an integer.',
            'rate.between' => 'The rate must be between 1 and 5.',
        ];
    }


}
