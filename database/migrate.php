<?php

require_once dirname(__DIR__) . '/vendor/autoload.php';

use Dotenv\Dotenv;
use App\Services\Database;

// Load environment variables
if (file_exists(dirname(__DIR__) . '/.env')) {
    $dotenv = Dotenv::createImmutable(dirname(__DIR__));
    $dotenv->load();
}

try {
    $pdo = Database::getConnection();

    // Ensure migrations table exists
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS migrations (
            id INT AUTO_INCREMENT PRIMARY KEY,
            migration VARCHAR(255) NOT NULL UNIQUE,
            batch INT NOT NULL,
            executed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ");

    $command = $argv[1] ?? 'migrate';

    if ($command === 'fresh') {
        echo "Dropping all tables...\n";
        $pdo->exec("SET FOREIGN_KEY_CHECKS = 0;");
        $stmt = $pdo->query("SHOW TABLES");
        $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
        foreach ($tables as $table) {
            $pdo->exec("DROP TABLE IF EXISTS `{$table}`");
            echo " - Dropped table: {$table}\n";
        }
        $pdo->exec("SET FOREIGN_KEY_CHECKS = 1;");

        // Recreate migrations table
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS migrations (
                id INT AUTO_INCREMENT PRIMARY KEY,
                migration VARCHAR(255) NOT NULL UNIQUE,
                batch INT NOT NULL,
                executed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ");
        $command = 'migrate';
    }

    if ($command === 'status') {
        $stmt = $pdo->query("SELECT migration, batch FROM migrations ORDER BY id ASC");
        $executed = $stmt->fetchAll(PDO::FETCH_KEY_PAIR) ?: [];

        $files = glob(__DIR__ . '/migrations/*.sql');
        sort($files);

        echo "Migration Status:\n";
        echo str_repeat('-', 70) . "\n";
        printf("%-45s | %-10s\n", "Migration File", "Status");
        echo str_repeat('-', 70) . "\n";

        foreach ($files as $file) {
            $name = basename($file);
            $status = isset($executed[$name]) ? "Applied (Batch {$executed[$name]})" : "Pending";
            printf("%-45s | %-10s\n", $name, $status);
        }
        exit(0);
    }

    if ($command === 'migrate') {
        // Get applied migrations
        $stmt = $pdo->query("SELECT migration FROM migrations");
        $applied = $stmt->fetchAll(PDO::FETCH_COLUMN) ?: [];

        // Determine current batch number
        $batchStmt = $pdo->query("SELECT MAX(batch) as max_batch FROM migrations");
        $currentBatch = (int)($batchStmt->fetchColumn() ?: 0) + 1;

        $files = glob(__DIR__ . '/migrations/*.sql');
        sort($files);

        $executedCount = 0;
        foreach ($files as $file) {
            $name = basename($file);
            if (in_array($name, $applied, true)) {
                continue;
            }

            echo "Migrating: {$name} ... ";
            $sql = file_get_contents($file);
            $pdo->exec($sql);

            $insertStmt = $pdo->prepare("INSERT INTO migrations (migration, batch) VALUES (:migration, :batch)");
            $insertStmt->execute([
                'migration' => $name,
                'batch' => $currentBatch,
            ]);

            echo "DONE\n";
            $executedCount++;
        }

        if ($executedCount === 0) {
            echo "Nothing to migrate. All migrations are up to date.\n";
        } else {
            echo "Successfully migrated {$executedCount} files (Batch: {$currentBatch}).\n";
        }
    }
} catch (Throwable $e) {
    echo "\n[ERROR] Migration failed: " . $e->getMessage() . "\n";
    exit(1);
}
