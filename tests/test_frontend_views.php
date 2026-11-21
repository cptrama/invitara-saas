<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/vendor/autoload.php';

use App\Services\View;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$_SESSION['user'] = [
    'id' => 1,
    'name' => 'Budi Santoso',
    'email' => 'budi@invitara.test',
    'role' => 'user',
];

$views = [
    'landing/index.twig' => [
        'page_title' => 'Landing Page Invitara',
        'templates' => [
            [
                'id' => 1,
                'name' => 'Minimalist Elegance',
                'description' => 'Desain bersih dan modern dengan tipografi klasik.',
                'view_key' => 'minimal',
                'category_name' => 'Modern Wedding',
            ],
            [
                'id' => 2,
                'name' => 'Floral Blossom',
                'description' => 'Aksen bunga lembut dengan sentuhan palet romantis.',
                'view_key' => 'floral',
                'category_name' => 'Romantic Floral',
            ],
        ],
    ],
    'dashboard/index.twig' => [
        'page_title' => 'Dashboard Overview',
        'stats' => [
            'total' => 3,
            'published' => 2,
            'drafts' => 1,
            'unpublished' => 0,
            'total_rsvps' => 12,
        ],
        'invitations' => [
            [
                'id' => 1,
                'title' => 'The Wedding of Romeo & Juliet',
                'slug' => 'romeo-juliet',
                'partner_one_name' => 'Romeo',
                'partner_two_name' => 'Juliet',
                'event_date' => '2026-12-12',
                'status' => 'published',
                'template_name' => 'Minimalist Elegance',
                'total_rsvps' => 10,
                'total_attending_guests' => 18,
            ],
            [
                'id' => 2,
                'title' => 'Pernikahan Rama & Shinta',
                'slug' => 'rama-shinta',
                'partner_one_name' => 'Rama',
                'partner_two_name' => 'Shinta',
                'event_date' => '2027-01-20',
                'status' => 'draft',
                'template_name' => 'Floral Blossom',
                'total_rsvps' => 0,
                'total_attending_guests' => 0,
            ],
        ],
    ],
    'auth/login.twig' => [
        'page_title' => 'Masuk ke Invitara',
    ],
    'auth/register.twig' => [
        'page_title' => 'Daftar Akun Invitara',
    ],
    'layouts/404.twig' => [
        'page_title' => '404 - Halaman Tidak Ditemukan',
    ],
];

echo "==========================================\n";
echo " TESTING FRONTEND VIEW RENDERING (TWIG)\n";
echo "==========================================\n";

$allPassed = true;

foreach ($views as $viewPath => $data) {
    try {
        $html = View::render($viewPath, $data);
        if (empty($html) || !str_contains($html, 'Invitara')) {
            throw new RuntimeException("Generated HTML is empty or missing Invitara brand mark.");
        }
        echo "✓ [PASS] Render {$viewPath} (" . strlen($html) . " bytes)\n";
    } catch (Throwable $e) {
        $allPassed = false;
        echo "✗ [FAIL] Render {$viewPath}: " . $e->getMessage() . "\n";
    }
}

echo "==========================================\n";
if ($allPassed) {
    echo " ALL FRONTEND VIEWS RENDERED SUCCESSFULLY!\n";
    exit(0);
} else {
    echo " SOME VIEWS FAILED TO RENDER.\n";
    exit(1);
}
