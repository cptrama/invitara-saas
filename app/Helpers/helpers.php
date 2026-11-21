<?php

use App\Services\CsrfService;
use App\Services\Flash;

if (!function_exists('env')) {
    /**
     * Get environment variable or return default value
     */
    function env(string $key, $default = null)
    {
        $value = $_ENV[$key] ?? $_SERVER[$key] ?? getenv($key);

        if ($value === false || $value === null) {
            return $default;
        }

        switch (strtolower((string)$value)) {
            case 'true':
            case '(true)':
                return true;
            case 'false':
            case '(false)':
                return false;
            case 'empty':
            case '(empty)':
                return '';
            case 'null':
            case '(null)':
                return null;
        }

        return $value;
    }
}

if (!function_exists('url')) {
    /**
     * Generate application URL
     */
    function url(string $path = ''): string
    {
        $baseUrl = rtrim(env('APP_URL', 'http://localhost:8000'), '/');
        $path = ltrim($path, '/');
        return $path === '' ? $baseUrl : $baseUrl . '/' . $path;
    }
}

if (!function_exists('asset')) {
    /**
     * Generate URL for assets
     */
    function asset(string $path): string
    {
        $path = ltrim($path, '/');
        return url($path);
    }
}

if (!function_exists('csrf_token')) {
    /**
     * Get current CSRF token
     */
    function csrf_token(): string
    {
        return CsrfService::getToken();
    }
}

if (!function_exists('csrf_field')) {
    /**
     * Render hidden CSRF token input
     */
    function csrf_field(): string
    {
        return CsrfService::renderField();
    }
}

if (!function_exists('auth_user')) {
    /**
     * Get currently logged-in user array or null
     */
    function auth_user(): ?array
    {
        return $_SESSION['user'] ?? null;
    }
}

if (!function_exists('is_authenticated')) {
    /**
     * Check if user is logged in
     */
    function is_authenticated(): bool
    {
        return isset($_SESSION['user']['id']);
    }
}

if (!function_exists('is_admin')) {
    /**
     * Check if logged in user is admin
     */
    function is_admin(): bool
    {
        return is_authenticated() && (($_SESSION['user']['role'] ?? '') === 'admin');
    }
}

if (!function_exists('flash')) {
    /**
     * Retrieve flash message by key
     */
    function flash(string $key, $default = null)
    {
        return Flash::get($key, $default);
    }
}

if (!function_exists('set_flash')) {
    /**
     * Set a flash message
     */
    function set_flash(string $type, string $message): void
    {
        Flash::set($type, $message);
    }
}

if (!function_exists('old')) {
    /**
     * Get previous form submission input
     */
    function old(string $key, $default = '')
    {
        $oldInputs = $_SESSION['_old_input'] ?? [];
        return $oldInputs[$key] ?? $default;
    }
}
