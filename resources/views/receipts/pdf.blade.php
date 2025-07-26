@php
    $currLang = session()->get('lang', 'en');
    app()->setLocale($currLang);
@endphp

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ __('booking-history.pdf.order_receipt') }} #{{ $transaction->id }}</title>
    <link rel="stylesheet" href="{{ public_path('build/assets/css/pdf.css') }}">
</head>
<body>
    <div class="container">

        <div class="header">
            <img src="{{ public_path('build/assets/images/RentLab.png') }}" alt="RentLab Logo" class="logo">
            <h2>{{ __('booking-history.pdf.order_note') }}</h2>
        </div>

        <table class="info-section">
            <tr>
                <td>
                    <strong>{{ __('booking-history.modal.customer_name') }}:</strong>
                    {{ $transaction->user?->name }}
                </td>
                <td>
                    <strong>{{ __('booking-history.pdf.vendor_name') }}:</strong>
                    Rent Lab Official
                </td>
            </tr>
            <tr>
                <td>
                    <strong>{{ __('booking-history.pdf.order_number') }}:</strong> #{{ $transaction->id }}
                </td>
                <td>
                    <strong>{{ __('booking-history.modal.transaction_date') }}:</strong> {{ $transaction->created_at?->format('d M Y') ?? 'N/A' }}
                </td>
            </tr>
            <tr>
                <td>
                    <strong>{{ __('booking-history.modal.booking_dates') }}:</strong>
                    {{ \Carbon\Carbon::parse($transaction->start_book_date)->format('d M Y') }} - {{ \Carbon\Carbon::parse($transaction->end_book_date)->format('d M Y') }}
                </td>
                <td>
                    <strong>{{ __('booking-history.pdf.payment_method') }}:</strong> Virtual Account
                </td>
            </tr>
        </table>
        
        <h3>{{ __('booking-history.pdf.order_details') }}</h3>
        <table class="items-table">
            <thead>
                <tr>
                    <th>{{ __('booking-history.pdf.product') }}</th>
                    <th>{{ __('booking-history.modal.variant') }}</th>
                    <th class="text-right">{{ __('booking-history.pdf.price') }}</th>
                    <th class="text-right">{{ __('booking-history.pdf.qty') }}</th>
                    <th class="text-right">{{ __('booking-history.pdf.subtotal') }}</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $transaction->vehicle?->vehicleName?->name }} ({{ $transaction->vehicle?->year }})</td>
                    <td>{{ $transaction->vehicle?->vehicleType?->type }} / {{ $transaction->vehicle?->vehicleTransmission?->transmission }}</td>
                    <td class="text-right">Rp{{ number_format($transaction->vehicle_price, 0, ',', '.') }}</td>
                    <td class="text">1</td>
                    <td class="text-right">Rp{{ number_format($transaction->vehicle_price, 0, ',', '.') }}</td>
                </tr>
                @if ($transaction->driver_fee > 0)
                <tr>
                    <td>D{{ __('booking-history.pdf.driver_service') }}</td>
                    <td>-</td>
                    <td class="text-right">Rp{{ number_format($transaction->driver_fee, 0, ',', '.') }}</td>
                    <td class="text">1</td>
                    <td class="text-right">Rp{{ number_format($transaction->driver_fee, 0, ',', '.') }}</td>
                </tr>
                @endif
            </tbody>
        </table>

        <table class="totals-table">
            <tr class="grand-total">
                <td class="text-end">{{ __('booking-history.pdf.total_payment') }}</td>
                <td class="text-end" style="padding-left: 15px;">Rp{{ number_format($transaction->total_price, 0, ',', '.') }}</td>
            </tr>

            <tr>
                <td colspan="2" class="vat-note-end">
                    {{ __('booking-history.pdf.vat_note') }}
                </td>
            </tr>
        </table>

        <div class="footer">
            <hr>
            PT RentLab Indonesia | Jl. Pakuan No.3, Kabupaten Bogor, Jawa Barat
        </div>
    </div>
</body>
</html>