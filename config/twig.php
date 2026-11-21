<?php

return [
    'views_path' => dirname(__DIR__) . '/app/Views',
    'cache_path' => env('APP_ENV') === 'production' ? dirname(__DIR__) . '/storage/cache/twig' : false,
    'debug' => (bool)env('APP_DEBUG', true),
    'auto_reload' => true,
];
