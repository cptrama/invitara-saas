<?php

namespace App\Repositories;

use App\Services\Database;
use PDO;

class RSVPRepository
{
    private PDO $db;

    public function __construct(?PDO $db = null)
    {
        $this->db = $db ?? Database::getConnection();
    }

    public function getByInvitationId(int $invitationId): array
    {
        $stmt = $this->db->prepare("
            SELECT * FROM rsvps 
            WHERE invitation_id = :invitation_id 
            ORDER BY created_at DESC
        ");
        $stmt->execute(['invitation_id' => $invitationId]);
        return $stmt->fetchAll() ?: [];
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM rsvps WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public function getSummaryByInvitationId(int $invitationId): array
    {
        $stmt = $this->db->prepare("
            SELECT 
                COUNT(*) AS total_responses,
                SUM(CASE WHEN attendance_status = 'attending' THEN 1 ELSE 0 END) AS total_attending,
                SUM(CASE WHEN attendance_status = 'not_attending' THEN 1 ELSE 0 END) AS total_not_attending,
                COALESCE(SUM(CASE WHEN attendance_status = 'attending' THEN guest_count ELSE 0 END), 0) AS total_attending_guests
            FROM rsvps
            WHERE invitation_id = :invitation_id
        ");
        $stmt->execute(['invitation_id' => $invitationId]);
        $row = $stmt->fetch();

        return [
            'total_responses' => (int)($row['total_responses'] ?? 0),
            'total_attending' => (int)($row['total_attending'] ?? 0),
            'total_not_attending' => (int)($row['total_not_attending'] ?? 0),
            'total_attending_guests' => (int)($row['total_attending_guests'] ?? 0),
        ];
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare("
            INSERT INTO rsvps (invitation_id, guest_name, attendance_status, guest_count, message)
            VALUES (:invitation_id, :guest_name, :attendance_status, :guest_count, :message)
        ");
        $stmt->execute([
            'invitation_id' => $data['invitation_id'],
            'guest_name' => trim($data['guest_name']),
            'attendance_status' => $data['attendance_status'] === 'attending' ? 'attending' : 'not_attending',
            'guest_count' => ($data['attendance_status'] ?? '') === 'attending' ? max(1, (int)($data['guest_count'] ?? 1)) : 0,
            'message' => !empty($data['message']) ? trim($data['message']) : null,
        ]);
        return (int)$this->db->lastInsertId();
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM rsvps WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    public function countByInvitation(int $invitationId): int
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM rsvps WHERE invitation_id = :invitation_id");
        $stmt->execute(['invitation_id' => $invitationId]);
        return (int)$stmt->fetchColumn();
    }
}
