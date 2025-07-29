<x-layout>





    <div class="container my-4">
        <h2>Halaman Pembayaran</h2>

        @if ($selectedCartItems->isNotEmpty())
            <p>Anda akan melakukan pembayaran untuk item-item berikut:</p>
            <ul>
                @foreach ($selectedCartItems as $item)
                    <li>
                        Produk ID: {{ $item->vehicle_id }} -
                        Tanggal Mulai: {{ $item->start_date }} -
                        Tanggal Selesai: {{ $item->end_date }}
                        {{-- Anda dapat menambahkan detail lebih lanjut dari relasi $item->vehicle jika dimuat secara eager --}}
                    </li>
                @endforeach
            </ul>
            <p>Di sini Anda akan mengintegrasikan gateway pembayaran atau menampilkan total harga akhir.</p>
            {{-- Tambahkan integrasi gateway pembayaran Anda atau langkah-langkah selanjutnya di sini --}}
        @else
            <p>Tidak ada item yang dipilih untuk pembayaran.</p>
        @endif

        {{-- <a href="{{ route('cart.index') }}" class="btn btn-secondary mt-3">Kembali ke Keranjang</a> --}}
    </div>
</x-layout>