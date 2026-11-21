<?php

namespace App\Controllers;

use App\Repositories\UserRepository;
use App\Services\AuthService;
use App\Services\Flash;
use App\Services\Validator;
use App\Services\View;
use Flight;

class ProfileController
{
    private UserRepository $userRepo;

    public function __construct()
    {
        $this->userRepo = new UserRepository();
    }

    /**
     * Show Profile Settings Page
     */
    public function showProfile(): void
    {
        $userId = AuthService::id();
        $user = $this->userRepo->findById($userId);

        if (!$user) {
            Flash::set('error', 'Data pengguna tidak ditemukan.');
            Flight::redirect('/dashboard');
            return;
        }

        echo View::render('dashboard/profile.twig', [
            'page_title' => 'Pengaturan Profil',
            'user' => $user,
        ]);
    }

    /**
     * Update Profile Information (Name)
     */
    public function updateProfile(): void
    {
        $userId = AuthService::id();
        $req = Flight::request();
        $name = trim($req->data->name ?? '');

        $validator = Validator::make([
            'name' => $name,
        ], [
            'name' => 'required|min:3|max:100',
        ]);

        if ($validator->fails()) {
            Flash::set('error', $validator->firstError());
            Flight::redirect('/dashboard/profile');
            return;
        }

        $this->userRepo->update($userId, [
            'name' => $name,
        ]);

        // Update active session name
        if (isset($_SESSION['user'])) {
            $_SESSION['user']['name'] = $name;
        }

        Flash::set('success', 'Profil Anda berhasil diperbarui.');
        Flight::redirect('/dashboard/profile');
    }

    /**
     * Update User Password
     */
    public function updatePassword(): void
    {
        $userId = AuthService::id();
        $req = Flight::request();
        $currentPassword = $req->data->current_password ?? '';
        $newPassword = $req->data->password ?? '';
        $passwordConfirmation = $req->data->password_confirmation ?? '';

        $validator = Validator::make([
            'current_password' => $currentPassword,
            'password' => $newPassword,
            'password_confirmation' => $passwordConfirmation,
        ], [
            'current_password' => 'required',
            'password' => 'required|min:6',
            'password_confirmation' => 'required|same:password',
        ]);

        if ($validator->fails()) {
            Flash::set('error', $validator->firstError());
            Flight::redirect('/dashboard/profile');
            return;
        }

        // Verify existing password
        $user = $this->userRepo->findByIdWithPassword($userId);
        if (!$user || !password_verify($currentPassword, $user['password'])) {
            Flash::set('error', 'Kata sandi saat ini yang Anda masukkan salah.');
            Flight::redirect('/dashboard/profile');
            return;
        }

        // Hash and update new password
        $hashed = password_hash($newPassword, PASSWORD_DEFAULT);
        $this->userRepo->updatePassword($userId, $hashed);

        Flash::set('success', 'Kata sandi akun Anda berhasil diperbarui.');
        Flight::redirect('/dashboard/profile');
    }
}
