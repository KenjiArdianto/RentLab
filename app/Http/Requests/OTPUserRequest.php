<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class OTPUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check() && Auth::user()->role!='admin';
    }
    protected function failedValidation(Validator $validator)
    {
        \activity('otp_validation_failed')
            ->withProperties([
                'ip' => $this->ip(),
                'user_input' => $this->only(array_keys($validator->errors()->messages())),
                'errors' => $validator->errors()->messages(),
                'user_agent' => $this->userAgent(),
            ])
            ->log('OTP input failed validation');

        throw (new ValidationException($validator))
            ->errorBag($this->errorBag)
            ->redirectTo($this->getRedirectUrl());
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'otp' => ['required', 'digits:6'], // exactly 6 digits, numeric only
        ];
    }
    public function messages(): array
    {
        return [
            'otp.required' => 'OTP is required.',
            'otp.digits' => 'OTP must be exactly 6 digits.',
        ];
    }
}
