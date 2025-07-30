<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminDriverEditSelectedRequest extends FormRequest
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
        $rules = [
            'action_type' => ['required', 'string', 'regex:/^(edit|delete)_\d+$/'],
        ];

        if ($this->isMethod('post') && str_starts_with($this->input('action_type'), 'edit')) {
            $rules = array_merge($rules, [  
                'name' => ['nullable', 'string', 'max:255'],
                'location_id' => ['nullable', 'exists:locations,id'],
                'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            ]);
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'action_type.required' => 'Action type is required.',
            'action_type.regex' => 'Action type format is invalid.',
            'location_id.exists' => 'Selected location does not exist.',
            'image.image' => 'The file must be an image.',
            'image.mimes' => 'Image must be in JPG, JPEG, or PNG format.',
            'image.max' => 'Image size may not exceed 2MB.',
        ];
    }
}
