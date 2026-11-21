<?php

namespace App\Services;

use App\Repositories\UserRepository;
use RuntimeException;

class AuthService
{
    private UserRepository $userRepo;

    public function __construct(?UserRepository $userRepo = null)
    {
        $this->userRepo = $userRepo ?? new UserRepository();
    }

    /**
     * Register a new user
     */
    public function register(array $data): array
    {
        $email = strtolower(trim($data['email']));

        if ($this->userRepo->findByEmail($email)) {
            throw new RuntimeException('Email sudah terdaftar.');
        }

        $userId = $this->userRepo->create([
            'name' => trim($data['name']),
            'email' => $email,
            'password' => password_hash($data['password'], PASSWORD_DEFAULT),
            'role' => $data['role'] ?? 'user',
        ]);

        $user = $this->userRepo->findById($userId);
        if (!$user) {
            throw new RuntimeException('Gagal membuat akun.');
        }

        // Auto login after registration
        $this->setUserSession($user);

        return $user;
    }

    /**
     * Authenticate user with email and password
     */
    public function login(string $email, string $password): ?array
    {
        $user = $this->userRepo->findByEmail($email);
        if (!$user) {
            return null;
        }

        if (!password_verify($password, $user['password'])) {
            return null;
        }

        // Remove password from user array
        unset($user['password']);

        $this->setUserSession($user);

        return $user;
    }

    /**
     * Set user session securely
     */
    public function setUserSession(array $user): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE && !headers_sent()) {
            session_start();
        }

        // Regenerate session id to protect against session fixation if session active and headers not sent
        if (session_status() === PHP_SESSION_ACTIVE && !headers_sent()) {
            session_regenerate_id(true);
        }

        $_SESSION['user'] = [
            'id' => (int)$user['id'],
            'name' => $user['name'],
            'email' => $user['email'],
            'role' => $user['role'],
        ];

        // Also refresh CSRF token
        CsrfService::regenerate();
    }

    /**
     * Logout user and clear session
     */
    public function logout(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE && !headers_sent()) {
            session_start();
        }

        unset($_SESSION['user']);
        if (session_status() === PHP_SESSION_ACTIVE && !headers_sent()) {
            session_regenerate_id(true);
        }
        CsrfService::regenerate();
    }

    /**
     * Get current user array or null
     */
    public static function user(): ?array
    {
        return $_SESSION['user'] ?? null;
    }

    /**
     * Check if currently authenticated
     */
    public static function check(): bool
    {
        return isset($_SESSION['user']['id']);
    }

    /**
     * Get current authenticated user ID
     */
    public static function id(): ?int
    {
        return isset($_SESSION['user']['id']) ? (int)$_SESSION['user']['id'] : null;
    }
}
