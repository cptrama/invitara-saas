<?php

namespace App\Controllers;

use App\Repositories\InvitationRepository;
use App\Repositories\RSVPRepository;
use App\Repositories\TemplateRepository;
use App\Services\AuthService;
use App\Services\Flash;
use App\Services\InvitationService;
use App\Services\Validator;
use App\Services\View;
use Flight;

class InvitationController
{
    private InvitationRepository $invitationRepo;
    private TemplateRepository $templateRepo;
    private RSVPRepository $rsvpRepo;
    private InvitationService $invitationService;

    public function __construct(
        $invitationRepo = null,
        $templateRepo = null,
        $rsvpRepo = null,
        $invitationService = null
    ) {
        $this->invitationRepo = ($invitationRepo instanceof InvitationRepository) ? $invitationRepo : new InvitationRepository();
        $this->templateRepo = ($templateRepo instanceof TemplateRepository) ? $templateRepo : new TemplateRepository();
        $this->rsvpRepo = ($rsvpRepo instanceof RSVPRepository) ? $rsvpRepo : new RSVPRepository();
        $this->invitationService = ($invitationService instanceof InvitationService) ? $invitationService : new InvitationService($this->invitationRepo);
    }

    /**
     * Display listing of user's invitations
     * GET /dashboard/invitations
     */
    public function index(): void
    {
        $userId = AuthService::id();
        if (!$userId) {
            Flight::redirect('/login');
            return;
        }
        $invitations = $this->invitationRepo->getByUserId($userId);

        echo View::render('invitations/index.twig', [
            'page_title' => 'Daftar Undangan Saya',
            'invitations' => $invitations,
        ]);
    }

    /**
     * Display template picker and creation form
     * GET /dashboard/invitations/create
     */
    public function create(): void
    {
        $templates = $this->templateRepo->allActive();
        $selectedTemplateId = (int)(Flight::request()->query->template_id ?? 0);

        if ($selectedTemplateId === 0 && !empty($templates)) {
            $selectedTemplateId = (int)$templates[0]['id'];
        }

        echo View::render('invitations/create.twig', [
            'page_title' => 'Buat Undangan Baru',
            'templates' => $templates,
            'selected_template_id' => $selectedTemplateId,
        ]);
    }

    /**
     * Store new draft invitation
     * POST /dashboard/invitations
     */
    public function store(): void
    {
        $userId = AuthService::id();
        if (!$userId) {
            Flight::redirect('/login');
            return;
        }
        $data = Flight::request()->data->getData();

        $rules = [
            'template_id' => 'required|integer',
            'title' => 'required|min:3|max:150',
            'partner_one_name' => 'required|max:100',
            'partner_two_name' => 'required|max:100',
            'event_date' => 'required|date',
            'event_time' => 'time',
            'timezone' => 'in:Asia/Jakarta,Asia/Makassar,Asia/Jayapura',
            'maps_url' => 'url',
            'theme_color' => 'in:rose,emerald,gold,classic,blue,purple,tinta,pualam,malam,melati,sirih,mawar,kobalt,kunyit,lumut',
            'story' => 'max:3000',
            'contact' => 'max:100',
            'show_countdown' => 'in:0,1',
        ];

        if (!empty($data['slug'])) {
            $rules['slug'] = 'slug';
        }

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            Flash::set('error', $validator->firstError());
            $_SESSION['_old_input'] = $data;
            Flight::redirect('/dashboard/invitations/create');
            return;
        }

        // Validate template existence
        $template = $this->templateRepo->findById((int)$data['template_id']);
        if (!$template || !$template['is_active']) {
            Flash::set('error', 'Template pernikahan yang dipilih tidak valid.');
            $_SESSION['_old_input'] = $data;
            Flight::redirect('/dashboard/invitations/create');
            return;
        }

        $invitationId = $this->invitationService->createDraft($userId, $data);

