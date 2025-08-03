<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminTransactionUpdateRequest extends FormRequest
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
            'status' => ['required', 'integer', 'in:1,2,3,4,5,6,7'], // adjust allowed status values
            'comment' => ['nullable', 'string', 'max:250'],
            'rating' => ['nullable', 'integer', 'between:1,5'],
        ];
    }

    public function messages(): array
    {
        return [
            'status.required' => 'Status is required.',
            'status.integer' => 'Status must be a number.',
            'status.in' => 'Status value is invalid.',
            'comment.string' => 'Comment must be a string.',
            'comment.max' => 'Comment must not exceed 250 characters.',
            'rating.integer' => 'Rating must be a number.',
            'rating.between' => 'Rating must be between 1 and 5.',
        ];
    }


}
