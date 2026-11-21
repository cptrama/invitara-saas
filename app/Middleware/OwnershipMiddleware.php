<?php

namespace App\Middleware;

use App\Repositories\InvitationRepository;
use App\Services\AuthService;
use App\Services\Flash;
use Flight;

class OwnershipMiddleware
{
    private InvitationRepository $invitationRepo;

    public function __construct()
    {
        $this->invitationRepo = new InvitationRepository();
    }

    /**
     * Check if the authenticated user owns the invitation specified in route params
     */
    public function before(array $params = []): void
    {
        $userId = AuthService::id();
        if (!$userId) {
            Flash::set('error', 'Silakan login terlebih dahulu.');
            Flight::redirect('/login');
            exit;
        }

        // Get invitation id from route parameters
        $invitationId = $params['id'] ?? Flight::request()->params['id'] ?? null;
        if (!$invitationId) {
            // Check if passed via GET / POST
            $invitationId = Flight::request()->query->id ?? Flight::request()->data->id ?? null;
        }

        if (!$invitationId) {
            Flight::halt(400, 'Bad Request: ID undangan tidak ditemukan.');
            exit;
        }

        $invitationId = (int)$invitationId;
        $invitation = $this->invitationRepo->findById($invitationId);

        if (!$invitation) {
            Flash::set('error', 'Undangan tidak ditemukan.');
            Flight::redirect('/dashboard/invitations');
            exit;
        }

        if ((int)$invitation['user_id'] !== $userId) {
            Flash::set('error', 'Akses ditolak: Anda tidak memiliki hak untuk mengelola undangan ini.');
            Flight::halt(403, '403 Forbidden - Anda tidak berhak mengakses undangan ini.');
            exit;
        }
    }
}
