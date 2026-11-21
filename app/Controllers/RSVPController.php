<?php

namespace App\Controllers;

use App\Repositories\InvitationRepository;
use App\Repositories\RSVPRepository;
use App\Services\Flash;
use App\Services\Validator;
use App\Services\View;
use Flight;

class RSVPController
{
    private RSVPRepository $rsvpRepo;
    private InvitationRepository $invitationRepo;

    public function __construct(
        $rsvpRepo = null,
        $invitationRepo = null
    ) {
        $this->rsvpRepo = ($rsvpRepo instanceof RSVPRepository) ? $rsvpRepo : new RSVPRepository();
        $this->invitationRepo = ($invitationRepo instanceof InvitationRepository) ? $invitationRepo : new InvitationRepository();
    }

    /**
     * Submit guest RSVP from public invitation page
     * POST /i/@slug/rsvp
     */
    public function storePublic(string $slug): void
    {
        $invitation = $this->invitationRepo->findBySlug($slug);

        if (!$invitation || $invitation['status'] !== 'published') {
            Flight::halt(404, 'Undangan tidak ditemukan atau belum dipublikasikan.');
            return;
        }

        $data = Flight::request()->data->getData();

        $rules = [
            'guest_name' => 'required|max:120',
            'attendance_status' => 'required|in:attending,not_attending',
            'message' => 'max:1000',
        ];

        if (($data['attendance_status'] ?? '') === 'attending') {
            $rules['guest_count'] = 'required|integer|min:1|max:20';
        }

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            Flash::set('error', $validator->firstError());
            $_SESSION['_old_input'] = $data;
            Flight::redirect('/i/' . $slug . '#rsvp');
            return;
        }

        $attendanceStatus = $data['attendance_status'];
        $guestCount = ($attendanceStatus === 'attending') ? max(1, (int)($data['guest_count'] ?? 1)) : 0;

        $this->rsvpRepo->create([
            'invitation_id' => (int)$invitation['id'],
            'guest_name' => trim($data['guest_name']),
            'attendance_status' => $attendanceStatus,
            'guest_count' => $guestCount,
            'message' => !empty($data['message']) ? trim($data['message']) : null,
        ]);

        unset($_SESSION['_old_input']);
        Flash::set('success', 'Terima kasih! Konfirmasi kehadiran dan ucapan Anda telah berhasil disimpan.');
        Flight::redirect('/i/' . $slug . '#rsvp');
    }

    /**
     * Display guest list and RSVP summary for invitation owner
     * GET /dashboard/invitations/@id/rsvps
     */
    public function index($id): void
    {
        $id = (int)$id;
        $invitation = $this->invitationRepo->findById($id);
        if (!$invitation) {
            Flash::set('error', 'Undangan tidak ditemukan.');
            Flight::redirect('/dashboard/invitations');
            return;
        }

        $rsvps = $this->rsvpRepo->getByInvitationId($id);
        $summary = $this->rsvpRepo->getSummaryByInvitationId($id);

        echo View::render('invitations/rsvps.twig', [
            'page_title' => 'Manajemen RSVP - ' . $invitation['title'],
            'invitation' => $invitation,
            'rsvps' => $rsvps,
            'summary' => $summary,
        ]);
    }

    /**
     * Delete an invalid or spam RSVP entry
     * POST /dashboard/invitations/@id/rsvps/@rsvp_id/delete
     */
    public function delete($id, $rsvp_id): void
    {
        $id = (int)$id;
        $rsvp_id = (int)$rsvp_id;
        $rsvp = $this->rsvpRepo->findById($rsvp_id);

        if (!$rsvp || (int)$rsvp['invitation_id'] !== $id) {
            Flash::set('error', 'Entri RSVP tidak ditemukan atau tidak valid.');
            Flight::redirect('/dashboard/invitations/' . $id . '/rsvps');
            return;
        }

        $this->rsvpRepo->delete($rsvp_id);

        Flash::set('success', 'Entri RSVP berhasil dihapus.');
        Flight::redirect('/dashboard/invitations/' . $id . '/rsvps');
    }
}
