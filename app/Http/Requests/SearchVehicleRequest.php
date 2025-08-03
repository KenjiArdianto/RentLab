<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SearchVehicleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'vehicle_type'    => 'required|string|in:motorcycle,car',
            'start_book_date' => 'required|date|after_or_equal:today',
            'end_book_date'   => 'required|date|after_or_equal:start_book_date',
        ];
    }

    public function attributes(): array
    {
        return [
            'vehicle_type'    => __('validation.attributes.vehicle_type'),
            'start_book_date' => __('validation.attributes.start_book_date'),
            'end_book_date'   => __('validation.attributes.end_book_date'),
        ];
    }
}
