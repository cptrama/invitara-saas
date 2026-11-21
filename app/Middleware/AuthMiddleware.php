<?php

namespace App\Middleware;

use App\Services\AuthService;
use App\Services\Flash;
use Flight;

class AuthMiddleware
{
    public function before(): void
    {
        if (!AuthService::check()) {
            Flash::set('error', 'Silakan masuk terlebih dahulu untuk mengakses halaman ini.');
            
            // Store requested URL to redirect back after login
            if (session_status() === PHP_SESSION_ACTIVE) {
                $_SESSION['_redirect_after_login'] = Flight::request()->url;
            }

            Flight::redirect('/login');
            exit;
        }
    }
}
