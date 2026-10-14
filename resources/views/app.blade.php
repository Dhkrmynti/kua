<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        @php
            $judul = config('app.name').' — '.config('kua.nama');
            $deskripsi = 'Urus nikah, talak, cerai & rujuk di '.config('app.name').' '.config('kua.nama').' tanpa antre. Ajukan berkas, pantau verifikasi, dan sampaikan pengaduan secara online.';
            $gambar = asset('og-image.png');
        @endphp

        {{-- Judul awal untuk crawler (tidak menjalankan JS); Inertia menggantinya di browser. --}}
        <title inertia>{{ $judul }}</title>
        <meta name="description" content="{{ $deskripsi }}">
        <meta name="theme-color" content="#064e3b">
        @if (config('kua.noindex'))
            <meta name="robots" content="noindex, nofollow">
        @endif

        {{-- Pratinjau tautan (WhatsApp, Facebook, Telegram, X, LinkedIn) --}}
        <meta property="og:type" content="website">
        <meta property="og:site_name" content="{{ config('app.name') }}">
        <meta property="og:locale" content="id_ID">
        <meta property="og:url" content="{{ url()->current() }}">
        <meta property="og:title" content="{{ $judul }}">
        <meta property="og:description" content="{{ $deskripsi }}">
        <meta property="og:image" content="{{ $gambar }}">
        <meta property="og:image:secure_url" content="{{ $gambar }}">
        <meta property="og:image:type" content="image/png">
        <meta property="og:image:width" content="1200">
        <meta property="og:image:height" content="630">
        <meta property="og:image:alt" content="Kantor Urusan Agama: urus nikah, talak, cerai, dan rujuk tanpa antre">
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="{{ $judul }}">
        <meta name="twitter:description" content="{{ $deskripsi }}">
        <meta name="twitter:image" content="{{ $gambar }}">

        <link rel="icon" href="/favicon.ico">
        <link rel="apple-touch-icon" href="/apple-touch-icon.png">

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Amiri:wght@700&display=swap" rel="stylesheet">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @inertiaHead
    </head>
    <body class="font-sans antialiased">
        @inertia
    </body>
</html>
