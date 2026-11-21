<?php

namespace App\Repositories;

use App\Services\Database;
use PDO;

class InvitationRepository
{
    private PDO $db;

    public function __construct(?PDO $db = null)
    {
        $this->db = $db ?? Database::getConnection();
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare("
            SELECT i.*, t.name AS template_name, t.view_key AS template_view_key, t.slug AS template_slug
            FROM invitations i
            JOIN templates t ON t.id = i.template_id
            WHERE i.id = :id
            LIMIT 1
        ");
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public function findBySlug(string $slug): ?array
    {
        $stmt = $this->db->prepare("
            SELECT i.*, t.name AS template_name, t.view_key AS template_view_key, t.slug AS template_slug
            FROM invitations i
            JOIN templates t ON t.id = i.template_id
            WHERE i.slug = :slug
            LIMIT 1
        ");
        $stmt->execute(['slug' => $slug]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public function getPublishedBySlug(string $slug): ?array
    {
        $stmt = $this->db->prepare("
            SELECT i.*, t.name AS template_name, t.view_key AS template_view_key, t.slug AS template_slug
            FROM invitations i
            JOIN templates t ON t.id = i.template_id
            WHERE i.slug = :slug AND i.status = 'published'
            LIMIT 1
        ");
        $stmt->execute(['slug' => $slug]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public function getByUserId(int $userId): array
    {
        $stmt = $this->db->prepare("
            SELECT i.*, t.name AS template_name, t.thumbnail_path AS template_thumbnail,
                   (SELECT COUNT(*) FROM rsvps r WHERE r.invitation_id = i.id) AS total_rsvps,
                   (SELECT COALESCE(SUM(guest_count), 0) FROM rsvps r WHERE r.invitation_id = i.id AND r.attendance_status = 'attending') AS total_attending_guests
            FROM invitations i
            JOIN templates t ON t.id = i.template_id
            WHERE i.user_id = :user_id
            ORDER BY i.created_at DESC
        ");
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetchAll() ?: [];
    }

    public function countByUserId(int $userId): array
    {
        $stmt = $this->db->prepare("
            SELECT 
                COUNT(*) AS total,
                SUM(CASE WHEN status = 'draft' THEN 1 ELSE 0 END) AS drafts,
                SUM(CASE WHEN status = 'published' THEN 1 ELSE 0 END) AS published,
                SUM(CASE WHEN status = 'unpublished' THEN 1 ELSE 0 END) AS unpublished,
                COALESCE((
                    SELECT COUNT(*) 
                    FROM rsvps r 
                    JOIN invitations inv ON inv.id = r.invitation_id 
                    WHERE inv.user_id = :uid_rsvp
                ), 0) AS total_rsvps
            FROM invitations
            WHERE user_id = :user_id
        ");
        $stmt->execute([
            'user_id' => $userId,
            'uid_rsvp' => $userId,
        ]);
        $counts = $stmt->fetch();
        return [
            'total' => (int)($counts['total'] ?? 0),
            'drafts' => (int)($counts['drafts'] ?? 0),
            'published' => (int)($counts['published'] ?? 0),
            'unpublished' => (int)($counts['unpublished'] ?? 0),
            'total_rsvps' => (int)($counts['total_rsvps'] ?? 0),
        ];
    }

    public function checkOwnership(int $invitationId, int $userId): bool
    {
        $stmt = $this->db->prepare("SELECT id FROM invitations WHERE id = :id AND user_id = :user_id LIMIT 1");
        $stmt->execute(['id' => $invitationId, 'user_id' => $userId]);
        return (bool)$stmt->fetchColumn();
    }

    public function isSlugAvailable(string $slug, ?int $ignoreId = null): bool
    {
        $sql = "SELECT id FROM invitations WHERE slug = :slug";
        $params = ['slug' => strtolower(trim($slug))];

        if ($ignoreId !== null) {
            $sql .= " AND id != :ignore_id";
            $params['ignore_id'] = $ignoreId;
        }

        $sql .= " LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn() === false;
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare("
            INSERT INTO invitations (
                user_id, template_id, title, slug, partner_one_name, partner_two_name,
                cover_photo_path, event_date, event_time, timezone, venue_name, venue_address,
                maps_url, description, closing_message, theme_color, status, published_at
            ) VALUES (
                :user_id, :template_id, :title, :slug, :partner_one_name, :partner_two_name,
                :cover_photo_path, :event_date, :event_time, :timezone, :venue_name, :venue_address,
                :maps_url, :description, :closing_message, :theme_color, :status, :published_at
            )
        ");

        $stmt->execute([
            'user_id' => $data['user_id'],
            'template_id' => $data['template_id'],
            'title' => trim($data['title']),
            'slug' => strtolower(trim($data['slug'])),
            'partner_one_name' => trim($data['partner_one_name']),
            'partner_two_name' => trim($data['partner_two_name']),
            'cover_photo_path' => $data['cover_photo_path'] ?? null,
            'event_date' => $data['event_date'],
            'event_time' => $data['event_time'] ?? null,
            'timezone' => $data['timezone'] ?? 'Asia/Jakarta',
            'venue_name' => $data['venue_name'] ?? null,
            'venue_address' => $data['venue_address'] ?? null,
            'maps_url' => $data['maps_url'] ?? null,
            'description' => $data['description'] ?? null,
            'closing_message' => $data['closing_message'] ?? null,
            'theme_color' => $data['theme_color'] ?? null,
            'status' => $data['status'] ?? 'draft',
            'published_at' => ($data['status'] ?? '') === 'published' ? date('Y-m-d H:i:s') : null,
        ]);

        return (int)$this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $allowed = [
            'template_id', 'title', 'slug', 'partner_one_name', 'partner_two_name',
            'cover_photo_path', 'event_date', 'event_time', 'timezone', 'venue_name',
            'venue_address', 'maps_url', 'description', 'closing_message', 'theme_color',
            'status', 'published_at'
        ];

        $fields = [];
        $params = ['id' => $id];

        foreach ($allowed as $field) {
            if (array_key_exists($field, $data)) {
                $fields[] = "{$field} = :{$field}";
                $params[$field] = $data[$field];
            }
        }

        if (empty($fields)) {
            return false;
        }

        $sql = "UPDATE invitations SET " . implode(', ', $fields) . " WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM invitations WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}
