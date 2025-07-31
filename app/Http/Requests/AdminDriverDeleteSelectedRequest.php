<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminDriverDeleteSelectedRequest extends FormRequest
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
            'selected'   => ['required', 'array', 'min:1'],
            'selected.*' => ['integer', 'exists:drivers,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'selected.required' => 'Please select at least one driver.',
            'selected.array'    => 'Invalid format for selection.',
            'selected.min'      => 'Please select at least one driver.',
            'selected.*.integer'=> 'Each selected item must be a valid ID.',
            'selected.*.exists' => 'One or more selected drivers do not exist.',
        ];
    }

}
