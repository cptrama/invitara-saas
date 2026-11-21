<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/vendor/autoload.php';

use Dotenv\Dotenv;
use App\Services\Database;
use App\Services\AuthService;
use App\Services\InvitationService;
use App\Services\Validator;
use App\Repositories\UserRepository;
use App\Repositories\TemplateRepository;
use App\Repositories\InvitationRepository;
use App\Repositories\RSVPRepository;
use App\Middleware\OwnershipMiddleware;

// Load environment
if (file_exists(dirname(__DIR__) . '/.env')) {
    $dotenv = Dotenv::createImmutable(dirname(__DIR__));
    $dotenv->load();
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$passed = 0;
$failed = 0;

function it(string $description, callable $fn): void {
    global $passed, $failed;
    echo "• Testing: {$description} ... ";
    try {
        $fn();
        echo "PASSED\n";
        $passed++;
    } catch (Throwable $e) {
        echo "FAILED\n  --> " . $e->getMessage() . " (" . $e->getFile() . ":" . $e->getLine() . ")\n";
        $failed++;
    }
}

function assertEquals($expected, $actual, string $message = ''): void {
    if ($expected !== $actual) {
        throw new RuntimeException($message ?: "Expected " . var_export($expected, true) . ", got " . var_export($actual, true));
    }
}

function assertTrue($condition, string $message = ''): void {
    if (!$condition) {
        throw new RuntimeException($message ?: "Expected true, got false");
    }
}

function assertFalse($condition, string $message = ''): void {
    if ($condition) {
        throw new RuntimeException($message ?: "Expected false, got true");
    }
}

echo "\n=======================================================\n";
echo " INVITARA - INVITATION & RSVP FLOW TEST SUITE (ANGGOTA 3) \n";
echo "=======================================================\n\n";

// Helper variables
$invRepo = new InvitationRepository();
$rsvpRepo = new RSVPRepository();
$templateRepo = new TemplateRepository();
$userRepo = new UserRepository();
$invService = new InvitationService($invRepo);

// Ensure test users exist
$user1 = $userRepo->findByEmail('admin@invitara.test');
$user2 = $userRepo->findByEmail('budi@invitara.test');
if (!$user1 || !$user2) {
    throw new RuntimeException("Test users not found. Run database/seed.php first.");
}
$userId1 = (int)$user1['id'];
$userId2 = (int)$user2['id'];

$activeTemplates = $templateRepo->allActive();
assertTrue(!empty($activeTemplates), "Active templates must exist");
$templateId = (int)$activeTemplates[0]['id'];

$createdInvitationIds = [];

// 1. Alur pembuatan draft undangan berhasil
it('creates draft invitation successfully with default draft status and all fields', function () use (
    $invService, $invRepo, $userId1, $templateId, &$createdInvitationIds
) {
    $timestamp = time();
    $data = [
        'template_id' => $templateId,
        'title' => 'Pernikahan Budi & Ani ' . $timestamp,
        'partner_one_name' => 'Budi',
        'partner_two_name' => 'Ani',
        'event_date' => '2026-12-28',
        'event_time' => '10:00',
        'timezone' => 'Asia/Jakarta',
        'venue_name' => 'Gedung Serbaguna Citra',
        'venue_address' => 'Jl. Kebon Jeruk No. 10, Jakarta Barat',
        'maps_url' => 'https://maps.google.com/?q=Jakarta',
        'description' => 'Kami pertama kali bertemu di bangku kuliah...',
        'closing_message' => 'Merupakan kehormatan bagi kami atas kehadiran Bapak/Ibu.',
        'theme_color' => 'rose',
    ];

    $invId = $invService->createDraft($userId1, $data);
    assertTrue($invId > 0, 'Draft invitation ID should be positive integer');
    $createdInvitationIds[] = $invId;

    $inv = $invRepo->findById($invId);
    assertTrue($inv !== null, 'Created draft must exist in database');
    assertEquals('draft', $inv['status'], 'Initial status must be draft');
    assertEquals($userId1, (int)$inv['user_id'], 'Owner must match creator user ID');
    assertEquals('Budi', $inv['partner_one_name']);
    assertEquals('Ani', $inv['partner_two_name']);
    assertEquals('Gedung Serbaguna Citra', $inv['venue_name']);
    assertEquals('rose', $inv['theme_color']);
    assertTrue(!empty($inv['slug']), 'Slug must be automatically generated');
});

// 2. Slug unik bekerja tanpa bentrok
it('generates unique slugs and handles collisions without crashing', function () use (
    $invService, $invRepo, $userId1, $templateId, &$createdInvitationIds
) {
    // Generate base slug for "Andi & Siti"
    $slug1 = $invService->generateSlug('Andi', 'Siti');
    $invId1 = $invRepo->create([
        'user_id' => $userId1,
        'template_id' => $templateId,
        'title' => 'The Wedding of Andi & Siti 1',
        'slug' => $slug1,
        'partner_one_name' => 'Andi',
        'partner_two_name' => 'Siti',
        'event_date' => '2026-11-20',
        'status' => 'draft',
    ]);
    $createdInvitationIds[] = $invId1;

    // Second invitation with same couple names
    $slug2 = $invService->generateSlug('Andi', 'Siti');
    assertTrue($slug2 !== $slug1, 'Second slug must not equal first slug');
    assertTrue(str_starts_with($slug2, $slug1 . '-'), 'Second slug must have a unique suffix');

    $invId2 = $invRepo->create([
        'user_id' => $userId1,
        'template_id' => $templateId,
        'title' => 'The Wedding of Andi & Siti 2',
        'slug' => $slug2,
        'partner_one_name' => 'Andi',
        'partner_two_name' => 'Siti',
        'event_date' => '2026-11-20',
        'status' => 'draft',
    ]);
    $createdInvitationIds[] = $invId2;

    // Third invitation with same couple names
    $slug3 = $invService->generateSlug('Andi', 'Siti');
    assertTrue($slug3 !== $slug1 && $slug3 !== $slug2, 'Third slug must be unique from both');

    // Ignore self id check
    $slugSelf = $invService->generateSlug('Andi', 'Siti', $invId1);
    assertEquals($slug1, $slugSelf, 'Generating slug ignoring own ID should allow reusing the same slug');
});

// 3. Undangan draft tidak bisa dibuka lewat URL publik /i/{slug} (PRD 8.6)
it('enforces that draft and unpublished invitations cannot be accessed publicly', function () use (
    $invService, $invRepo, $userId1, $templateId, &$createdInvitationIds
) {
    $slug = 'public-rule-test-' . time();
    $invId = $invRepo->create([
        'user_id' => $userId1,
        'template_id' => $templateId,
        'title' => 'Public Access Rule Test',
        'slug' => $slug,
        'partner_one_name' => 'Hasan',
        'partner_two_name' => 'Fatimah',
        'event_date' => '2026-12-12',
        'status' => 'draft',
    ]);
    $createdInvitationIds[] = $invId;

    // Rule 1: getPublishedBySlug must return null when status is 'draft'
    $publicAccessDraft = $invRepo->getPublishedBySlug($slug);
    assertEquals(null, $publicAccessDraft, 'Draft invitation must NOT be returned by getPublishedBySlug');

    // Publish invitation
    $published = $invService->publish($invId);
    assertTrue($published, 'Publishing invitation must succeed');

    $invPublished = $invRepo->findById($invId);
    assertEquals('published', $invPublished['status']);
    assertTrue(!empty($invPublished['published_at']), 'published_at timestamp must be recorded');

    // Rule 2: getPublishedBySlug must return invitation when status is 'published'
    $publicAccessPublished = $invRepo->getPublishedBySlug($slug);
    assertTrue($publicAccessPublished !== null, 'Published invitation MUST be accessible');
    assertEquals($invId, (int)$publicAccessPublished['id']);

    // Unpublish invitation
    $unpublished = $invService->unpublish($invId);
    assertTrue($unpublished, 'Unpublishing invitation must succeed');

    $invUnpublished = $invRepo->findById($invId);
    assertEquals('unpublished', $invUnpublished['status']);

    // Rule 3: getPublishedBySlug must return null when status is 'unpublished'
    $publicAccessUnpublished = $invRepo->getPublishedBySlug($slug);
    assertEquals(null, $publicAccessUnpublished, 'Unpublished invitation must NOT be returned by getPublishedBySlug');
});

// 4. Tamu berhasil mengirim RSVP dan ringkasan statistik di dashboard akurat
it('records guest RSVPs and calculates accurate dashboard summary stats', function () use (
    $invRepo, $rsvpRepo, $userId1, $templateId, &$createdInvitationIds
) {
    $invId = $invRepo->create([
        'user_id' => $userId1,
        'template_id' => $templateId,
        'title' => 'RSVP Integration Test',
        'slug' => 'rsvp-stat-test-' . time(),
        'partner_one_name' => 'Fajar',
        'partner_two_name' => 'Rina',
        'event_date' => '2026-12-25',
        'status' => 'published',
    ]);
    $createdInvitationIds[] = $invId;

    // 1. Initial summary should be zeros
    $initialSummary = $rsvpRepo->getSummaryByInvitationId($invId);
    assertEquals(0, $initialSummary['total_responses']);
    assertEquals(0, $initialSummary['total_attending']);
    assertEquals(0, $initialSummary['total_not_attending']);
    assertEquals(0, $initialSummary['total_attending_guests']);

    // 2. Guest 1 attends with 2 guests
    $r1 = $rsvpRepo->create([
        'invitation_id' => $invId,
        'guest_name' => 'Tamu Pertama (Budi)',
        'attendance_status' => 'attending',
        'guest_count' => 2,
        'message' => 'Selamat menempuh hidup baru!',
    ]);
    assertTrue($r1 > 0, 'First RSVP created');

    // 3. Guest 2 attends with 4 guests
    $r2 = $rsvpRepo->create([
        'invitation_id' => $invId,
        'guest_name' => 'Tamu Kedua (Keluarga Santoso)',
        'attendance_status' => 'attending',
        'guest_count' => 4,
        'message' => 'Semoga bahagia selalu.',
    ]);
    assertTrue($r2 > 0, 'Second RSVP created');

    // 4. Guest 3 is not attending (guest_count should be automatically 0)
    $r3 = $rsvpRepo->create([
        'invitation_id' => $invId,
        'guest_name' => 'Tamu Ketiga (Rian)',
        'attendance_status' => 'not_attending',
        'guest_count' => 3, // should be forced to 0
        'message' => 'Maaf belum bisa hadir, selamat ya!',
    ]);
    assertTrue($r3 > 0, 'Third RSVP created');

    // Verify summary: 3 responses, 2 attending, 1 not attending, 6 guests (2+4)
    $summary = $rsvpRepo->getSummaryByInvitationId($invId);
    assertEquals(3, $summary['total_responses'], 'Total responses must be 3');
    assertEquals(2, $summary['total_attending'], 'Attending responses must be 2');
    assertEquals(1, $summary['total_not_attending'], 'Not attending responses must be 1');
    assertEquals(6, $summary['total_attending_guests'], 'Total attending guest count must be 6 (2 + 4)');

    // Verify guest list retrieval
    $list = $rsvpRepo->getByInvitationId($invId);
    assertEquals(3, count($list), 'List must return 3 RSVP records');

    // Test deleting single RSVP entry (e.g. spam by owner)
    $deleted = $rsvpRepo->delete($r2);
    assertTrue($deleted, 'RSVP deletion must succeed');

    $updatedSummary = $rsvpRepo->getSummaryByInvitationId($invId);
    assertEquals(2, $updatedSummary['total_responses'], 'Total responses updated to 2');
    assertEquals(1, $updatedSummary['total_attending'], 'Attending updated to 1');
    assertEquals(2, $updatedSummary['total_attending_guests'], 'Guest count updated to 2');
});

// 5. Pengguna lain tidak bisa mengedit undangan akun berbeda (403 Forbidden / Data Isolation)
it('prohibits non-owner users from managing invitations belonging to other accounts', function () use (
    $invRepo, $userId1, $userId2, $templateId, &$createdInvitationIds
) {
    // Create invitation owned by User 1
    $invId = $invRepo->create([
        'user_id' => $userId1,
        'template_id' => $templateId,
        'title' => 'Private Invitation of User 1',
        'slug' => 'user1-private-' . time(),
        'partner_one_name' => 'User1-A',
        'partner_two_name' => 'User1-B',
        'event_date' => '2026-11-30',
        'status' => 'draft',
    ]);
    $createdInvitationIds[] = $invId;

    // Check ownership method directly
    assertTrue($invRepo->checkOwnership($invId, $userId1), 'User 1 is verified as owner');
    assertFalse($invRepo->checkOwnership($invId, $userId2), 'User 2 MUST NOT be verified as owner');
    assertFalse($invRepo->checkOwnership($invId, 9999), 'Non-existent user MUST NOT be owner');

    // Simulate session check as User 2 trying to access User 1's invitation
    $_SESSION['user'] = [
        'id' => $userId2,
        'name' => 'User Two',
        'email' => 'user@invitara.test',
        'role' => 'user',
    ];

    $invitation = $invRepo->findById($invId);
    assertTrue($invitation !== null);
    assertEquals($userId1, (int)$invitation['user_id']);

    $isForbidden = ((int)$invitation['user_id'] !== (int)$_SESSION['user']['id']);
    assertTrue($isForbidden, 'Attempt by User 2 on User 1 resource must trigger 403 Forbidden condition');

    // Restore session
    unset($_SESSION['user']);
});

// 6. Validasi integritas relasi ON DELETE CASCADE
it('automatically removes all attached RSVPs when an invitation is deleted', function () use (
    $invRepo, $rsvpRepo, $userId1, $templateId
) {
    $invId = $invRepo->create([
        'user_id' => $userId1,
        'template_id' => $templateId,
        'title' => 'Cascade Delete Test',
        'slug' => 'cascade-test-' . time(),
        'partner_one_name' => 'Cas1',
        'partner_two_name' => 'Cas2',
        'event_date' => '2026-12-01',
        'status' => 'draft',
    ]);

    $rsvpId = $rsvpRepo->create([
        'invitation_id' => $invId,
        'guest_name' => 'Cascade Guest',
        'attendance_status' => 'attending',
        'guest_count' => 1,
    ]);
    assertTrue($rsvpId > 0);

    // Delete parent invitation
    $invRepo->delete($invId);

    // Verify RSVP was removed by ON DELETE CASCADE
    $rsvpCheck = $rsvpRepo->findById($rsvpId);
    assertEquals(null, $rsvpCheck, 'RSVP must be deleted automatically when parent invitation is deleted');
});

// Cleanup test records
foreach ($createdInvitationIds as $id) {
    $invRepo->delete($id);
}

echo "\n=======================================================\n";
echo " TEST RESULTS: {$passed} PASSED, {$failed} FAILED\n";
echo "=======================================================\n\n";

if ($failed > 0) {
    exit(1);
}
