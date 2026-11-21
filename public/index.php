<?php

declare(strict_types=1);

// Ensure vendor autoload exists
$autoload = dirname(__DIR__) . '/vendor/autoload.php';
if (!file_exists($autoload)) {
    die("Autoload file not found. Please run 'composer install'.");
}
require_once $autoload;

use Dotenv\Dotenv;
use App\Services\Database;
use App\Services\View;

// Load Environment Configuration
if (file_exists(dirname(__DIR__) . '/.env')) {
    $dotenv = Dotenv::createImmutable(dirname(__DIR__));
    $dotenv->safeLoad();
}

// Set Default Timezone
date_default_timezone_set(env('APP_TIMEZONE', 'Asia/Jakarta'));

// Start Session with Secure Attributes
if (session_status() === PHP_SESSION_NONE) {
    $lifetime = (int)env('SESSION_LIFETIME', 7200);
    $isSecure = (bool)env('SESSION_SECURE', false);

    session_set_cookie_params([
        'lifetime' => $lifetime,
        'path' => '/',
        'domain' => '',
        'secure' => $isSecure,
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_start();
}

// Configure Flight Error Handling
Flight::set('flight.log_errors', true);

Flight::map('error', function (Throwable $error) {
    // Log exception to storage/logs
    $logDir = dirname(__DIR__) . '/storage/logs';
    if (!is_dir($logDir)) {
        mkdir($logDir, 0777, true);
    }
    $logMessage = sprintf(
        "[%s] %s in %s:%d\nStack Trace:\n%s\n\n",
        date('Y-m-d H:i:s'),
        $error->getMessage(),
        $error->getFile(),
        $error->getLine(),
        $error->getTraceAsString()
    );
    file_put_contents($logDir . '/app.log', $logMessage, FILE_APPEND);

    $isDebug = (bool)env('APP_DEBUG', true);
    if ($isDebug) {
        Flight::response()
            ->status(500)
            ->write("<h1>500 Internal Server Error</h1>")
            ->write("<p><strong>Message:</strong> " . htmlspecialchars($error->getMessage()) . "</p>")
            ->write("<p><strong>File:</strong> " . htmlspecialchars($error->getFile()) . ":" . $error->getLine() . "</p>")
            ->write("<pre>" . htmlspecialchars($error->getTraceAsString()) . "</pre>")
            ->send();
    } else {
        Flight::response()
            ->status(500)
            ->write("<h1>500 Internal Server Error</h1><p>Terjadi kesalahan pada sistem. Silakan coba beberapa saat lagi.</p>")
            ->send();
    }
});

// Register Flight view renderer mapping
Flight::map('renderTwig', function (string $template, array $data = []) {
    echo View::render($template, $data);
});

// Register Database into Flight container
Flight::register('db', Database::class, ['getConnection']);

// Load Web Routes
require_once dirname(__DIR__) . '/routes/web.php';

// Dispatch Flight
Flight::start();
