<?php

namespace App\Repositories;

use App\Services\Database;
use PDO;

class InvitationPhotoRepository
{
    private PDO $db;

    public function __construct(?PDO $db = null)
    {
        $this->db = $db ?? Database::getConnection();
    }

    public function getByInvitationId(int $invitationId): array
    {
        $stmt = $this->db->prepare("
            SELECT * FROM invitation_photos 
            WHERE invitation_id = :invitation_id 
            ORDER BY sort_order ASC, id ASC
        ");
        $stmt->execute(['invitation_id' => $invitationId]);
        return $stmt->fetchAll() ?: [];
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM invitation_photos WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare("
            INSERT INTO invitation_photos (invitation_id, photo_path, caption, sort_order)
            VALUES (:invitation_id, :photo_path, :caption, :sort_order)
        ");
        $stmt->execute([
            'invitation_id' => $data['invitation_id'],
            'photo_path' => $data['photo_path'],
            'caption' => $data['caption'] ?? null,
            'sort_order' => $data['sort_order'] ?? 0,
        ]);
        return (int)$this->db->lastInsertId();
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM invitation_photos WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    public function updateOrder(int $id, int $sortOrder): bool
    {
        $stmt = $this->db->prepare("UPDATE invitation_photos SET sort_order = :sort_order WHERE id = :id");
        return $stmt->execute(['id' => $id, 'sort_order' => $sortOrder]);
    }
}
