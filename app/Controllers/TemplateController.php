<?php

namespace App\Controllers;

use App\Repositories\CategoryRepository;
use App\Repositories\TemplateRepository;
use App\Services\View;
use Flight;

class TemplateController
{
    private TemplateRepository $templateRepo;
    private CategoryRepository $categoryRepo;

    public function __construct()
    {
        $this->templateRepo = new TemplateRepository();
        $this->categoryRepo = new CategoryRepository();
    }

    /**
     * Display wedding templates gallery
     */
    public function index(): void
    {
        $templates = $this->templateRepo->allActive();
        $categories = $this->categoryRepo->all();

        echo View::render('templates/index.twig', [
            'page_title' => 'Galeri Template Undangan Pernikahan',
            'templates' => $templates,
            'categories' => $categories,
        ]);
    }

    /**
     * Display template details
     */
    public function show(string $slug): void
    {
        $slug = trim($slug);
        $template = $this->templateRepo->findBySlug($slug);

        // Fallback check by view_key if slug doesn't match directly
        if (!$template) {
            $template = $this->templateRepo->findByViewKey($slug);
        }

        if (!$template || !(bool)$template['is_active']) {
            Flight::notFound();
            return;
        }

        echo View::render('templates/show.twig', [
            'page_title' => 'Template ' . $template['name'],
            'template' => $template,
        ]);
    }

    /**
     * Display interactive live preview of a template with sample wedding data
     * Supports:
     * - /templates/preview/floral
     * - /templates/preview/modern
     * - /templates/preview/minimal
     * - /templates/@slug/preview
     */
    public function preview(string $keyOrSlug): void
    {
        $keyOrSlug = strtolower(trim($keyOrSlug));

        $aliasMap = [
            'floral'            => 'floral',
            'modern'            => 'modern',
            'minimal'           => 'minimal',
            'minimalis'         => 'minimal',
            'floral-klasik'     => 'floral',
            'modern-sederhana'  => 'modern',
            'minimalis-elegan'  => 'minimal',
        ];

        $viewKey = $aliasMap[$keyOrSlug] ?? null;
        $template = null;

        if ($viewKey) {
            $template = $this->templateRepo->findByViewKey($viewKey);
        } else {
            $template = $this->templateRepo->findBySlug($keyOrSlug);
            if ($template) {
                $viewKey = $template['view_key'] ?? 'minimal';
            }
        }

        if (!$template) {
            $viewKey = $viewKey ?? 'minimal';
            $template = [
                'id' => 1,
                'name' => ucfirst($viewKey) . ' Wedding Template',
                'view_key' => $viewKey,
                'category_name' => 'Wedding',
            ];
        }

        $names = [
            'floral' => [
                'p1' => 'Raden Mas Arya',
                'p2' => 'Citra Kirana',
                'title' => 'The Wedding of Arya & Citra',
                'color' => 'rose',
                'story' => 'Kisah cinta kami bermula dari sebuah pertemuan tak terduga di kebun botani pada tahun 2021. Dari canda tawa hangat hingga saling menguatkan, kami kini memantapkan langkah menuju pelaminan suci.',
            ],
            'modern' => [
                'p1' => 'Dimas Pratama',
                'p2' => 'Amanda Putri',
                'title' => 'The Wedding of Dimas & Amanda',
                'color' => 'gold',
                'story' => 'Dua insan dengan visi yang sama dipertemukan dalam perjalanan karier dan kehidupan. Kini kami melangkah bersama membangun masa depan yang penuh berkah dan cinta.',
            ],
            'minimal' => [
                'p1' => 'Budi Santoso',
                'p2' => 'Santi Rahayu',
                'title' => 'The Wedding of Budi & Santi',
                'color' => 'classic',
                'story' => 'Perjalanan cinta kami telah melewati berbagai fase indah. Bersama keluarga tercinta, kami memohon doa restu untuk mengarungi bahtera rumah tangga yang sakinah.',
            ],
        ];

        $meta = $names[$viewKey] ?? $names['minimal'];

        $sampleInvitation = [
            'id' => 0,
            'user_id' => 1,
            'template_id' => (int)($template['id'] ?? 1),
            'title' => $meta['title'],
            'slug' => 'preview-' . $viewKey,
            'partner_one_name' => $meta['p1'],
            'partner_two_name' => $meta['p2'],
            'event_date' => date('Y-m-d', strtotime('+30 days')),
            'event_time' => '09:00:00',
            'reception_date' => date('Y-m-d', strtotime('+30 days')),
            'reception_time' => '11:00:00',
            'timezone' => 'Asia/Jakarta',
            'location_name' => 'Grand Ballroom Hotel Mulia Senayan',
            'address' => 'Jl. Asia Afrika, Senayan, Jakarta Pusat, DKI Jakarta',
            'maps_url' => 'https://maps.google.com',
            'theme_color' => $meta['color'],
            'story' => $meta['story'],
            'contact' => '0812-3456-7890 (Keluarga Besar)',
            'show_countdown' => 1,
            'status' => 'published',
            'template_name' => $template['name'] ?? 'Invitara Wedding',
            'template_view_key' => $viewKey,
            'template_slug' => $template['slug'] ?? $keyOrSlug,
        ];

        $sampleRsvps = [
            [
                'guest_name' => 'Bapak Budi & Rekan',
                'status' => 'hadir',
                'pax' => 2,
                'wishes' => 'Selamat menempuh hidup baru untuk kedua mempelai! Semoga senantiasa sakinah, mawaddah, warahmah dan penuh kebahagiaan.',
                'created_at' => date('Y-m-d H:i:s', strtotime('-2 hours')),
            ],
            [
                'guest_name' => 'Ibu Sari & Keluarga',
                'status' => 'hadir',
                'pax' => 3,
                'wishes' => 'Barakallahu lakuma wa baraka alaikuma wa jama\'a bainakuma fii khoir. Doa terbaik untuk pernikahan kalian berdua!',
                'created_at' => date('Y-m-d H:i:s', strtotime('-1 day')),
            ],
        ];

        $viewMap = [
            'minimal' => 'invitations/public/minimal.twig',
            'floral'  => 'invitations/public/floral.twig',
            'modern'  => 'invitations/public/modern.twig',
        ];

        $viewFile = $viewMap[$viewKey] ?? 'invitations/public/minimal.twig';

        echo View::render($viewFile, [
            'page_title' => '[Demo Preview] ' . $sampleInvitation['title'] . ' | Invitara',
            'invitation' => $sampleInvitation,
            'rsvps' => $sampleRsvps,
            'is_preview' => true,
        ]);
    }
}
