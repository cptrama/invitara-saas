<?php

use App\Controllers\AdminController;
use App\Controllers\AuthController;
use App\Controllers\DashboardController;
use App\Controllers\HomeController;
use App\Controllers\ProfileController;
use App\Controllers\TemplateController;
use App\Middleware\AdminMiddleware;
use App\Middleware\AuthMiddleware;
use App\Middleware\CsrfMiddleware;
use App\Middleware\GuestMiddleware;
use App\Middleware\OwnershipMiddleware;

// Public Routes
Flight::route('GET /', [HomeController::class, 'index']);
Flight::route('GET /templates', [TemplateController::class, 'index']);
Flight::route('GET /templates/@slug', [TemplateController::class, 'show']);

// Authentication Routes (Guest Only)
Flight::group('', function () {
    Flight::route('GET /login', [AuthController::class, 'showLogin']);
    Flight::route('POST /login', [AuthController::class, 'login'])->addMiddleware(new CsrfMiddleware());
    Flight::route('GET /register', [AuthController::class, 'showRegister']);
    Flight::route('POST /register', [AuthController::class, 'register'])->addMiddleware(new CsrfMiddleware());
}, [new GuestMiddleware()]);

// Authenticated Logout
Flight::route('POST /logout', [AuthController::class, 'logout'])->addMiddleware([new AuthMiddleware(), new CsrfMiddleware()]);
Flight::route('GET /logout', [AuthController::class, 'logout'])->addMiddleware(new AuthMiddleware());

// Authenticated Dashboard Routes
Flight::group('/dashboard', function () {
    Flight::route('GET /', [DashboardController::class, 'index']);
    Flight::route('GET /overview', [DashboardController::class, 'index']);

    // Profile Settings (PRD 6.11)
    Flight::route('GET /profile', [ProfileController::class, 'showProfile']);
    Flight::route('POST /profile', [ProfileController::class, 'updateProfile'])->addMiddleware(new CsrfMiddleware());
    Flight::route('POST /profile/password', [ProfileController::class, 'updatePassword'])->addMiddleware(new CsrfMiddleware());

    // Placeholders and Ownership Route contracts for Anggota 3
    Flight::route('GET /invitations', function () {
        Flight::redirect('/dashboard');
    });

    // Example Route showing Ownership Middleware protection
    Flight::route('GET /invitations/@id:[0-9]+/check-ownership', function (int $id) {
        Flight::json([
            'status' => 'success',
            'message' => "Ownership confirmed for invitation ID {$id}",
            'invitation_id' => $id,
        ]);
    })->addMiddleware(new OwnershipMiddleware());

}, [new AuthMiddleware()]);

// Admin Platform Management & Monitoring (PRD 3.2 & 16.1)
Flight::group('/admin', function () {
    Flight::route('GET /', [AdminController::class, 'index']);
    Flight::route('POST /templates/@id:[0-9]+/toggle', [AdminController::class, 'toggleTemplate'])->addMiddleware(new CsrfMiddleware());
}, [new AuthMiddleware(), new AdminMiddleware()]);

// 404 Route handler
Flight::map('notFound', function () {
    echo \App\Services\View::render('layouts/404.twig', [
        'page_title' => '404 - Halaman Tidak Ditemukan',
    ]);
});
