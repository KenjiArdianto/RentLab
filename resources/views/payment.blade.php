<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Pembayaran</title>
    <style>
        body { font-family: sans-serif; display: flex; justify-content: center; align-items: center; min-height: 100vh; background-color: #f0f0f0; }
        .container { background-color: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); text-align: center; }
        button { padding: 10px 20px; font-size: 16px; cursor: pointer; background-color: #007bff; color: white; border: none; border-radius: 5px; }
        button:disabled { background-color: #cccccc; cursor: not-allowed; }
        .error-message { color: red; margin-top: 10px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Detail Checkout Anda</h1>
        <p>Anda akan melakukan pembayaran untuk 3 item keranjang (ID 1, 2, 3).</p>

        <form id="checkoutForm" action="{{ url('/bayar') }}" method="POST">
            @csrf <input type="hidden" name="cart_ids[]" value="1">
            <input type="hidden" name="cart_ids[]" value="2">
            <input type="hidden" name="cart_ids[]" value="3">

            <button type="submit" id="checkoutButton">Lanjutkan Pembayaran</button>
        </form>

        @if(session('error'))
            <div class="error-message">
                {{ session('error') }}
            </div>
        @endif
    </div>

    <script>
        document.getElementById('checkoutForm').addEventListener('submit', function() {
            const button = document.getElementById('checkoutButton');
            button.disabled = true;
            button.innerText = 'Memproses Pembayaran...';
        });
    </script>
</body>
</html>
