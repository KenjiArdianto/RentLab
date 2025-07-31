<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class UserDetailsRequest extends FormRequest
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
        \activity('user_detail_validation_failed')
            ->causedBy(Auth::user())
            ->withProperties([
                'ip' => $this->ip(),
                'user_id' => optional($this->user())->id,
                'user_input' => $this->except(['idcardPicture']),
                'errors' => $validator->errors()->messages(),
                'user_agent' => $this->userAgent(),
            ])
            ->log('Validation failed when completing user details');

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
            'fname' => ['required', 'regex:/^[A-Za-z]+$/', 'max:255'],
            'lname' => ['required', 'regex:/^[A-Za-z]+$/', 'max:255'],
            'phoneNumber' => ['required', 'digits_between:8,13'],
            'idcardNumber' => ['required', 'string', 'max:50'],
            'dateOfBirth' => ['required', 'date'],
            'idcardPicture' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:10240'], // 10MB in kilobytes
        ];
    }

    public function messages(): array
    {
        return [
            'fname.required' => 'First name is required.',
            'fname.regex' => 'First name must contain only letters.',
            'lname.regex' => 'Last name must contain only letters.',
            'phoneNumber.required' => 'Phone number is required.',
            'dateOfBirth.required'=>'Date Of Birth can not be empty',
            'dateOfBirth.date'=>'Date Of Birth must in form of date',
            'phoneNumber.digits_between' => 'Phone number must be between 8 and 13 digits.',
            'idcardPicture.max' => 'ID card picture must not be larger than 10MB.',
        ];
    }
}
