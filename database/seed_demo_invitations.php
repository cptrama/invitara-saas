<?php
require_once __DIR__ . '/../vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->safeLoad();

$pdo = App\Services\Database::getConnection();

// Check template IDs
$floral = $pdo->query("SELECT id FROM templates WHERE view_key = 'floral' LIMIT 1")->fetch(PDO::FETCH_ASSOC);
$modern = $pdo->query("SELECT id FROM templates WHERE view_key = 'modern' LIMIT 1")->fetch(PDO::FETCH_ASSOC);

if ($floral) {
    $stmt = $pdo->prepare("
        INSERT INTO invitations (user_id, template_id, title, slug, partner_one_name, partner_two_name, event_date, event_time, timezone, venue_name, venue_address, maps_url, theme_color, story, contact, show_countdown, status)
        VALUES (1, :template_id, 'The Wedding of Raden & Citra', 'raden-citra', 'Raden Mas Arya', 'Citra Kirana', DATE_ADD(CURDATE(), INTERVAL 30 DAY), '09:00:00', 'Asia/Jakarta', 'Grand Ballroom Hotel Indonesia Kempinski', 'Jl. M.H. Thamrin No. 1, Jakarta Pusat', 'https://maps.google.com', 'rose', 'Kisah cinta kami bermula dari kebun botani yang indah.', '081234567890', 1, 'published')
        ON DUPLICATE KEY UPDATE status = 'published', template_id = VALUES(template_id)
    ");
    $stmt->execute(['template_id' => $floral['id']]);
    echo "Raden & Citra seeded.\n";
}

if ($modern) {
    $stmt = $pdo->prepare("
        INSERT INTO invitations (user_id, template_id, title, slug, partner_one_name, partner_two_name, event_date, event_time, timezone, venue_name, venue_address, maps_url, theme_color, story, contact, show_countdown, status)
        VALUES (1, :template_id, 'The Wedding of Dimas & Amanda', 'dimas-amanda', 'Dimas Pratama', 'Amanda Putri', DATE_ADD(CURDATE(), INTERVAL 45 DAY), '10:00:00', 'Asia/Jakarta', 'Grand Ballroom Hotel Mulia Senayan', 'Jl. Asia Afrika, Senayan, Jakarta', 'https://maps.google.com', 'gold', 'Dua insan yang melangkah bersama membangun masa depan.', '081234567890', 1, 'published')
        ON DUPLICATE KEY UPDATE status = 'published', template_id = VALUES(template_id)
    ");
    $stmt->execute(['template_id' => $modern['id']]);
    echo "Dimas & Amanda seeded.\n";
}
