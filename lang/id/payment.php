<?php

return [
    // Judul Halaman
    'title' => [
        'checkout' => 'Rincian Pembayaran',
        'success' => 'Pembayaran Berhasil',
        'pending' => 'Menunggu Pembayaran',
        'failed' => 'Pembayaran Gagal',
        'countdown' => 'Selesaikan Pembayaran Dalam',
    ],

    // Ringkasan Pesanan
    'summary' => [
        'heading' => 'Ringkasan Pesanan',
        'subtotal' => 'Subtotal',
        'service_fee' => 'Biaya Layanan Xendit',
        'total' => 'Total Pembayaran',
    ],

    // Metode Pembayaran (spesifik Xendit)
    'method' => [
        'heading' => 'Pilih Metode Pembayaran',
        'va' => 'Virtual Account',
        'qris' => 'QRIS (GPay, OVO, Dana, dll)',
        'card' => 'Kartu Kredit / Debit',
        'ewallet' => 'E-Wallet',
        'retail' => 'Gerai Ritel (Alfamart, dll)',
    ],

    // Instruksi & Status
    'instructions' => [
        'pay_before' => 'Harap selesaikan pembayaran sebelum',
        'scan_qr' => 'Pindai kode QR di bawah ini menggunakan aplikasi perbankan atau e-wallet Anda.',
        'transfer_to_va' => 'Silakan transfer ke nomor Virtual Account berikut:',
        'va_number' => 'Nomor Virtual Account',
    ],

    // Pesan Status
    'status' => [
        'success_message' => 'Terima kasih! Pembayaran Anda telah kami terima dan pesanan Anda sedang diproses.',
        'pending_message' => 'Pesanan Anda telah dibuat. Silakan selesaikan pembayaran untuk melanjutkan.',
        'failed_message' => 'Maaf, pembayaran Anda tidak dapat diproses. Silakan coba lagi atau gunakan metode pembayaran lain.',
        'expired_message' => 'Waktu pembayaran untuk pesanan ini telah berakhir.',
    ],

    // Teks Tombol
    'buttons' => [
        'pay_now' => 'Lanjutkan ke Pembayaran',
        'back_to_home' => 'Kembali ke Beranda',
        'try_again' => 'Coba Lagi',
        'check_status' => 'Cek Status Pembayaran',
    ],
];
