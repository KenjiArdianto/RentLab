<?php

namespace App\Http\Requests;
use Illuminate\Support\Facades\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Auth\Access\AuthorizationException;


class RegisterUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::guest();
    }

    public function failedAuthorization()
    {
        throw new AuthorizationException('You are already logged in.');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
     public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'min:3',
                'max:15',
                'regex:/^(?=.*[A-Za-z])[A-Za-z0-9._-]{3,15}$/'
            ],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'regex:/^[\w.+-]+@gmail\.com$/i' // Must be Gmail
            ],
            'password' => [
                'required',
                'string',
                'min:8',
                'max:25',
                'regex:/^[\x21-\x7E]{8,25}$/', // ASCII 33–126
                'confirmed'
            ],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Please enter your full name.',
            'name.regex' => 'Name must be 3-15 characters, can include alphanumeric, dots, underscores, hyphens',
            'email.required' => 'We need your email address.',
            'email.email' => 'That is not a valid email address.',
            'password.required' => 'Don’t forget your password!',
            'password.min' => 'Password must be at least 8 characters.',
            'password.max' => 'Password must not exceed 25 characters.',
            'password.regex' => 'Password must contain only ASCII characters.',
            'password.confirmed' => 'Password confirmation does not match.',
            'email.regex' => 'Only @gmail.com addresses are accepted.',
        ];
    }
}
