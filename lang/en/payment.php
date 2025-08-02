<?php

return [
    // Page Titles
    'title' => [
        'checkout' => 'Payment Details',
        'success' => 'Payment Successful',
        'pending' => 'Waiting for Payment',
        'failed' => 'Payment Failed',
        'countdown' => 'Complete Payment Within',

    ],

    // Order Summary
    'summary' => [
        'heading' => 'Order Summary',
        'subtotal' => 'Subtotal',
        'service_fee' => 'Xendit Service Fee',
        'total' => 'Total Payment',
    ],

    // Payment Methods (Xendit specific)
    'method' => [
        'heading' => 'Select Payment Method',
        'va' => 'Virtual Account',
        'qris' => 'QRIS (GPay, OVO, Dana, etc.)',
        'card' => 'Credit / Debit Card',
        'ewallet' => 'E-Wallet',
        'retail' => 'Retail Outlet (Alfamart, etc.)',
    ],

    // Instructions & Status
    'instructions' => [
        'pay_before' => 'Please complete your payment before',
        'scan_qr' => 'Scan the QR code below using your banking or e-wallet application.',
        'transfer_to_va' => 'Please transfer to the following Virtual Account number:',
        'va_number' => 'Virtual Account Number',
    ],

    // Status Messages
    'status' => [
        'success_message' => 'Thank you! We have received your payment and your order is being processed.',
        'pending_message' => 'Your order has been created. Please complete the payment to proceed.',
        'failed_message' => 'Sorry, your payment could not be processed. Please try again or use another payment method.',
        'expired_message' => 'The payment window for this order has expired.',
    ],

    // Button Texts
    'buttons' => [
        'pay_now' => 'Proceed to Payment',
        'back_to_home' => 'Back to Home',
        'try_again' => 'Try Again',
        'check_status' => 'Check Payment Status',
    ],
];
