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
     * Display template details and preview
     */
    public function show(string $slug): void
    {
        $template = $this->templateRepo->findBySlug(trim($slug));

        if (!$template || !(bool)$template['is_active']) {
            Flight::notFound();
            return;
        }

        echo View::render('templates/show.twig', [
            'page_title' => 'Template ' . $template['name'],
            'template' => $template,
        ]);
    }
}
