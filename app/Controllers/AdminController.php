<?php

namespace App\Controllers;

use App\Repositories\CategoryRepository;
use App\Repositories\TemplateRepository;
use App\Repositories\UserRepository;
use App\Services\Database;
use App\Services\Flash;
use App\Services\View;
use Flight;
use PDO;

class AdminController
{
    private TemplateRepository $templateRepo;
    private CategoryRepository $categoryRepo;
    private UserRepository $userRepo;
    private PDO $db;

    public function __construct()
    {
        $this->templateRepo = new TemplateRepository();
        $this->categoryRepo = new CategoryRepository();
        $this->userRepo = new UserRepository();
        $this->db = Database::getConnection();
    }

    /**
     * Admin Dashboard - Monitoring Platform Usage & Overview
     */
    public function index(): void
    {
        // 1. Platform-wide Statistics
        $totalUsers = (int)$this->db->query("SELECT COUNT(*) FROM users")->fetchColumn();
        $totalInvitations = (int)$this->db->query("SELECT COUNT(*) FROM invitations")->fetchColumn();
        $publishedInvitations = (int)$this->db->query("SELECT COUNT(*) FROM invitations WHERE status = 'published'")->fetchColumn();
        $draftInvitations = (int)$this->db->query("SELECT COUNT(*) FROM invitations WHERE status = 'draft'")->fetchColumn();
        $totalRsvps = (int)$this->db->query("SELECT COUNT(*) FROM rsvps")->fetchColumn();
        $totalGuests = (int)$this->db->query("SELECT COALESCE(SUM(guest_count), 0) FROM rsvps WHERE attendance_status = 'attending'")->fetchColumn();

        // 2. Data Lists
        $users = $this->userRepo->all();
        $templates = $this->templateRepo->all();
        $categories = $this->categoryRepo->all();

        echo View::render('admin/index.twig', [
            'page_title' => 'Panel Admin & Monitoring Platform',
            'stats' => [
                'total_users' => $totalUsers,
                'total_invitations' => $totalInvitations,
                'published_invitations' => $publishedInvitations,
                'draft_invitations' => $draftInvitations,
                'total_rsvps' => $totalRsvps,
                'total_guests' => $totalGuests,
            ],
            'users' => $users,
            'templates' => $templates,
            'categories' => $categories,
        ]);
    }

    /**
     * Toggle Template Active / Inactive Status
     */
    public function toggleTemplate(int $id): void
    {
        $template = $this->templateRepo->findById($id);
        if (!$template) {
            Flash::set('error', 'Template tidak ditemukan.');
            Flight::redirect('/admin');
            return;
        }

        $newStatus = (int)$template['is_active'] === 1 ? 0 : 1;
        $this->templateRepo->update($id, [
            'is_active' => $newStatus,
        ]);

        $statusLabel = $newStatus === 1 ? 'diaktifkan' : 'dinonaktifkan';
        Flash::set('success', "Template '{$template['name']}' berhasil {$statusLabel}.");
        Flight::redirect('/admin');
    }
}
