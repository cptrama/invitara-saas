<?php

require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->safeLoad();

// Mock session
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
$_SESSION['user'] = [
    'id' => 1,
    'role' => 'user',
    'name' => 'Budi Santoso',
    'email' => 'budi@example.com'
];
$_SESSION['user_id'] = 1;
$_SESSION['user_role'] = 'user';

echo "\n--- TESTING CONTROLLERS DISPATCH (FLIGHT COMPATIBILITY) ---\n";

// Test Flight engine passing to controller
$engine = Flight::app();

// Test 1: InvitationController instantiation with Engine
try {
    $invController = new App\Controllers\InvitationController($engine);
    echo "✓ [PASS] InvitationController instantiates with Flight Engine\n";
} catch (Throwable $e) {
    echo "✗ [FAIL] InvitationController instantiation: " . $e->getMessage() . "\n";
    exit(1);
}

// Test 2: RSVPController instantiation with Engine
try {
    $rsvpController = new App\Controllers\RSVPController($engine);
    echo "✓ [PASS] RSVPController instantiates with Flight Engine\n";
} catch (Throwable $e) {
    echo "✗ [FAIL] RSVPController instantiation: " . $e->getMessage() . "\n";
    exit(1);
}

// Test 3: Render create page
try {
    ob_start();
    $invController->create();
    $html = ob_get_clean();
    if (strlen($html) > 500 && stripos($html, 'undangan') !== false) {
        echo "✓ [PASS] InvitationController::create() renders HTML successfully (" . strlen($html) . " bytes)\n";
    } else {
        echo "✗ [FAIL] Unexpected output from InvitationController::create()\n";
        exit(1);
    }
} catch (Throwable $e) {
    ob_end_clean();
    echo "✗ [FAIL] InvitationController::create() threw: " . $e->getMessage() . "\n";
    exit(1);
}

// Test 4: Render index page
try {
    ob_start();
    $invController->index();
    $html = ob_get_clean();
    if (strlen($html) > 500 && stripos($html, 'undangan') !== false) {
        echo "✓ [PASS] InvitationController::index() renders HTML successfully (" . strlen($html) . " bytes)\n";
    } else {
        echo "✗ [FAIL] Unexpected output from InvitationController::index()\n";
        exit(1);
    }
} catch (Throwable $e) {
    ob_end_clean();
    echo "✗ [FAIL] InvitationController::index() threw: " . $e->getMessage() . "\n";
    exit(1);
}

// Test 5: Test edit with string ID (FlightPHP route param compatibility)
try {
    $pdo = App\Services\Database::getConnection();
    $row = $pdo->query("SELECT id FROM invitations LIMIT 1")->fetch(PDO::FETCH_ASSOC);
    if ($row) {
        $stringId = (string)$row['id'];
        ob_start();
        $invController->edit($stringId);
        $html = ob_get_clean();
        if (strlen($html) > 500 && stripos($html, 'Edit Undangan') !== false) {
            echo "✓ [PASS] InvitationController::edit('$stringId') renders HTML successfully with string ID\n";
        }
    }
} catch (Throwable $e) {
    ob_end_clean();
    echo "✗ [FAIL] InvitationController::edit() with string ID threw: " . $e->getMessage() . "\n";
    exit(1);
}

echo "--- ALL HTTP CONTROLLER DISPATCH TESTS PASSED ---\n\n";
