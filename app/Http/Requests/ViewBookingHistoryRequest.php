<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ViewBookingHistoryRequest extends FormRequest
{
    /**     * @return bool
     */
    public function authorize(): bool
    {
        $transaction = $this->route('transaction');
        return Auth::id() == $transaction->user_id;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [];
    }
}