<?php

namespace Database\Seeders;

use PDO;

class UserSeeder
{
    public static function run(PDO $pdo): void
    {
        $users = [
            [
                'name' => 'Admin Invitara',
                'email' => 'admin@invitara.test',
                'password' => password_hash('password123', PASSWORD_DEFAULT),
                'role' => 'admin',
            ],
            [
                'name' => 'Budi Santoso',
                'email' => 'budi@invitara.test',
                'password' => password_hash('password123', PASSWORD_DEFAULT),
                'role' => 'user',
            ],
        ];

        $stmt = $pdo->prepare("
            INSERT INTO users (name, email, password, role) 
            VALUES (:name, :email, :password, :role)
            ON DUPLICATE KEY UPDATE name = VALUES(name), role = VALUES(role)
        ");

        foreach ($users as $user) {
            $stmt->execute($user);
        }

        echo " - UserSeeder: seeded " . count($users) . " users.\n";
    }
}
