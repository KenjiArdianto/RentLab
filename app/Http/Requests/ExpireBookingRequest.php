<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ExpireBookingRequest extends FormRequest
{
    /**
     * @return bool
     */
    public function authorize(): bool
    {
        $transaction = $this->route('transaction');
        return Auth::id() === $transaction->user_id && in_array($transaction->transaction_status_id, [1, 7]);
    }

    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [];
    }
}