<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminVehicleUpdateRequest extends FormRequest
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
            'vehicle_name_id' => 'required|exists:vehicle_names,id',
            'vehicle_type_id' => 'required|exists:vehicle_types,id',
            'vehicle_transmission_id' => 'required|exists:vehicle_transmissions,id',
            'engine_cc' => 'required|numeric|min:50',
            'seats' => 'required|integer|min:1|max:50',
            'year' => 'required|integer|min:1900|max:' . date('Y'),
            'price' => 'required|numeric|min:0',
            'location_id' => 'required|exists:locations,id',

            'main_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'image1' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'image2' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'image3' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'image4' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',

            'image1_id' => 'nullable|exists:vehicle_images,id',
            'image2_id' => 'nullable|exists:vehicle_images,id',
            'image3_id' => 'nullable|exists:vehicle_images,id',
            'image4_id' => 'nullable|exists:vehicle_images,id',
        ];
    }

    public function messages(): array
    {
        return [
            // vehicle_name_id
            'vehicle_name_id.required' => 'Vehicle name is required.',
            'vehicle_name_id.exists' => 'Selected vehicle name does not exist.',

            // vehicle_type_id
            'vehicle_type_id.required' => 'Vehicle type is required.',
            'vehicle_type_id.exists' => 'Selected vehicle type does not exist.',

            // vehicle_transmission_id
            'vehicle_transmission_id.required' => 'Transmission type is required.',
            'vehicle_transmission_id.exists' => 'Selected transmission type does not exist.',

            // engine_cc
            'engine_cc.required' => 'Engine capacity (CC) is required.',
            'engine_cc.numeric' => 'Engine capacity must be a number.',
            'engine_cc.min' => 'Engine capacity must be at least 50 cc.',

            // seats
            'seats.required' => 'Seat count is required.',
            'seats.integer' => 'Seat count must be an integer.',
            'seats.min' => 'There must be at least 1 seat.',
            'seats.max' => 'Seat count must not exceed 20.',

            // year
            'year.required' => 'Year is required.',
            'year.integer' => 'Year must be a number.',
            'year.min' => 'Year must be at least 1900.',
            'year.max' => 'Year must not exceed ' . date('Y'),
            
            // price
            'price.required' => 'Price is required.',
            'price.numeric' => 'Price must be a number.',
            'price.min' => 'Price must be at least 0.',

            // location_id
            'location_id.required' => 'Location is required.',
            'location_id.exists' => 'Selected location does not exist.',

            // main image
            'main_image.image' => 'Main image must be an image file.',
            'main_image.mimes' => 'Main image must be a JPEG, PNG, JPG, or WEBP.',
            'main_image.max' => 'Main image must not exceed 2MB.',

            // image1
            'image1.image' => 'Image 1 must be an image file.',
            'image1.mimes' => 'Image 1 must be a JPEG, PNG, JPG, or WEBP.',
            'image1.max' => 'Image 1 must not exceed 2MB.',

            // image2
            'image2.image' => 'Image 2 must be an image file.',
            'image2.mimes' => 'Image 2 must be a JPEG, PNG, JPG, or WEBP.',
            'image2.max' => 'Image 2 must not exceed 2MB.',

            // image3
            'image3.image' => 'Image 3 must be an image file.',
            'image3.mimes' => 'Image 3 must be a JPEG, PNG, JPG, or WEBP.',
            'image3.max' => 'Image 3 must not exceed 2MB.',

            // image4
            'image4.image' => 'Image 4 must be an image file.',
            'image4.mimes' => 'Image 4 must be a JPEG, PNG, JPG, or WEBP.',
            'image4.max' => 'Image 4 must not exceed 2MB.',

            // image id existence
            'image1_id.exists' => 'Image 1 reference is invalid.',
            'image2_id.exists' => 'Image 2 reference is invalid.',
            'image3_id.exists' => 'Image 3 reference is invalid.',
            'image4_id.exists' => 'Image 4 reference is invalid.',
        ];
    }

}
