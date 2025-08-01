<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ShowCheckoutRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Izinkan semua orang untuk mengakses, otorisasi akan ditangani di controller
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // HANYA terapkan aturan validasi jika metodenya adalah POST
        if ($this->isMethod('post')) {
            return [
                'cart_ids'   => ['required', 'array', 'min:1'],
                'cart_ids.*' => ['integer', 'exists:carts,id'],
            ];
        }

        // Jika metodenya GET (misal: refresh), tidak perlu aturan validasi
        return [];
    }
    
    public function messages(){
        return [
            'cart_ids.required'=>'Please select minimum 1 cart items',
        ];
    }
}
