<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminVehicleUpdateCategoryRequest extends FormRequest
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
            'category' => ['required', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'category_id.required' => 'Category ID is required.',
            'category_id.exists' => 'Selected category does not exist in the system.',

            'category.required' => 'Category name is required.',
            'category.string' => 'Category must be a valid text.',
            'category.max' => 'Category name may not be greater than 255 characters.',
        ];
    }
}
