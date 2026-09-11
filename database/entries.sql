-- Research entries: papers, thesis, and research projects.
-- Each row becomes its own page at /research/<slug>.

CREATE TABLE IF NOT EXISTS `entries` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `type` ENUM('paper', 'thesis', 'project') NOT NULL DEFAULT 'paper',
    `slug` VARCHAR(160) NOT NULL,
    `title` VARCHAR(255) NOT NULL,
    `subtitle` VARCHAR(255) NULL,
    `authors` VARCHAR(255) NULL,
    `venue` VARCHAR(190) NULL,
    `year` SMALLINT UNSIGNED NULL,
    `official_url` VARCHAR(500) NULL,
    `abstract` TEXT NULL,
    `body` MEDIUMTEXT NULL,
    `sort_order` INT NOT NULL DEFAULT 0,
    `is_published` TINYINT(1) NOT NULL DEFAULT 0,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uniq_entries_slug` (`slug`),
    KEY `idx_entries_type_published` (`type`, `is_published`, `sort_order`),
    KEY `idx_entries_published_sort` (`is_published`, `sort_order`, `year`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
