<?php

namespace App\Middleware;

use App\Services\AuthService;
use App\Services\Flash;
use Flight;

class AdminMiddleware
{
    public function before(): void
    {
        $user = AuthService::user();
        if (!$user || ($user['role'] ?? '') !== 'admin') {
            Flash::set('error', 'Akses ditolak. Halaman ini hanya dapat diakses oleh administrator.');
            Flight::redirect('/dashboard');
            exit;
        }
    }
}
