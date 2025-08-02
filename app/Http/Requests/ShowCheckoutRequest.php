<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;

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

    /**
     * Handle a failed validation attempt.
     */
    protected function failedValidation(Validator $validator)
    {
        \activity('show_checkout_validation_failed')
            ->causedBy(Auth::user())
            ->withProperties([
                'method' => $this->method(),
                'ip' => $this->ip(),
                'user_id' => optional($this->user())->id,
                'user_input' => $this->except([]), // Exclude fields if needed
                'errors' => $validator->errors()->messages(),
                'user_agent' => $this->userAgent(),
            ])
            ->log('Validation failed during show checkout request');

        throw (new ValidationException($validator))
            ->errorBag($this->errorBag)
            ->redirectTo($this->getRedirectUrl());
    }
}
