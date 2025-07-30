<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class LoginUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::guest();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'email', 'regex:/^[a-zA-Z0-9._%+-]+@gmail\.com$/'],
            'password' => ['required', 'string', 'min:8', 'max:25'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.regex' => 'Email must end with @gmail.com',
            'password.min' => 'Password must be at least 8 characters',
            'password.max' => 'Password may not be greater than 25 characters',
        ];
    }
}
