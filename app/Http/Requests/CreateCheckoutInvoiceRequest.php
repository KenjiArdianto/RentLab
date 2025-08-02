<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;

class CreateCheckoutInvoiceRequest extends FormRequest
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
            'cart_ids'   => 'required|array|min:1',
            'cart_ids.*' => 'integer|exists:carts,id',
        ];
    }
    protected function failedValidation(Validator $validator)
    {
        \activity('checkout_validation_failed')
            ->causedBy(Auth::user())
            ->withProperties([
                'ip' => $this->ip(),
                'user_id' => optional($this->user())->id,
                'user_input' => $this->except([]), // You can exclude fields if needed
                'errors' => $validator->errors()->messages(),
                'user_agent' => $this->userAgent(),
            ])
            ->log('Validation failed when creating checkout invoice');

        throw (new ValidationException($validator))
            ->errorBag($this->errorBag)
            ->redirectTo($this->getRedirectUrl());
    }
}
