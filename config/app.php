<?php

return [
    'name' => env('APP_NAME', 'Invitara'),
    'env' => env('APP_ENV', 'development'),
    'debug' => env('APP_DEBUG', true),
    'url' => env('APP_URL', 'http://localhost:8000'),
    'timezone' => env('APP_TIMEZONE', 'Asia/Jakarta'),
    'session' => [
        'lifetime' => (int)env('SESSION_LIFETIME', 7200),
        'secure' => (bool)env('SESSION_SECURE', false),
    ],
    'upload' => [
        'max_size' => (int)env('UPLOAD_MAX_SIZE', 2097152),
        'allowed_types' => explode(',', env('UPLOAD_ALLOWED_TYPES', 'image/jpeg,image/png,image/webp')),
    ]
];
