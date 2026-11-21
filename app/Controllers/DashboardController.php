<?php

namespace App\Controllers;

use App\Repositories\InvitationRepository;
use App\Services\AuthService;
use App\Services\View;

class DashboardController
{
    private InvitationRepository $invitationRepo;

    public function __construct()
    {
        $this->invitationRepo = new InvitationRepository();
    }

    public function index(): void
    {
        $userId = AuthService::id();
        $counts = $this->invitationRepo->countByUserId($userId);
        $invitations = $this->invitationRepo->getByUserId($userId);

        echo View::render('dashboard/index.twig', [
            'page_title' => 'Dashboard',
            'stats' => $counts,
            'invitations' => $invitations,
        ]);
    }
}
