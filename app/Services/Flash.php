<?php

namespace App\Services;

class Flash
{
    private const SESSION_KEY = '_flash_messages';

    /**
     * Set a flash message
     */
    public static function set(string $type, string $message): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE && !headers_sent()) {
            session_start();
        }

        if (!isset($_SESSION[self::SESSION_KEY])) {
            $_SESSION[self::SESSION_KEY] = [];
        }

        $_SESSION[self::SESSION_KEY][$type][] = $message;
    }

    /**
     * Get and clear flash messages for a type
     */
    public static function get(string $type, $default = null)
    {
        if (session_status() !== PHP_SESSION_ACTIVE && !headers_sent()) {
            session_start();
        }

        if (isset($_SESSION[self::SESSION_KEY][$type])) {
            $messages = $_SESSION[self::SESSION_KEY][$type];
            unset($_SESSION[self::SESSION_KEY][$type]);
            return $messages;
        }

        return $default;
    }

    /**
     * Check if a flash type has messages
     */
    public static function has(string $type): bool
    {
        if (session_status() !== PHP_SESSION_ACTIVE && !headers_sent()) {
            session_start();
        }

        return !empty($_SESSION[self::SESSION_KEY][$type]);
    }

    /**
     * Get all flash messages and clear
     */
    public static function all(): array
    {
        if (session_status() !== PHP_SESSION_ACTIVE && !headers_sent()) {
            session_start();
        }

        $all = $_SESSION[self::SESSION_KEY] ?? [];
        $_SESSION[self::SESSION_KEY] = [];
        return $all;
    }
}
