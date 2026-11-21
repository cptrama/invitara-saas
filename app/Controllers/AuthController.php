<?php

namespace App\Controllers;

use App\Services\AuthService;
use App\Services\Flash;
use App\Services\Validator;
use App\Services\View;
use Flight;
use Throwable;

class AuthController
{
    private AuthService $authService;

    public function __construct()
    {
        $this->authService = new AuthService();
    }

    /**
     * Show login form
     */
    public function showLogin(): void
    {
        echo View::render('auth/login.twig', [
            'page_title' => 'Masuk ke Akun Anda',
        ]);
    }

    /**
     * Process login submission
     */
    public function login(): void
    {
        $req = Flight::request();
        $email = trim($req->data->email ?? '');
        $password = $req->data->password ?? '';

        // Save old input for repopulation
        $_SESSION['_old_input'] = ['email' => $email];

        $validator = Validator::make([
            'email' => $email,
            'password' => $password,
        ], [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            Flash::set('error', $validator->firstError());
            Flight::redirect('/login');
            return;
        }

        $user = $this->authService->login($email, $password);

        if (!$user) {
            Flash::set('error', 'Kombinasi email dan kata sandi tidak cocok.');
            Flight::redirect('/login');
            return;
        }

        unset($_SESSION['_old_input']);
        Flash::set('success', 'Selamat datang kembali, ' . htmlspecialchars($user['name'], ENT_QUOTES, 'UTF-8') . '!');

        // Check if redirect intended
        $redirectUrl = $_SESSION['_redirect_after_login'] ?? '/dashboard';
        unset($_SESSION['_redirect_after_login']);

        Flight::redirect($redirectUrl);
    }

    /**
     * Show registration form
     */
    public function showRegister(): void
    {
        echo View::render('auth/register.twig', [
            'page_title' => 'Daftar Akun Baru',
        ]);
    }

    /**
     * Process registration submission
     */
    public function register(): void
    {
        $req = Flight::request();
        $name = trim($req->data->name ?? '');
        $email = strtolower(trim($req->data->email ?? ''));
        $password = $req->data->password ?? '';
        $passwordConfirmation = $req->data->password_confirmation ?? '';

        $_SESSION['_old_input'] = [
            'name' => $name,
            'email' => $email,
        ];

        $validator = Validator::make([
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'password_confirmation' => $passwordConfirmation,
        ], [
            'name' => 'required|min:3|max:100',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'password_confirmation' => 'required|same:password',
        ]);

        if ($validator->fails()) {
            Flash::set('error', $validator->firstError());
            Flight::redirect('/register');
            return;
        }

        try {
            $user = $this->authService->register([
                'name' => $name,
                'email' => $email,
                'password' => $password,
                'role' => 'user',
            ]);

            unset($_SESSION['_old_input']);
            Flash::set('success', 'Akun berhasil dibuat! Selamat datang di Invitara.');
            Flight::redirect('/dashboard');
        } catch (Throwable $e) {
            Flash::set('error', 'Pendaftaran gagal: ' . $e->getMessage());
            Flight::redirect('/register');
        }
    }

    /**
     * Process logout
     */
    public function logout(): void
    {
        $this->authService->logout();
        Flash::set('success', 'Anda telah berhasil keluar.');
        Flight::redirect('/login');
    }
}
