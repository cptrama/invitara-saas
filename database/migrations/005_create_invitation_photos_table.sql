CREATE TABLE IF NOT EXISTS invitation_photos (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    invitation_id BIGINT NOT NULL,
    photo_path VARCHAR(255) NOT NULL,
    caption VARCHAR(150) NULL,
    sort_order INT NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_invitation_photos_invitation_id (invitation_id),
    INDEX idx_invitation_photos_sort (sort_order),
    CONSTRAINT fk_invitation_photos_invitation FOREIGN KEY (invitation_id) REFERENCES invitations (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
