<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminVehicleDeleteCategoryRequest extends FormRequest
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
            'category_id' => ['required', 'exists:vehicle_categories,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'category_id.required' => 'Category ID is required for deletion.',
            'category_id.exists' => 'The selected category does not exist.',
        ];
    }
}
