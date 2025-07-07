<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Order Receipt #{{ $transaction->id }}</title>
    {{-- Memanggil file CSS dari folder public, ini cara yang paling stabil untuk domPDF --}}
    <link rel="stylesheet" href="{{ public_path('build/assets/css/pdf.css') }}">
</head>
<body>
    <div class="container">

        <div class="header">
            <img src="{{ public_path('build/assets/images/RentLab.png') }}" alt="RentLab Logo" class="logo">
            <h2>Order Note</h2>
        </div>

        <table class="info-section">
            <tr>
                <td>
                    <strong>Customer Name:</strong>
                    {{ $transaction->user?->name }}
                </td>
                <td>
                    <strong>Vendor Name:</strong>
                    Rent Lab Official
                </td>
            </tr>
            <tr>
                <td>
                    <strong>Order Number:</strong> #{{ $transaction->id }}
                </td>
                <td>
                    <strong>Transaction Date:</strong> {{ $transaction->created_at?->format('d M Y') ?? 'N/A' }}
                </td>
            </tr>
            <tr>
                <td>
                    <strong>Booking Dates:</strong>
                    {{ \Carbon\Carbon::parse($transaction->start_book_date)->format('d M Y') }} - {{ \Carbon\Carbon::parse($transaction->end_book_date)->format('d M Y') }}
                </td>
                <td>
                    <strong>Payment Method:</strong> Virtual Account
                </td>
            </tr>
        </table>
        
        <h3>Order Details</h3>
        <table class="items-table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Variant</th>
                    <th class="text-right">Price</th>
                    <th class="text-right">Qty</th>
                    <th class="text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $transaction->vehicle?->vehicleName?->name }}</td>
                    <td>{{ $transaction->vehicle?->vehicleType?->type }} / {{ $transaction->vehicle?->vehicleTransmission?->transmission }}</td>
                    <td class="text-right">Rp{{ number_format($transaction->vehicle_price, 0, ',', '.') }}</td>
                    <td class="text-right">1</td>
                    <td class="text-right">Rp{{ number_format($transaction->vehicle_price, 0, ',', '.') }}</td>
                </tr>
                @if ($transaction->driver_fee > 0)
                <tr>
                    <td>Driver Service Fee</td>
                    <td>-</td>
                    <td class="text-right">Rp{{ number_format($transaction->driver_fee, 0, ',', '.') }}</td>
                    <td class="text-right">1</td>
                    <td class="text-right">Rp{{ number_format($transaction->driver_fee, 0, ',', '.') }}</td>
                </tr>
                @endif
            </tbody>
        </table>

        <table class="totals-table">
            <tr class="grand-total">
                <td>Total Payment</td>
                <td class="text-right">Rp{{ number_format($transaction->total_price, 0, ',', '.') }}</td>
            </tr>

            <tr>
                <td colspan="2" class="vat-note">
                    All fees charged by RentLab (if any) are inclusive of VAT.
                </td>
            </tr>
        </table>

        <div class="footer">
            <hr>
            PT RentLab Indonesia | Jalan Teknologi No. 1, Jakarta, 12345 | NPWP: 12.345.678.9-012.000
        </div>
    </div>
</body>
</html>