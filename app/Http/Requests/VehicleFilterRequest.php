<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'start_date'      => ['nullable', 'date'],
            'end_date'        => ['nullable', 'date', 'after_or_equal:start_date'], // end_date harus setelah atau sama dengan start_date

            // Filter pencarian
            'search'          => ['nullable', 'string', 'max:100'], // Batasi panjang string pencarian
        ];
    }
}
