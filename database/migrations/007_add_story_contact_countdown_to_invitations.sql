-- Migration 007: Menambahkan kolom story, contact, dan show_countdown pada tabel invitations
-- Berdasarkan spesifikasi Design.md Bagian 14 Poin 2 & Fitur PRD 6.5 / 6.8

ALTER TABLE invitations
ADD COLUMN story TEXT NULL AFTER closing_message,
ADD COLUMN contact VARCHAR(100) NULL AFTER story,
ADD COLUMN show_countdown TINYINT(1) NOT NULL DEFAULT 1 AFTER contact;
