<?php

namespace Database\Seeders;

use PDO;

class TemplateSeeder
{
    public static function run(PDO $pdo): void
    {
        // Get Wedding category ID
        $catStmt = $pdo->prepare("SELECT id FROM categories WHERE slug = :slug LIMIT 1");
        $catStmt->execute(['slug' => 'wedding']);
        $categoryId = $catStmt->fetchColumn();

        if (!$categoryId) {
            echo " - TemplateSeeder error: Wedding category not found.\n";
            return;
        }

        $templates = [
            [
                'category_id' => $categoryId,
                'name' => 'Minimalis Elegan',
                'slug' => 'minimalis-elegan',
                'thumbnail_path' => '/assets/images/templates/minimalis.jpg',
                'view_key' => 'minimal',
                'description' => 'Desain minimalis dengan tipografi elegan dan sentuhan monokrom modern.',
                'is_active' => 1,
            ],
            [
                'category_id' => $categoryId,
                'name' => 'Floral Klasik',
                'slug' => 'floral-klasik',
                'thumbnail_path' => '/assets/images/templates/floral.jpg',
                'view_key' => 'floral',
                'description' => 'Desain bernuansa bunga alami dengan ornamen klasik romantis dan lembut.',
                'is_active' => 1,
            ],
            [
                'category_id' => $categoryId,
                'name' => 'Modern Sederhana',
                'slug' => 'modern-sederhana',
                'thumbnail_path' => '/assets/images/templates/modern.jpg',
                'view_key' => 'modern',
                'description' => 'Desain kontemporer yang bersih, tegas, dinamis, dan sangat mudah dibaca.',
                'is_active' => 1,
            ],
        ];

        $stmt = $pdo->prepare("
            INSERT INTO templates (category_id, name, slug, thumbnail_path, view_key, description, is_active)
            VALUES (:category_id, :name, :slug, :thumbnail_path, :view_key, :description, :is_active)
            ON DUPLICATE KEY UPDATE 
                name = VALUES(name), 
                thumbnail_path = VALUES(thumbnail_path), 
                view_key = VALUES(view_key),
                description = VALUES(description),
                is_active = VALUES(is_active)
        ");

        foreach ($templates as $tmpl) {
            $stmt->execute($tmpl);
        }

        echo " - TemplateSeeder: seeded " . count($templates) . " templates.\n";
    }
}
