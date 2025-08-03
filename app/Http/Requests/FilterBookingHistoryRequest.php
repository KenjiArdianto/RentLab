<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FilterBookingHistoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'history_search' => 'nullable|string|max:100',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after_or_equal:date_from',
            'ongoingPage' => 'nullable|integer',
            'historyPage' => 'nullable|integer',
            'active_tab' => 'nullable|string|in:ongoing,history',
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'search.string' => __('validation.search.string'),
            'search.max' => __('validation.search.max'),
            'date_from.date' => __('validation.date_from.date'),
            'date_to.date' => __('validation.date_to.date'),
            'date_to.after_or_equal' => __('validation.date_to.after_or_equal'),
        ];
    }
}