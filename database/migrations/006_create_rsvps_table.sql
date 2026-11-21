CREATE TABLE IF NOT EXISTS rsvps (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    invitation_id BIGINT NOT NULL,
    guest_name VARCHAR(120) NOT NULL,
    attendance_status VARCHAR(20) NOT NULL DEFAULT 'attending',
    guest_count INT NOT NULL DEFAULT 1,
    message TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_rsvps_invitation_id (invitation_id),
    INDEX idx_rsvps_status (attendance_status),
    CONSTRAINT fk_rsvps_invitation FOREIGN KEY (invitation_id) REFERENCES invitations (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
