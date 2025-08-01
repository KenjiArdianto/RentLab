<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class ProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check() && Auth::user()->role!='admin';
    }
    // protected function failedValidation(Validator $validator)
    // {
    //     \activity('profile_validation_failed')
    //         ->causedBy(Auth::user())
    //         ->performedOn(Auth::user()->detail)
    //         ->withProperties([
    //             'ip' => $this->ip(),
    //             'user_id' => optional($this->user())->id,
    //             'user_input' => $this->except(['idcardPicture', 'profilePicture']),
    //             'errors' => $validator->errors()->messages(),
    //             'user_agent' => $this->userAgent(),
    //         ])
    //         ->log('Validation failed when updating profile');

    //     throw (new ValidationException($validator))
    //         ->errorBag($this->errorBag)
    //         ->redirectTo($this->getRedirectUrl());
    // }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required','min:3','max:15','regex:/^(?=.*[A-Za-z])[A-Za-z0-9._-]{3,15}$/'],
            'fname' => ['required', 'regex:/^[A-Za-z]+$/', 'max:255'],
            'lname' => ['nullable', 'regex:/^[A-Za-z]+$/', 'max:255'],
            'phoneNumber' => ['required', 'digits_between:8,13'],
            'idCardNumber' => ['required', 'string', 'max:50'],
            'dateOfBirth' => ['required', 'date'],
            'idcardPicture' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:10240'], // 10MB
            'profilePicture' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:10240'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Please enter your full name.',
            'name.regex' => 'Name must be 3-15 characters, can include alphanumeric, dots, underscores, hyphens',
            'fname.required' => 'fname must not be empty',
            'fname.regex' => 'First name must contain only letters and atleast 1 character',
            'lname.regex' => 'Last name must contain only letters.',
            'phoneNumber.digits_between' => 'Phone number must be between 8 and 13 digits.',
            'idcardPicture.max' => 'ID card picture must not be larger than 10MB.',
            'profilePicture.max' => 'Profile picture must not be larger than 10MB.',
            'dateOfBirth.required'=>'Date Of Birth can not be empty',
            'dateOfBirth.date'=>'Date Of Birth must in form of date',
        ];
    }
}
