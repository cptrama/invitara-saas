<?php

namespace App\Middleware;

use App\Services\AuthService;
use Flight;

class GuestMiddleware
{
    public function before(): void
    {
        if (AuthService::check()) {
            Flight::redirect('/dashboard');
            exit;
        }
    }
}
