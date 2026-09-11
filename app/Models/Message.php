<?php
declare(strict_types=1);

/**
 * Contact messages. Every query against contact_messages lives here, so the
 * public endpoint, the admin screens and the cleanup command all agree on the
 * columns and on what a valid status is.
 */

function message_statuses(): array
{
    return ['new', 'read', 'archived'];
}

function message_retention_days(): array
{
    return [7, 14, 30, 60, 90];
}

/** The columns the admin list needs; the body is excerpted for display. */
function message_all(?string $status = null): array
{
    $sql = 'SELECT id, name, email, message, status, delete_after_days, delete_after_at, created_at
            FROM contact_messages';

    if ($status === null) {
        return app_pdo()->query($sql . ' ORDER BY created_at DESC')->fetchAll();
    }

    $stmt = app_pdo()->prepare($sql . ' WHERE status = :status ORDER BY created_at DESC');
    $stmt->execute(['status' => $status]);

    return $stmt->fetchAll();
}

function message_find(int $id): ?array
{
    $stmt = app_pdo()->prepare(
        'SELECT id, name, email, message, status, ip_address, user_agent, referrer,
                delete_after_days, delete_after_at, created_at
         FROM contact_messages
         WHERE id = :id
         LIMIT 1'
    );
    $stmt->execute(['id' => $id]);
    $message = $stmt->fetch();

    return $message ?: null;
}

function message_set_status(int $id, string $status): bool
{
    if ($id <= 0 || !in_array($status, message_statuses(), true)) {
        return false;
    }

    $stmt = app_pdo()->prepare('UPDATE contact_messages SET status = :status WHERE id = :id');
    $stmt->execute(['status' => $status, 'id' => $id]);

    return true;
}

function message_recent(int $limit = 5): array
{
    $stmt = app_pdo()->prepare(
        'SELECT id, name, email, status, created_at
         FROM contact_messages
         ORDER BY created_at DESC
         LIMIT :limit'
    );
    $stmt->bindValue('limit', $limit, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll();
}

/** Totals for the dashboard tiles, keyed the way the view reads them. */
function message_counts(): array
{
    $counts = ['total_messages' => 0, 'new_messages' => 0, 'read_messages' => 0];

    $counts['total_messages'] = (int)app_pdo()->query('SELECT COUNT(*) FROM contact_messages')->fetchColumn();
    $counts['new_messages'] = (int)app_pdo()->query("SELECT COUNT(*) FROM contact_messages WHERE status = 'new'")->fetchColumn();
    $counts['read_messages'] = (int)app_pdo()->query("SELECT COUNT(*) FROM contact_messages WHERE status = 'read'")->fetchColumn();

    return $counts;
}

/**
 * True when the same sender sent the same text minutes ago, which is nearly
 * always a double-submit rather than a second message.
 */
function message_is_recent_duplicate(string $messageHash): bool
{
    $stmt = app_pdo()->prepare(
        'SELECT id
         FROM contact_messages
         WHERE message_hash = :message_hash
           AND created_at >= (NOW() - INTERVAL 10 MINUTE)
         LIMIT 1'
    );
    $stmt->execute(['message_hash' => $messageHash]);

    return (bool)$stmt->fetch();
}

function message_create(array $data): void
{
    $stmt = app_pdo()->prepare(
        'INSERT INTO contact_messages
            (name, email, message, message_hash, ip_address, user_agent, referrer, delete_after_days, delete_after_at)
         VALUES
            (:name, :email, :message, :message_hash, :ip_address, :user_agent, :referrer, :delete_after_days, :delete_after_at)'
    );

    $stmt->execute([
        'name' => $data['name'],
        'email' => $data['email'],
        'message' => $data['message'],
        'message_hash' => $data['message_hash'],
        'ip_address' => $data['ip_address'],
        'user_agent' => $data['user_agent'],
        'referrer' => $data['referrer'],
        'delete_after_days' => $data['delete_after_days'],
        'delete_after_at' => $data['delete_after_at'],
    ]);
}

/** Drops the messages whose sender asked for them to expire. Returns the count. */
function message_delete_expired(): int
{
    $stmt = app_pdo()->prepare(
        'DELETE FROM contact_messages
         WHERE delete_after_at IS NOT NULL
           AND delete_after_at <= NOW()'
    );
    $stmt->execute();

    return $stmt->rowCount();
}
