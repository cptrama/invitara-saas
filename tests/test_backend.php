<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/vendor/autoload.php';

use Dotenv\Dotenv;
use App\Services\Database;
use App\Services\AuthService;
use App\Services\CsrfService;
use App\Services\Validator;
use App\Services\View;
use App\Repositories\UserRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\TemplateRepository;
use App\Repositories\InvitationRepository;
use App\Repositories\RSVPRepository;

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
echo " INVITARA - BACKEND & DATABASE TEST SUITE (ANGGOTA 1) \n";
echo "=======================================================\n\n";

// 1. Database Connection
it('PDO connects to MySQL successfully', function () {
    $pdo = Database::getConnection();
    assertTrue($pdo instanceof PDO, 'PDO instance established');
    $version = $pdo->query('SELECT VERSION()')->fetchColumn();
    assertTrue(!empty($version), 'MySQL version query executed');
});

// 2. Migration and Seeder Tables
it('all required tables and seeded data exist', function () {
    $pdo = Database::getConnection();
    $tables = ['users', 'categories', 'templates', 'invitations', 'invitation_photos', 'rsvps', 'migrations'];
    foreach ($tables as $t) {
        $check = $pdo->query("SHOW TABLES LIKE '{$t}'")->fetchColumn();
        assertEquals($t, $check, "Table '{$t}' must exist in database");
    }

    $userRepo = new UserRepository();
    $admin = $userRepo->findByEmail('admin@invitara.test');
    assertTrue($admin !== null, 'Admin user seeded');
    assertEquals('admin', $admin['role']);

    $templateRepo = new TemplateRepository();
    $templates = $templateRepo->allActive();
    assertTrue(count($templates) >= 3, 'At least 3 MVP templates must be seeded and active');
});

// 3. User Registration and Password Hashing
it('registers new user with hashed password and validates uniqueness', function () {
    $userRepo = new UserRepository();
    $authService = new AuthService($userRepo);

    $testEmail = 'tester_' . time() . '@example.com';
    $user = $authService->register([
        'name' => 'Tester User',
        'email' => $testEmail,
        'password' => 'secret123',
    ]);

    assertTrue($user['id'] > 0, 'User ID generated');
    assertEquals($testEmail, $user['email']);

    // Check duplicate email rejection
    $threw = false;
    try {
        $authService->register([
            'name' => 'Tester Duplicate',
            'email' => $testEmail,
            'password' => 'secret123',
        ]);
    } catch (Throwable $e) {
        $threw = true;
    }
    assertTrue($threw, 'Duplicate email should throw an exception');
});

// 4. User Login and Session
it('authenticates valid credentials and rejects invalid credentials', function () {
    $authService = new AuthService();

    $valid = $authService->login('admin@invitara.test', 'password123');
    assertTrue($valid !== null, 'Valid credentials should authenticate');
    assertEquals('Admin Invitara', $valid['name']);
    assertTrue(AuthService::check(), 'AuthService::check() should be true');
    assertEquals($valid['id'], AuthService::id());

    $invalid = $authService->login('admin@invitara.test', 'wrongpassword');
    assertTrue($invalid === null, 'Invalid credentials should return null');

    $authService->logout();
    assertFalse(AuthService::check(), 'AuthService::check() should be false after logout');
});

// 5. CSRF Protection
it('generates and validates CSRF tokens securely', function () {
    $token = CsrfService::getToken();
    assertTrue(strlen($token) === 64, 'CSRF token should be 64-char hex string');
    assertTrue(CsrfService::validate($token), 'Valid CSRF token should pass validation');
    assertFalse(CsrfService::validate('invalid_token_xyz'), 'Invalid token should fail validation');
    assertFalse(CsrfService::validate(null), 'Null token should fail validation');

    $rendered = CsrfService::renderField();
    assertTrue(str_contains($rendered, 'name="_csrf_token"'), 'Rendered field must have _csrf_token name');
    assertTrue(str_contains($rendered, $token), 'Rendered field must contain the token value');
});

