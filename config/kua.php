<?php

return [
    'nama' => env('KUA_NAMA', 'KUA Kecamatan'),
    'kabupaten' => env('KUA_KABUPATEN', ''),
    'alamat' => env('KUA_ALAMAT', ''),
    'telepon' => env('KUA_TELEPON', ''),
    'email' => env('KUA_EMAIL', ''),
    'jam_layanan' => env('KUA_JAM_LAYANAN', 'Senin–Jumat, 08.00–16.00'),

    // true = minta mesin pencari tidak mengindeks (untuk situs demo).
    'noindex' => (bool) env('KUA_NOINDEX', false),

    // Kata sandi akun demo yang dibuat oleh seeder.
    'demo_password' => env('DEMO_PASSWORD', 'password'),
];