        unset($_SESSION['_old_input']);
        Flash::set('success', 'Undangan berhasil dibuat sebagai draft.');
        Flight::redirect('/dashboard/invitations/' . $invitationId . '/edit');
    }

    /**
     * Show form-based editor
     * GET /dashboard/invitations/@id/edit
     */
    public function edit($id): void
    {
        $id = (int)$id;
        $invitation = $this->invitationRepo->findById($id);
        if (!$invitation) {
            Flash::set('error', 'Undangan tidak ditemukan.');
            Flight::redirect('/dashboard/invitations');
            return;
        }

        $templates = $this->templateRepo->allActive();

        echo View::render('invitations/edit.twig', [
            'page_title' => 'Edit Undangan - ' . $invitation['title'],
            'invitation' => $invitation,
            'templates' => $templates,
        ]);
    }

    /**
     * Update invitation details
     * POST /dashboard/invitations/@id/edit
     */
    public function update($id): void
    {
        $id = (int)$id;
        $data = Flight::request()->data->getData();

        $rules = [
            'template_id' => 'required|integer',
            'title' => 'required|min:3|max:150',
            'slug' => 'required|slug',
            'partner_one_name' => 'required|max:100',
            'partner_two_name' => 'required|max:100',
            'event_date' => 'required|date',
            'event_time' => 'time',
            'timezone' => 'in:Asia/Jakarta,Asia/Makassar,Asia/Jayapura',
            'maps_url' => 'url',
            'theme_color' => 'in:rose,emerald,gold,classic,blue,purple,tinta,pualam,malam,melati,sirih,mawar,kobalt,kunyit,lumut',
            'story' => 'max:3000',
            'contact' => 'max:100',
            'show_countdown' => 'in:0,1',
        ];

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            Flash::set('error', $validator->firstError());
            $_SESSION['_old_input'] = $data;
            Flight::redirect('/dashboard/invitations/' . $id . '/edit');
            return;
        }

        // Check slug uniqueness
        $slug = strtolower(trim($data['slug']));
        if (!$this->invitationRepo->isSlugAvailable($slug, $id)) {
            Flash::set('error', 'Slug URL sudah digunakan oleh undangan lain. Silakan gunakan slug berbeda.');
            $_SESSION['_old_input'] = $data;
            Flight::redirect('/dashboard/invitations/' . $id . '/edit');
            return;
        }

        $updateData = [
            'template_id' => (int)$data['template_id'],
            'title' => trim($data['title']),
            'slug' => $slug,
            'partner_one_name' => trim($data['partner_one_name']),
            'partner_two_name' => trim($data['partner_two_name']),
            'event_date' => $data['event_date'],
            'event_time' => !empty($data['event_time']) ? $data['event_time'] : null,
            'timezone' => !empty($data['timezone']) ? $data['timezone'] : 'Asia/Jakarta',
            'venue_name' => !empty($data['venue_name']) ? trim($data['venue_name']) : null,
            'venue_address' => !empty($data['venue_address']) ? trim($data['venue_address']) : null,
            'maps_url' => !empty($data['maps_url']) ? trim($data['maps_url']) : null,
            'description' => !empty($data['description']) ? trim($data['description']) : null,
            'closing_message' => !empty($data['closing_message']) ? trim($data['closing_message']) : null,
            'story' => !empty($data['story']) ? trim($data['story']) : null,
            'contact' => !empty($data['contact']) ? trim($data['contact']) : null,
            'show_countdown' => isset($data['show_countdown']) ? (int)$data['show_countdown'] : 1,
            'theme_color' => !empty($data['theme_color']) ? trim($data['theme_color']) : 'rose',
        ];

        $this->invitationService->updateInvitation($id, $updateData);

        unset($_SESSION['_old_input']);
        Flash::set('success', 'Data undangan berhasil diperbarui.');
        Flight::redirect('/dashboard/invitations/' . $id . '/edit');
    }

    /**
     * Delete invitation
     * POST /dashboard/invitations/@id/delete
     */
    public function delete($id): void
    {
        $id = (int)$id;
        $this->invitationRepo->delete($id);

        Flash::set('success', 'Undangan berhasil dihapus.');
        Flight::redirect('/dashboard/invitations');
    }

    /**
     * Publish invitation
     * POST /dashboard/invitations/@id/publish
     */
    public function publish($id): void
    {
        $id = (int)$id;
        $this->invitationService->publish($id);

        Flash::set('success', 'Undangan berhasil dipublikasikan! Tautan publik siap dibagikan.');
        Flight::redirect('/dashboard/invitations/' . $id . '/edit');
    }

    /**
     * Unpublish invitation
     * POST /dashboard/invitations/@id/unpublish
     */
    public function unpublish($id): void
    {
        $id = (int)$id;
        $this->invitationService->unpublish($id);

        Flash::set('success', 'Publikasi undangan dibatalkan. Undangan kini dinonaktifkan dari publik.');
        Flight::redirect('/dashboard/invitations/' . $id . '/edit');
    }

    /**
     * Display public invitation
     * GET /i/@slug
     */
    public function showPublic(string $slug): void
    {
        $invitation = $this->invitationRepo->findBySlug($slug);

        if (!$invitation) {
            Flight::response()->status(404);
            echo View::render('layouts/404.twig', [
                'page_title' => '404 - Undangan Tidak Ditemukan',
            ]);
            return;
        }

        // PRD 8.6 Business Rule: Public page ONLY accessible when status is 'published'
        if ($invitation['status'] !== 'published') {
            Flight::response()->status(404);
            echo View::render('invitations/public/inactive.twig', [
                'page_title' => 'Undangan Belum Dipublikasikan',
                'invitation' => $invitation,
            ]);
            return;
        }

        $rsvps = $this->rsvpRepo->getByInvitationId($invitation['id']);

        $viewKey = $invitation['template_view_key'] ?? 'minimal';
        $viewMap = [
            'minimal' => 'invitations/public/minimal.twig',
            'floral'  => 'invitations/public/floral.twig',
            'modern'  => 'invitations/public/modern.twig',
        ];

        $viewFile = $viewMap[$viewKey] ?? 'invitations/public/minimal.twig';

        echo View::render($viewFile, [
            'page_title' => $invitation['title'] . ' | Invitara',
            'invitation' => $invitation,
            'rsvps' => $rsvps,
        ]);
    }
}
