<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;

class VehicleFilterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * Menentukan apakah pengguna diizinkan untuk membuat request ini.
     * Untuk filter publik, ini seharusnya selalu true.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * Mendapatkan aturan validasi yang berlaku untuk request ini.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // Filter utama
            'Tipe_Kendaraan'  => [
                'nullable',
                'string',
                Rule::in(['Car', 'Motor']), // Hanya izinkan nilai 'Car' atau 'Motor'
            ],
            'Jenis_Kendaraan' => ['nullable', 'array'],
            'Jenis_Transmisi' => ['nullable', 'array'],
            'Tempat'          => ['nullable', 'array'],

            // Filter harga
            'min_price'       => ['nullable', 'numeric', 'min:0'],
            'max_price'       => ['nullable', 'numeric', 'gte:min_price'], // max_price harus lebih besar atau sama dengan min_price

            // Filter tanggal
            'start_date'      => ['nullable', 'required_with:end_date', 'date', 'after_or_equal:today', 'before:end_date'],
            'end_date'        => ['nullable','required_with:start_date', 'date', 'after:start_date'], // end_date harus setelah atau sama dengan start_date

            // Filter pencarian
            'search'          => ['nullable', 'string', 'max:100'], // Batasi panjang string pencarian
        ];
    }

     /**
     * Handle a failed validation attempt.
     */
    protected function failedValidation(Validator $validator)
    {
        \activity('vehicle_filter_validation_failed')
            ->causedBy(Auth::user())
            ->withProperties([
                'ip' => $this->ip(),
                'user_id' => optional($this->user())->id,
                'user_input' => $this->except([]),
                'errors' => $validator->errors()->messages(),
                'user_agent' => $this->userAgent(),
            ])
            ->log('Validation failed when filtering vehicles');

        throw (new ValidationException($validator))
            ->errorBag($this->errorBag)
            ->redirectTo($this->getRedirectUrl());
    }
}
