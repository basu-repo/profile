CREATE DATABASE IF NOT EXISTS `my_profile`
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE `my_profile`;

CREATE TABLE IF NOT EXISTS `contact_messages` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(120) NOT NULL,
    `email` VARCHAR(254) NOT NULL,
    `message` TEXT NOT NULL,
    `message_hash` CHAR(64) NOT NULL,
    `ip_address` VARCHAR(45) NOT NULL,
    `user_agent` VARCHAR(255) DEFAULT NULL,
    `referrer` VARCHAR(255) DEFAULT NULL,
    `delete_after_days` SMALLINT UNSIGNED DEFAULT NULL,
    `delete_after_at` DATETIME DEFAULT NULL,
    `status` ENUM('new', 'read', 'archived') NOT NULL DEFAULT 'new',
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_contact_messages_status_created` (`status`, `created_at`),
    KEY `idx_contact_messages_email` (`email`),
    KEY `idx_contact_messages_hash_created` (`message_hash`, `created_at`),
    KEY `idx_contact_messages_delete_after_at` (`delete_after_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `admin_users` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `full_name` VARCHAR(120) NOT NULL,
    `email` VARCHAR(190) NOT NULL,
    `password_hash` VARCHAR(255) NOT NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `last_login_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uniq_admin_users_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `site_content` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `content_key` VARCHAR(120) NOT NULL,
    `content_value` MEDIUMTEXT NOT NULL,
    `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uniq_site_content_key` (`content_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
