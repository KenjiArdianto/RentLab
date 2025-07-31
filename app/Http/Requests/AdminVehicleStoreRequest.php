<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminVehicleStoreRequest extends FormRequest
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
            'vehicle_name_id' => ['required', 'exists:vehicle_names,id'],
            'vehicle_type_id' => ['required', 'exists:vehicle_types,id'],
            'vehicle_transmission_id' => ['required', 'exists:vehicle_transmissions,id'],
            'engine_cc' => ['required', 'integer', 'min:50', 'max:10000'],
            'seats' => ['required', 'integer', 'min:1', 'max:50'],
            'price' => ['required', 'numeric', 'min:0'],
            'location_id' => ['required', 'exists:locations,id'],

            // Main image is optional but must be an image file if present
            'main_image' => ['nullable', 'image', 'max:2048'],

            // Additional images
            'image1' => ['nullable', 'image', 'max:2048'],
            'image2' => ['nullable', 'image', 'max:2048'],
            'image3' => ['nullable', 'image', 'max:2048'],
            'image4' => ['nullable', 'image', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'vehicle_name_id.required' => 'Vehicle name is required.',
            'vehicle_name_id.exists' => 'Selected vehicle name does not exist.',
            
            'vehicle_type_id.required' => 'Vehicle type is required.',
            'vehicle_type_id.exists' => 'Selected vehicle type does not exist.',

            'vehicle_transmission_id.required' => 'Transmission type is required.',
            'vehicle_transmission_id.exists' => 'Selected transmission type is invalid.',

            'engine_cc.required' => 'Engine capacity is required.',
            'engine_cc.integer' => 'Engine capacity must be a number.',
            'engine_cc.min' => 'Engine capacity must be at least 50 cc.',
            'engine_cc.max' => 'Engine capacity must not exceed 10,000 cc.',

            'seats.required' => 'Seat count is required.',
            'seats.integer' => 'Seat count must be a number.',
            'seats.min' => 'There must be at least 1 seat.',
            'seats.max' => 'Seat count must not exceed 50.',

            'price.required' => 'Price is required.',
            'price.numeric' => 'Price must be a number.',
            'price.min' => 'Price must be at least 0.',

            'location_id.required' => 'Location is required.',
            'location_id.exists' => 'Selected location is invalid.',

            'main_image.image' => 'Main image must be a valid image file.',
            'main_image.max' => 'Main image size must not exceed 2MB.',

            'image1.image' => 'Image 1 must be a valid image file.',
            'image1.max' => 'Image 1 size must not exceed 2MB.',

            'image2.image' => 'Image 2 must be a valid image file.',
            'image2.max' => 'Image 2 size must not exceed 2MB.',

            'image3.image' => 'Image 3 must be a valid image file.',
            'image3.max' => 'Image 3 size must not exceed 2MB.',

            'image4.image' => 'Image 4 must be a valid image file.',
            'image4.max' => 'Image 4 size must not exceed 2MB.',
        ];
    }


}
