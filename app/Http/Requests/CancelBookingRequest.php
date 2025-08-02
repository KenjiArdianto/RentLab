<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CancelBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::id() == $this->route('transaction')->user_id;
    }

    public function rules(): array
    {
        return [
            'id' => ['cancel_allowed'],
        ];
    }
    
    public function withValidator($validator)
    {
        $validator->addExtension('cancel_allowed', function ($attribute, $value, $parameters, $validator) {
            $transaction = $this->route('transaction');
            $allowedStatuses = [1, 2,7];
            return in_array($transaction->transaction_status_id, $allowedStatuses);
        });
    }

    public function messages(): array
    {
        return [
            'id.cancel_allowed' => __('validation.transaction.cancel_denied'),
        ];
    }
}