<?php

namespace App\Services;

use App\Repositories\InvitationRepository;

class InvitationService
{
    private InvitationRepository $invitationRepo;

    public function __construct(?InvitationRepository $invitationRepo = null)
    {
        $this->invitationRepo = $invitationRepo ?? new InvitationRepository();
    }

    /**
     * Generate a unique slug based on partners' names, title, or custom string
     */
    public function generateSlug(string $name1, string $name2 = '', ?int $ignoreId = null): string
    {
        $base = trim($name1);
        if (!empty($name2)) {
            $base .= ' ' . trim($name2);
        }

        // Clean string and convert to slug
        $slug = strtolower($base);
        // Replace non-alphanumeric with -
        $slug = preg_replace('~[^\pL\d]+~u', '-', $slug);
        // Transliterate
        if (function_exists('iconv')) {
            $trans = @iconv('utf-8', 'us-ascii//TRANSLIT', $slug);
            if ($trans !== false) {
                $slug = $trans;
            }
        }
        // Remove unwanted characters
        $slug = preg_replace('~[^-\w]+~', '', $slug);
        // Trim -
        $slug = trim($slug, '-');
        // Remove duplicate -
        $slug = preg_replace('~-+~', '-', $slug);

        if (empty($slug)) {
            $slug = 'undangan-pernikahan';
        }

        // Ensure slug availability
        $originalSlug = $slug;
        $counter = 2;

        while (!$this->invitationRepo->isSlugAvailable($slug, $ignoreId)) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    /**
     * Create a new draft invitation
     */
    public function createDraft(int $userId, array $data): int
    {
        $slug = !empty($data['slug']) 
            ? $this->generateSlug($data['slug'])
            : $this->generateSlug($data['partner_one_name'] ?? '', $data['partner_two_name'] ?? '');

        $insertData = [
            'user_id' => $userId,
            'template_id' => (int)$data['template_id'],
            'title' => trim($data['title']),
            'slug' => $slug,
            'partner_one_name' => trim($data['partner_one_name']),
            'partner_two_name' => trim($data['partner_two_name']),
            'cover_photo_path' => $data['cover_photo_path'] ?? null,
            'event_date' => $data['event_date'],
            'event_time' => !empty($data['event_time']) ? $data['event_time'] : null,
            'timezone' => !empty($data['timezone']) ? $data['timezone'] : 'Asia/Jakarta',
            'venue_name' => !empty($data['venue_name']) ? trim($data['venue_name']) : null,
            'venue_address' => !empty($data['venue_address']) ? trim($data['venue_address']) : null,
            'maps_url' => !empty($data['maps_url']) ? trim($data['maps_url']) : null,
            'description' => !empty($data['description']) ? trim($data['description']) : null,
            'closing_message' => !empty($data['closing_message']) ? trim($data['closing_message']) : null,
            'theme_color' => !empty($data['theme_color']) ? trim($data['theme_color']) : 'rose',
            'status' => 'draft',
        ];

        return $this->invitationRepo->create($insertData);
    }

    /**
     * Update an invitation
     */
    public function updateInvitation(int $id, array $data): bool
    {
        $updateData = [];
        $fields = [
            'template_id', 'title', 'slug', 'partner_one_name', 'partner_two_name',
            'cover_photo_path', 'event_date', 'event_time', 'timezone', 'venue_name',
            'venue_address', 'maps_url', 'description', 'closing_message', 'theme_color',
            'status', 'published_at'
        ];

        foreach ($fields as $field) {
            if (array_key_exists($field, $data)) {
                $updateData[$field] = $data[$field];
            }
        }

        return $this->invitationRepo->update($id, $updateData);
    }

    /**
     * Publish an invitation
     */
    public function publish(int $id): bool
    {
        return $this->invitationRepo->update($id, [
            'status' => 'published',
            'published_at' => date('Y-m-d H:i:s'),
        ]);
    }

    /**
     * Unpublish an invitation
     */
    public function unpublish(int $id): bool
    {
        return $this->invitationRepo->update($id, [
            'status' => 'unpublished',
        ]);
    }

    /**
     * Get published invitation by slug
     */
    public function getPublishedBySlug(string $slug): ?array
    {
        return $this->invitationRepo->getPublishedBySlug($slug);
    }
}
