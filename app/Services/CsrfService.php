<?php

namespace App\Services;

class CsrfService
{
    private const TOKEN_KEY = '_csrf_token';

    /**
     * Get existing token or generate new one
     */
    public static function getToken(): string
    {
        if (session_status() !== PHP_SESSION_ACTIVE && !headers_sent()) {
            session_start();
        }

        if (empty($_SESSION[self::TOKEN_KEY])) {
            $_SESSION[self::TOKEN_KEY] = bin2hex(random_bytes(32));
        }

        return $_SESSION[self::TOKEN_KEY];
    }

    /**
     * Generate fresh token (e.g. after login/logout)
     */
    public static function regenerate(): string
    {
        if (session_status() !== PHP_SESSION_ACTIVE && !headers_sent()) {
            session_start();
        }

        $_SESSION[self::TOKEN_KEY] = bin2hex(random_bytes(32));
        return $_SESSION[self::TOKEN_KEY];
    }

    /**
     * Validate submitted token against session token
     */
    public static function validate(?string $token): bool
    {
        if (session_status() !== PHP_SESSION_ACTIVE && !headers_sent()) {
            session_start();
        }

        if (empty($token) || empty($_SESSION[self::TOKEN_KEY])) {
            return false;
        }

        return hash_equals($_SESSION[self::TOKEN_KEY], $token);
    }

    /**
     * Render hidden HTML input field
     */
    public static function renderField(): string
    {
        $token = self::getToken();
        return '<input type="hidden" name="_csrf_token" value="' . htmlspecialchars($token, ENT_QUOTES, 'UTF-8') . '">';
    }
}
