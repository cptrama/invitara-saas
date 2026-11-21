<?php

use App\Controllers\AdminController;
use App\Controllers\AuthController;
use App\Controllers\DashboardController;
use App\Controllers\HomeController;
use App\Controllers\InvitationController;
use App\Controllers\ProfileController;
use App\Controllers\RSVPController;
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
Flight::route('GET /i/@slug:[a-zA-Z0-9\-]+', [InvitationController::class, 'showPublic']);
Flight::route('POST /i/@slug:[a-zA-Z0-9\-]+/rsvp', [RSVPController::class, 'storePublic'])->addMiddleware(new CsrfMiddleware());

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

    // Dashboard Invitations Routes (Anggota 3 - PR 5 & PR 7)
    Flight::route('GET /invitations', [InvitationController::class, 'index']);
    Flight::route('GET /invitations/create', [InvitationController::class, 'create']);
    Flight::route('POST /invitations', [InvitationController::class, 'store'])->addMiddleware(new CsrfMiddleware());
    Flight::route('GET /invitations/@id:[0-9]+/edit', [InvitationController::class, 'edit'])->addMiddleware(new OwnershipMiddleware());
    Flight::route('POST /invitations/@id:[0-9]+/edit', [InvitationController::class, 'update'])->addMiddleware([new OwnershipMiddleware(), new CsrfMiddleware()]);
    Flight::route('POST /invitations/@id:[0-9]+/delete', [InvitationController::class, 'delete'])->addMiddleware([new OwnershipMiddleware(), new CsrfMiddleware()]);
    Flight::route('POST /invitations/@id:[0-9]+/publish', [InvitationController::class, 'publish'])->addMiddleware([new OwnershipMiddleware(), new CsrfMiddleware()]);
    Flight::route('POST /invitations/@id:[0-9]+/unpublish', [InvitationController::class, 'unpublish'])->addMiddleware([new OwnershipMiddleware(), new CsrfMiddleware()]);

    // Dashboard RSVP Routes (Anggota 3 - PR 8)
    Flight::route('GET /invitations/@id:[0-9]+/rsvps', [RSVPController::class, 'index'])->addMiddleware(new OwnershipMiddleware());
    Flight::route('POST /invitations/@id:[0-9]+/rsvps/@rsvp_id:[0-9]+/delete', [RSVPController::class, 'delete'])->addMiddleware([new OwnershipMiddleware(), new CsrfMiddleware()]);

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
