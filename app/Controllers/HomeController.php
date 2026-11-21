<?php

namespace App\Controllers;

use App\Repositories\TemplateRepository;
use App\Services\View;

class HomeController
{
    private TemplateRepository $templateRepo;

    public function __construct()
    {
        $this->templateRepo = new TemplateRepository();
    }

    public function index(): void
    {
        $templates = $this->templateRepo->allActive();

        echo View::render('landing/index.twig', [
            'page_title' => 'Buat Undangan Pernikahan Digital dengan Mudah',
            'templates' => $templates,
        ]);
    }
}
