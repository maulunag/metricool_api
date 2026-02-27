CREATE TABLE IF NOT EXISTS `content_topics` (
    `id`          INT AUTO_INCREMENT PRIMARY KEY,
    `title`       VARCHAR(500)  NOT NULL COMMENT 'Topic title',
    `description` TEXT          DEFAULT NULL COMMENT 'Topic description/summary',
    `keywords`    VARCHAR(500)  DEFAULT NULL COMMENT 'Comma-separated keywords',
    `category`    VARCHAR(255)  DEFAULT NULL COMMENT 'Topic category',
    `status`      ENUM('draft','ready','used') NOT NULL DEFAULT 'draft' COMMENT 'draft, ready for AI, or already used',
    `created_at`  TIMESTAMP     DEFAULT CURRENT_TIMESTAMP,
    `updated_at`  TIMESTAMP     DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    INDEX `idx_status`     (`status`),
    INDEX `idx_category`   (`category`),
    INDEX `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
