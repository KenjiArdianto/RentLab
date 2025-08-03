<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

namespace App\Http\Requests;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;

use Illuminate\Foundation\Http\FormRequest;

class AdminVehicleImportRequest extends FormRequest
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
            'csv_file' => 'required|file|mimes:csv,txt',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'csv_file.required' => 'Please upload a CSV file.',
            'csv_file.file'     => 'The uploaded file must be a valid file.',
            'csv_file.mimes'    => 'The file must be a CSV.',
        ];
    }
    protected function failedValidation(Validator $validator): void
    {
        activity('admin_vehicle_import_failed_validation')
            ->causedBy(Auth::user())
            ->withProperties([
                'ip' => $this->ip(),
                'errors' => $validator->errors()->all(),
                'user_agent' => $this->userAgent(),
            ])
            ->log('Admin failed to upload vehicle import CSV due to validation error.');

        throw new HttpResponseException(
            redirect()->back()->withErrors($validator)->withInput()
        );
    }
}

