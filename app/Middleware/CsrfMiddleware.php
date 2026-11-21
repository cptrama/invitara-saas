<?php

namespace App\Middleware;

use App\Services\CsrfService;
use App\Services\Flash;
use Flight;

class CsrfMiddleware
{
    public function before(): void
    {
        $method = strtoupper(Flight::request()->method);
        if (in_array($method, ['POST', 'PUT', 'DELETE', 'PATCH'], true)) {
            $token = Flight::request()->data->_csrf_token 
                ?? Flight::request()->getHeader('X-CSRF-TOKEN') 
                ?? null;

            if (!CsrfService::validate($token)) {
                Flash::set('error', 'Sesi kedaluwarsa atau token keamanan tidak valid. Silakan coba lagi.');
                Flight::halt(403, '403 Forbidden - Token CSRF tidak valid atau kedaluwarsa.');
                exit;
            }
        }
    }
}