// 6. Request Validator
it('validates rules properly with Indonesian messages', function () {
    $validator = Validator::make([
        'email' => 'invalid-email',
        'password' => '123',
    ], [
        'name' => 'required',
        'email' => 'required|email',
        'password' => 'required|min:6',
    ]);

    assertTrue($validator->fails(), 'Validator should fail on invalid inputs');
    $errors = $validator->errors();
    assertTrue(isset($errors['name']), 'Name should have required error');
    assertTrue(isset($errors['email']), 'Email should have format error');
    assertTrue(isset($errors['password']), 'Password should have min length error');
});

// 7. Data Isolation and Ownership Protection (AC-11)
it('enforces invitation ownership and prevents unauthorized access', function () {
    $invRepo = new InvitationRepository();

    // Create invitation for User 1 (Admin, id=1)
    $invId = $invRepo->create([
        'user_id' => 1,
        'template_id' => 1,
        'title' => 'Pernikahan Rama & Sinta',
        'slug' => 'rama-sinta-' . time(),
        'partner_one_name' => 'Rama',
        'partner_two_name' => 'Sinta',
        'event_date' => '2026-12-31',
        'timezone' => 'Asia/Jakarta',
        'status' => 'draft',
    ]);

    assertTrue($invId > 0, 'Invitation created');

    // Owner (id=1) check
    assertTrue($invRepo->checkOwnership($invId, 1), 'User 1 is the owner');

    // Non-owner (id=2 or id=999) check
    assertFalse($invRepo->checkOwnership($invId, 2), 'User 2 must NOT be owner of User 1 invitation');
    assertFalse($invRepo->checkOwnership($invId, 999), 'User 999 must NOT be owner');

    // Test slug uniqueness check
    $inv = $invRepo->findById($invId);
    assertFalse($invRepo->isSlugAvailable($inv['slug']), 'Used slug should not be available');
    assertTrue($invRepo->isSlugAvailable('random-slug-' . time()), 'New slug should be available');
    assertTrue($invRepo->isSlugAvailable($inv['slug'], $invId), 'Same slug is available when ignoring self id');

    // Clean up
    $invRepo->delete($invId);
});

// 8. RSVP Summary Calculation
it('calculates RSVP summaries and counts correctly', function () {
    $invRepo = new InvitationRepository();
    $rsvpRepo = new RSVPRepository();

    $invId = $invRepo->create([
        'user_id' => 1,
        'template_id' => 1,
        'title' => 'Pernikahan Test RSVP',
        'slug' => 'test-rsvp-' . time(),
        'partner_one_name' => 'Andi',
        'partner_two_name' => 'Rini',
        'event_date' => '2026-12-25',
        'status' => 'published',
    ]);

    // Add 2 attending RSVPs (1 with 2 guests, 1 with 3 guests) and 1 not_attending
    $rsvpRepo->create([
        'invitation_id' => $invId,
        'guest_name' => 'Tamu A',
        'attendance_status' => 'attending',
        'guest_count' => 2,
        'message' => 'Selamat ya!',
    ]);
    $rsvpRepo->create([
        'invitation_id' => $invId,
        'guest_name' => 'Tamu B',
        'attendance_status' => 'attending',
        'guest_count' => 3,
        'message' => 'Semoga bahagia selalu.',
    ]);
    $rsvpRepo->create([
        'invitation_id' => $invId,
        'guest_name' => 'Tamu C',
        'attendance_status' => 'not_attending',
        'guest_count' => 0,
        'message' => 'Maaf berhalangan hadir.',
    ]);

    $summary = $rsvpRepo->getSummaryByInvitationId($invId);
    assertEquals(3, $summary['total_responses'], 'Total responses should be 3');
    assertEquals(2, $summary['total_attending'], 'Attending responses should be 2');
    assertEquals(1, $summary['total_not_attending'], 'Not attending responses should be 1');
    assertEquals(5, $summary['total_attending_guests'], 'Total attending guest count should be 2 + 3 = 5');

    // Clean up (CASCADE delete should remove RSVPs too)
    $invRepo->delete($invId);
    assertEquals(0, $rsvpRepo->countByInvitation($invId), 'Cascading delete should clean related RSVPs');
});

