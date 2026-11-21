<?php

require_once dirname(__DIR__) . '/vendor/autoload.php';

use Dotenv\Dotenv;
use App\Services\Database;
use Database\Seeders\UserSeeder;
use Database\Seeders\CategorySeeder;
use Database\Seeders\TemplateSeeder;

if (file_exists(dirname(__DIR__) . '/.env')) {
    $dotenv = Dotenv::createImmutable(dirname(__DIR__));
    $dotenv->load();
}

try {
    $pdo = Database::getConnection();

    echo "Running seeders...\n";
    CategorySeeder::run($pdo);
    UserSeeder::run($pdo);
    TemplateSeeder::run($pdo);
    echo "All seeders completed successfully!\n";
} catch (Throwable $e) {
    echo "\n[ERROR] Seeding failed: " . $e->getMessage() . "\n";
    exit(1);
}
