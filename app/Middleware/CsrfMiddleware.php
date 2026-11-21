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
            $req = Flight::request();
            $token = $req->data->_csrf_token 
                ?? ($req->data['_csrf_token'] ?? null)
                ?? ($_POST['_csrf_token'] ?? null)
                ?? $req->getHeader('X-CSRF-TOKEN') 
                ?? null;

            if (!CsrfService::validate($token)) {
                Flash::set('error', 'Sesi login telah kedaluwarsa atau token tidak valid. Silakan coba kembali.');
                
                // If on login or register form, redirect back gracefully so user sees the message
                $url = $req->url;
                if ($url === '/login' || $url === '/register') {
                    Flight::redirect($url);
                    exit;
                }

                Flight::halt(403, '403 Forbidden - Token CSRF tidak valid atau kedaluwarsa. Silakan muat ulang halaman.');
                exit;
            }
        }
    }
}
