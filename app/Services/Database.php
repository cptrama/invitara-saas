<?php

namespace App\Services;

use PDO;
use PDOException;
use RuntimeException;

class Database
{
    private static ?PDO $pdo = null;

    /**
     * Get active PDO connection instance (Singleton)
     */
    public static function getConnection(): PDO
    {
        if (self::$pdo === null) {
            $configPath = dirname(__DIR__, 2) . '/config/database.php';
            if (!file_exists($configPath)) {
                throw new RuntimeException('Database configuration file not found.');
            }

            $config = require $configPath;
            $default = $config['default'] ?? 'mysql';
            $dbConfig = $config['connections'][$default] ?? null;

            if (!$dbConfig) {
                throw new RuntimeException("Database configuration for '{$default}' not found.");
            }

            $dsn = sprintf(
                '%s:host=%s;port=%d;dbname=%s;charset=%s',
                $dbConfig['driver'],
                $dbConfig['host'],
                $dbConfig['port'],
                $dbConfig['database'],
                $dbConfig['charset']
            );

            try {
                self::$pdo = new PDO(
                    $dsn,
                    $dbConfig['username'],
                    $dbConfig['password'],
                    $dbConfig['options']
                );
            } catch (PDOException $e) {
                throw new RuntimeException('Database connection failed: ' . $e->getMessage(), (int)$e->getCode(), $e);
            }
        }

        return self::$pdo;
    }

    /**
     * Explicitly set PDO connection (useful for testing or mocks)
     */
    public static function setConnection(?PDO $pdo): void
    {
        self::$pdo = $pdo;
    }

    /**
     * Begin database transaction
     */
    public static function beginTransaction(): bool
    {
        return self::getConnection()->beginTransaction();
    }

    /**
     * Commit active transaction
     */
    public static function commit(): bool
    {
        return self::getConnection()->commit();
    }

    /**
     * Rollback active transaction
     */
    public static function rollBack(): bool
    {
        return self::getConnection()->rollBack();
    }
}