// 9. Twig Template Rendering
it('renders Twig templates without syntax or loader errors', function () {
    $landingHtml = View::render('landing/index.twig', [
        'page_title' => 'Test Landing',
        'templates' => [],
    ]);
    assertTrue(str_contains($landingHtml, 'Invitara'), 'Landing page contains Invitara');

    $loginHtml = View::render('auth/login.twig', [
        'page_title' => 'Test Login',
    ]);
    assertTrue(str_contains($loginHtml, 'name="email"'), 'Login form contains email input');
    assertTrue(str_contains($loginHtml, '_csrf_token'), 'Login form contains CSRF token');

    $registerHtml = View::render('auth/register.twig', [
        'page_title' => 'Test Register',
    ]);
    assertTrue(str_contains($registerHtml, 'name="password_confirmation"'), 'Register form contains password confirmation');
});

// 10. Profile Management & Password Change (PRD 6.11)
it('updates user profile and validates password change security', function () {
    $userRepo = new UserRepository();
    $authService = new AuthService($userRepo);

    // Register a temp user for profile testing
    $testEmail = 'profile_test_' . time() . '@example.com';
    $user = $authService->register([
        'name' => 'Original Name',
        'email' => $testEmail,
        'password' => 'oldpassword123',
    ]);

    // Update name
    $userRepo->update($user['id'], ['name' => 'Updated Name']);
    $updatedUser = $userRepo->findById($user['id']);
    assertEquals('Updated Name', $updatedUser['name']);

    // Check old password verification
    $userWithPass = $userRepo->findByIdWithPassword($user['id']);
    assertTrue(password_verify('oldpassword123', $userWithPass['password']), 'Old password matches');
    assertFalse(password_verify('wrongpassword', $userWithPass['password']), 'Wrong password rejected');

    // Change password
    $newHash = password_hash('newpassword456', PASSWORD_DEFAULT);
    $userRepo->updatePassword($user['id'], $newHash);
    $userWithNewPass = $userRepo->findByIdWithPassword($user['id']);
    assertTrue(password_verify('newpassword456', $userWithNewPass['password']), 'New password verified');
});

// 11. UploadService Security & Validation (PRD 8.12)
it('UploadService validates MIME types and protects against directory traversal', function () {
    $uploader = new \App\Services\UploadService();

    // Test rejection of non-image file
    $tempTextFile = tempnam(sys_get_temp_dir(), 'test_php');
    file_put_contents($tempTextFile, '<?php echo "evil"; ?>');

    $rejected = false;
    try {
        $uploader->upload([
            'name' => 'evil.php',
            'type' => 'text/plain',
            'tmp_name' => $tempTextFile,
            'error' => UPLOAD_ERR_OK,
            'size' => filesize($tempTextFile),
        ], 'test');
    } catch (\RuntimeException $e) {
        $rejected = true;
    }
    @unlink($tempTextFile);
    assertTrue($rejected, 'Non-image file must be rejected by UploadService');

    // Test directory traversal prevention on delete
    assertFalse($uploader->delete('../../../config/database.php'), 'Directory traversal must be blocked');
    assertFalse($uploader->delete('/etc/passwd'), 'Non-upload path must be blocked');
});

// 12. Profile Twig Rendering
it('renders profile Twig template with Tabler UI elements and CSRF', function () {
    $profileHtml = View::render('dashboard/profile.twig', [
        'page_title' => 'Pengaturan Profil',
        'user' => [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'role' => 'user',
            'created_at' => '2026-01-01 10:00:00',
        ],
    ]);
    assertTrue(str_contains($profileHtml, 'Informasi Akun'), 'Profile page contains account info card');
    assertTrue(str_contains($profileHtml, 'Ubah Kata Sandi'), 'Profile page contains change password card');
    assertTrue(str_contains($profileHtml, '_csrf_token'), 'Profile forms contain CSRF token');
});

echo "\n=======================================================\n";
echo " TEST RESULTS: {$passed} PASSED, {$failed} FAILED\n";
echo "=======================================================\n\n";

if ($failed > 0) {
    exit(1);
}
