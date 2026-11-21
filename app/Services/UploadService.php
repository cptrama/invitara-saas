<?php

namespace App\Services;

use RuntimeException;

class UploadService
{
    private const ALLOWED_MIME_TYPES = [
        'image/jpeg' => 'jpg',
        'image/png'  => 'png',
        'image/webp' => 'webp',
    ];

    private int $maxSize;
    private string $uploadDir;

    public function __construct(?int $maxSize = null, ?string $uploadDir = null)
    {
        $this->maxSize = $maxSize ?? (int)env('UPLOAD_MAX_SIZE', 2097152); // Default 2MB
        $this->uploadDir = $uploadDir ?? (dirname(__DIR__, 2) . '/public/uploads');
    }

    /**
     * Upload and validate file securely
     * 
     * @param array $file $_FILES['input_name'] array
     * @param string $subfolder Subdirectory inside public/uploads (e.g. 'covers', 'gallery')
     * @return array ['path' => string, 'filename' => string, 'size' => int, 'mime' => string]
     * @throws RuntimeException
     */
    public function upload(array $file, string $subfolder = 'covers'): array
    {
        // 1. Check for basic PHP upload error
        if (!isset($file['error']) || is_array($file['error'])) {
            throw new RuntimeException('Parameter berkas tidak valid.');
        }

        switch ($file['error']) {
            case UPLOAD_ERR_OK:
                break;
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                throw new RuntimeException('Ukuran berkas melebihi batas yang diizinkan (maksimal ' . round($this->maxSize / 1048576, 1) . ' MB).');
            case UPLOAD_ERR_NO_FILE:
                throw new RuntimeException('Tidak ada berkas yang diunggah.');
            default:
                throw new RuntimeException('Terjadi kesalahan saat mengunggah berkas.');
        }

        // 2. Validate file size
        if ($file['size'] > $this->maxSize) {
            throw new RuntimeException('Ukuran berkas melebihi batas maksimal (' . round($this->maxSize / 1048576, 1) . ' MB).');
        }

        // 3. Inspect genuine MIME type using finfo (prevents extension spoofing)
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        if (!array_key_exists($mimeType, self::ALLOWED_MIME_TYPES)) {
            throw new RuntimeException('Format berkas tidak didukung. Harap unggah gambar JPG, PNG, atau WEBP.');
        }

        $extension = self::ALLOWED_MIME_TYPES[$mimeType];

        // 4. Sanitize subfolder name to prevent directory traversal
        $subfolder = preg_replace('/[^a-zA-Z0-9_\-]/', '', $subfolder);
        $targetDirectory = rtrim($this->uploadDir, '/\\') . '/' . $subfolder;

        if (!is_dir($targetDirectory)) {
            if (!mkdir($targetDirectory, 0755, true) && !is_dir($targetDirectory)) {
                throw new RuntimeException('Gagal membuat direktori penyimpanan berkas.');
            }
        }

        // 5. Generate secure random filename
        $uniqueName = bin2hex(random_bytes(16)) . '.' . $extension;
        $targetFilePath = $targetDirectory . '/' . $uniqueName;

        // 6. Move file to destination
        if (!move_uploaded_file($file['tmp_name'], $targetFilePath)) {
            throw new RuntimeException('Gagal memindahkan berkas unggahan ke lokasi tujuan.');
        }

        return [
            'path' => '/uploads/' . $subfolder . '/' . $uniqueName,
            'filename' => $uniqueName,
            'size' => (int)$file['size'],
            'mime' => $mimeType,
        ];
    }

    /**
     * Delete an uploaded file safely
     */
    public function delete(string $relativePath): bool
    {
        // Prevent directory traversal attacks
        if (str_contains($relativePath, '..')) {
            return false;
        }

        $relativePath = ltrim($relativePath, '/');
        if (!str_starts_with($relativePath, 'uploads/')) {
            return false;
        }

        $fullPath = dirname(__DIR__, 2) . '/public/' . $relativePath;
        if (file_exists($fullPath) && is_file($fullPath)) {
            return unlink($fullPath);
        }

        return false;
    }
}
