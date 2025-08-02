<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ __('booking-history.pdf.order_receipt') }} #{{ $payment->external_id }}</title>
    <link rel="stylesheet" href="{{ public_path('booking_history_assets/css/pdf.css') }}">
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="{{ public_path('booking_history_assets/images/RentLab.png') }}" alt="RentLab Logo" class="logo">
            <h2>{{ __('booking-history.pdf.order_note') }}</h2>
        </div>
        
        <table class="info-section">
             <tr>
                <td><strong>{{ __('booking-history.modal.customer_name') }}:</strong> {{ $user?->name }}</td>
                <td><strong>{{ __('booking-history.pdf.vendor_name') }}:</strong> Rent Lab Official</td>
            </tr>
            <tr>
                <td><strong>{{ __('booking-history.pdf.order_number') }}:</strong> #{{ $payment->external_id }}</td>
                <td><strong>{{ __('booking-history.modal.transaction_date') }}:</strong> {{ $payment->created_at?->format('d M Y') }}</td>
            </tr>
            <tr>
                <td><strong>{{ __('booking-history.pdf.payment_status') }}:</strong> <span class="status-paid">{{ $payment->status ?? 'PAID' }}</span></td>
                <td><strong>{{ __('booking-history.pdf.payment_method') }}:</strong> {{ $payment->payment_channel ?? 'N/A' }}</td>
            </tr>
        </table>
        
        <h3>{{ __('booking-history.pdf.order_details') }}</h3>
        
        <table class="items-table">
            <thead>
                <tr>
                    <th class="id-col">ID</th>
                    <th class="product-col">{{ __('booking-history.pdf.product') }}</th>
                    <th class="variant-col">{{ __('booking-history.modal.variant') }}</th>
                    <th class="dates-col">{{ __('booking-history.modal.booking_dates') }}</th>
                    <th class="price-col text-right">{{ __('booking-history.pdf.price') }}</th>
                    <th class="subtotal-col text-right">{{ __('booking-history.pdf.subtotal') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($transactions as $transaction)
                    @php
                        $startDate = \Carbon\Carbon::parse($transaction->start_book_date);
                        $endDate = \Carbon\Carbon::parse($transaction->end_book_date);
                        $duration = $startDate->diffInDays($endDate) + 1;
                        if ($duration < 1) { $duration = 1; }
                        $vehicleDailyPrice = ($duration > 0 && $transaction->vehicle_price > 0) ? ($transaction->vehicle_price / $duration) : 0;
                    @endphp

                    <tr>
                        <td class="id-col v-align-middle" @if ($transaction->driver_price > 0 || $transaction->driver_id) rowspan="2" @endif>
                            #{{ $transaction->id }}
                        </td>
                        <td class="product-col">
                            {{ $transaction->vehicle?->vehicleName?->name }} ({{ $transaction->vehicle?->year }})
                        </td>
                        <td class="variant-col">
                             <small>{{ $transaction->vehicle?->vehicleType?->type }} / {{ $transaction->vehicle?->vehicleTransmission?->transmission }}</small>
                        </td>
                        <td class="dates-col">
                            {{ $startDate->format('d M Y') }} - {{ $endDate->format('d M Y') }}
                        </td>
                        <td class="price-col text-right">
                            Rp{{ number_format($vehicleDailyPrice, 0, ',', '.') }}
                        </td>
                        <td class="subtotal-col text-right">
                            Rp{{ number_format($transaction->vehicle_price, 0, ',', '.') }}
                        </td>
                    </tr>

                    @if ($transaction->driver_price > 0 || $transaction->driver_id)
                        @php
                            $driverFee = $transaction->driver_price > 0 ? $transaction->driver_price : 50000;
                        @endphp
                        <tr class="item-row-driver">
                            <td class="product-col">{{ __('booking-history.pdf.driver_service') }}</td>
                            <td class="variant-col">-</td>
                            <td class="dates-col">-</td>
                            <td class="price-col text-right">
                                Rp{{ number_format($driverFee, 0, ',', '.') }}
                            </td>
                            <td class="subtotal-col text-right">
                                Rp{{ number_format($driverFee, 0, ',', '.') }}
                            </td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>

        <table class="totals-table">
             @php
                $correctTotal = $transactions->sum('price');
             @endphp
             <tr class="grand-total">
                <td class="text-end">{{ __('booking-history.pdf.total_payment') }}</td>
                <td class="text-end" style="padding-left: 15px;">Rp{{ number_format($correctTotal, 0, ',', '.') }}</td>
             </tr>
            <tr>
                <td colspan="2" class="vat-note-end">{{ __('booking-history.pdf.vat_note') }}</td>
            </tr>
        </table>

        <div class="footer">
            <hr>
            PT RentLab Indonesia | Jl. Pakuan No.3, Kabupaten Bogor, Jawa Barat
        </div>
    </div>
</body>
</html>