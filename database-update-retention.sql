ALTER TABLE `contact_messages`
    ADD COLUMN `delete_after_days` SMALLINT UNSIGNED DEFAULT NULL AFTER `referrer`,
    ADD COLUMN `delete_after_at` DATETIME DEFAULT NULL AFTER `delete_after_days`,
    ADD KEY `idx_contact_messages_delete_after_at` (`delete_after_at`);
