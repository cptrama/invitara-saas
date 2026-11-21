<?php

namespace App\Repositories;

use App\Services\Database;
use PDO;

class TemplateRepository
{
    private PDO $db;

    public function __construct(?PDO $db = null)
    {
        $this->db = $db ?? Database::getConnection();
    }

    public function allActive(): array
    {
        $stmt = $this->db->query("
            SELECT t.*, c.name AS category_name, c.slug AS category_slug 
            FROM templates t
            JOIN categories c ON c.id = t.category_id
            WHERE t.is_active = 1
            ORDER BY t.id ASC
        ");
        return $stmt->fetchAll() ?: [];
    }

    public function all(): array
    {
        $stmt = $this->db->query("
            SELECT t.*, c.name AS category_name 
            FROM templates t
            JOIN categories c ON c.id = t.category_id
            ORDER BY t.id ASC
        ");
        return $stmt->fetchAll() ?: [];
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare("
            SELECT t.*, c.name AS category_name 
            FROM templates t
            JOIN categories c ON c.id = t.category_id
            WHERE t.id = :id 
            LIMIT 1
        ");
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public function findBySlug(string $slug): ?array
    {
        $stmt = $this->db->prepare("
            SELECT t.*, c.name AS category_name 
            FROM templates t
            JOIN categories c ON c.id = t.category_id
            WHERE t.slug = :slug 
            LIMIT 1
        ");
        $stmt->execute(['slug' => $slug]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public function findByViewKey(string $viewKey): ?array
    {
        $stmt = $this->db->prepare("
            SELECT t.*, c.name AS category_name 
            FROM templates t
            JOIN categories c ON c.id = t.category_id
            WHERE t.view_key = :view_key 
            LIMIT 1
        ");
        $stmt->execute(['view_key' => $viewKey]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare("
            INSERT INTO templates (category_id, name, slug, thumbnail_path, view_key, description, is_active)
            VALUES (:category_id, :name, :slug, :thumbnail_path, :view_key, :description, :is_active)
        ");
        $stmt->execute([
            'category_id' => $data['category_id'],
            'name' => trim($data['name']),
            'slug' => trim($data['slug']),
            'thumbnail_path' => $data['thumbnail_path'],
            'view_key' => $data['view_key'],
            'description' => $data['description'] ?? null,
            'is_active' => isset($data['is_active']) ? (int)$data['is_active'] : 1,
        ]);
        return (int)$this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $fields = [];
        $params = ['id' => $id];

        $allowed = ['category_id', 'name', 'slug', 'thumbnail_path', 'view_key', 'description', 'is_active'];
        foreach ($allowed as $field) {
            if (array_key_exists($field, $data)) {
                $fields[] = "{$field} = :{$field}";
                $params[$field] = $data[$field];
            }
        }

        if (empty($fields)) {
            return false;
        }

        $sql = "UPDATE templates SET " . implode(', ', $fields) . " WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }
}
