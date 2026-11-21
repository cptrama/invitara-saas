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

$sampleTemplates = [
    [
        'id' => 1,
        'name' => 'Minimalist Elegance',
        'slug' => 'minimalist-elegance',
        'description' => 'Desain bersih dan modern dengan tipografi klasik.',
        'view_key' => 'minimal',
        'category_name' => 'Modern Wedding',
    ],
    [
        'id' => 2,
        'name' => 'Floral Blossom',
        'slug' => 'floral-blossom',
        'description' => 'Aksen bunga lembut dengan sentuhan palet romantis.',
        'view_key' => 'floral',
        'category_name' => 'Romantic Floral',
    ],
    [
        'id' => 3,
        'name' => 'Modern Luxury',
        'slug' => 'modern-luxury',
        'description' => 'Gaya kontemporer mewah dengan palet emas dan gelap.',
        'view_key' => 'modern',
        'category_name' => 'Contemporary Luxury',
    ],
];

$sampleInvitation = [
    'id' => 1,
    'user_id' => 1,
    'template_id' => 1,
    'title' => 'The Wedding of Romeo & Juliet',
    'slug' => 'romeo-juliet',
    'partner_one_name' => 'Romeo Montague',
    'partner_two_name' => 'Juliet Capulet',
    'cover_photo_path' => null,
    'event_date' => '2026-12-12',
    'event_time' => '10:00:00',
    'timezone' => 'Asia/Jakarta',
    'venue_name' => 'Grand Ballroom Verona',
    'venue_address' => 'Jl. Cinta Abadi No. 12, Verona',
    'maps_url' => 'https://maps.google.com/?q=Verona',
    'description' => 'Dua insan yang dipersatukan dalam cinta tulus.',
    'closing_message' => 'Merupakan kebahagiaan bagi kami atas kehadiran dan doa restu Anda.',
    'theme_color' => 'rose',
    'status' => 'published',
    'template_name' => 'Minimalist Elegance',
    'total_rsvps' => 2,
    'total_attending_guests' => 3,
];

$sampleRsvps = [
    [
        'id' => 1,
        'guest_name' => 'Keluarga Budi Santoso',
        'attendance_status' => 'attending',
        'guest_count' => 2,
        'message' => 'Selamat menempuh hidup baru Romeo & Juliet!',
    ],
    [
        'id' => 2,
        'guest_name' => 'Siti Aminah',
        'attendance_status' => 'not_attending',
        'guest_count' => 0,
        'message' => 'Mohon maaf belum bisa hadir secara langsung.',
    ],
];

$views = [
    'landing/index.twig' => [
        'page_title' => 'Landing Page Invitara',
        'templates' => $sampleTemplates,
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
        'invitations' => [$sampleInvitation],
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
    'templates/index.twig' => [
        'page_title' => 'Galeri Template Undangan',
        'templates' => $sampleTemplates,
    ],
    'templates/preview.twig' => [
        'page_title' => 'Preview Template - ' . $sampleTemplates[0]['name'],
        'template' => $sampleTemplates[0],
    ],
    'editor/form.twig' => [
        'page_title' => 'Editor Undangan',
        'templates' => $sampleTemplates,
        'invitation' => $sampleInvitation,
    ],
    'invitations/preview.twig' => [
        'page_title' => 'Preview Undangan',
        'invitation' => $sampleInvitation,
    ],
    'invitations/public/minimal.twig' => [
        'invitation' => $sampleInvitation,
        'rsvps' => $sampleRsvps,
        'photos' => [],
    ],
    'invitations/public/floral.twig' => [
        'invitation' => $sampleInvitation,
        'rsvps' => $sampleRsvps,
        'photos' => [],
    ],
    'invitations/public/modern.twig' => [
        'invitation' => $sampleInvitation,
        'rsvps' => $sampleRsvps,
        'photos' => [],
    ],
];

echo "========================================================\n";
echo " TESTING ALL FRONTEND & WEDDING VIEW RENDERING (TWIG)\n";
echo "========================================================\n";

$allPassed = true;

foreach ($views as $viewPath => $data) {
    try {
        $html = View::render($viewPath, $data);
        if (empty($html)) {
            throw new RuntimeException("Generated HTML is empty.");
        }
        echo "✓ [PASS] Render {$viewPath} (" . strlen($html) . " bytes)\n";
    } catch (Throwable $e) {
        $allPassed = false;
        echo "✗ [FAIL] Render {$viewPath}: " . $e->getMessage() . "\n";
    }
}

echo "========================================================\n";
if ($allPassed) {
    echo " ALL 12 FRONTEND & WEDDING VIEWS RENDERED SUCCESSFULLY!\n";
    exit(0);
} else {
    echo " SOME VIEWS FAILED TO RENDER.\n";
    exit(1);
}
