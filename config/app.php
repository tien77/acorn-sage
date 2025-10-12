<?php
// config/app.php
return [
    'name' => env('APP_NAME', 'Sage'),
    'env' => env('APP_ENV', 'production'),
    'debug' => (bool) env('APP_DEBUG', false),

    // tuỳ chọn cho đủ bộ, không bắt buộc
    'url' => env('APP_URL', home_url('/')),
    'timezone' => env('APP_TIMEZONE', 'UTC'),
    'locale' => get_locale() ?: 'en',
    'fallback_locale' => 'en',
    'key' => env('APP_KEY'),
    'cipher' => 'AES-256-CBC',
];
