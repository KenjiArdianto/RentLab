<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;

class StoreCartRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // pastikan diatur true agar request bisa digunakan
    }

    public function rules(): array
    {
        return [
            'vehicle_id' => 'required|exists:vehicles,id',
            'date_ranges' => 'required|array|min:1',
            'date_ranges.*.start_date' => 'required|date|before_or_equal:date_ranges.*.end_date',
            'date_ranges.*.end_date' => 'required|date|after_or_equal:date_ranges.*.start_date',
        ];
    }

    public function messages(): array
    {
        return [
            'vehicle_id.required' => 'Kendaraan wajib dipilih.',
            'vehicle_id.exists' => 'Kendaraan tidak valid.',
            'date_ranges.required' => 'Tanggal wajib diisi.',
            'date_ranges.*.start_date.required' => 'Tanggal mulai wajib diisi.',
            'date_ranges.*.end_date.required' => 'Tanggal akhir wajib diisi.',
        ];
    }

    /**
     * Handle a failed validation attempt.
     */
    protected function failedValidation(Validator $validator)
    {
        \activity('store_cart_validation_failed')
            ->causedBy(Auth::user())
            ->withProperties([
                'ip' => $this->ip(),
                'user_id' => optional($this->user())->id,
                'user_input' => $this->except([]),
                'errors' => $validator->errors()->messages(),
                'user_agent' => $this->userAgent(),
            ])
            ->log('Validation failed while trying to store cart');

        throw (new ValidationException($validator))
            ->errorBag($this->errorBag)
            ->redirectTo($this->getRedirectUrl());
    }
}
