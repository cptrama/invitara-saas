<?php

namespace Database\Seeders;

use PDO;

class CategorySeeder
{
    public static function run(PDO $pdo): void
    {
        $categories = [
            [
                'name' => 'Wedding',
                'slug' => 'wedding',
                'description' => 'Template undangan pernikahan digital',
            ],
        ];

        $stmt = $pdo->prepare("
            INSERT INTO categories (name, slug, description) 
            VALUES (:name, :slug, :description)
            ON DUPLICATE KEY UPDATE name = VALUES(name), description = VALUES(description)
        ");

        foreach ($categories as $cat) {
            $stmt->execute($cat);
        }

        echo " - CategorySeeder: seeded " . count($categories) . " categories.\n";
    }
}
