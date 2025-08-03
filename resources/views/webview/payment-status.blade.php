<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <style>
        /* Menggunakan font yang umum dan mudah dibaca */
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background-color: #f8f9fa; /* Warna latar belakang abu-abu muda */
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            color: #333;
        }
        /* Kontainer utama untuk konten di tengah halaman */
        .container {
            text-align: center;
            padding: 40px;
            background-color: #fff; /* Latar belakang putih untuk kartu */
            border-radius: 12px; /* Sudut yang lebih membulat */
            box-shadow: 0 4px_20px rgba(0,0,0,0.1); /* Bayangan yang lembut */
            max-width: 450px;
            width: 90%;
            border-top: 5px solid; /* Garis atas sebagai indikator status */
        }
        /* Styling untuk kontainer berdasarkan status sukses atau gagal */
        .container.success {
            border-color: #28a745; /* Hijau untuk sukses */
        }
        .container.failed {
            border-color: #dc3545; /* Merah untuk gagal */
        }
        /* Ikon status (checkmark atau silang) */
        .icon {
            font-size: 60px;
            line-height: 1;
            margin-bottom: 20px;
        }
        .icon.success {
            color: #28a745;
        }
        .icon.failed {
            color: #dc3545;
        }
        /* Judul halaman */
        h1 {
            font-size: 2rem;
            margin-bottom: 1rem;
            font-weight: 600;
        }
        /* Pesan deskripsi */
        p {
            font-size: 1.1rem;
            color: #6c757d; /* Warna abu-abu untuk teks sekunder */
            margin-bottom: 2rem;
            line-height: 1.6;
        }
        /* Tombol aksi */
        .btn {
            display: inline-block;
            padding: 12px 25px;
            font-size: 1rem;
            font-weight: 600;
            color: #fff;
            text-decoration: none;
            border-radius: 8px;
            transition: background-color 0.2s ease, transform 0.2s ease;
            border: none;
            cursor: pointer;
        }
        .btn:hover {
            opacity: 0.9;
            transform: translateY(-2px); /* Efek angkat saat di-hover */
        }
        .btn.success {
            background-color: #28a745;
        }
        .btn.failed {
            background-color: #dc3545;
        }
    </style>
</head>
<body>
    {{-- Menambahkan kelas 'success' atau 'failed' ke kontainer utama --}}
    <div class="container {{ $status_class }}">
        {{-- Menampilkan ikon yang sesuai berdasarkan status --}}
        @if($status_class === 'success')
            <div class="icon success">&#10004;</div> <!-- Ikon checkmark -->
        @else
            <div class="icon failed">&#10006;</div> <!-- Ikon silang -->
        @endif

        {{-- Menampilkan judul dan pesan yang dikirim dari controller --}}
        <h1>{{ $title }}</h1>
        <p>{{ $message }}</p>

        {{-- Tombol untuk mengarahkan pengguna kembali --}}
        <a href="{{ $homeUrl }}" class="btn {{ $status_class }}">{{ $buttonText }}</a>
    </div>
</body>
</html>
