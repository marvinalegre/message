<?php

function rate_limit(PDO $db, string $key, int $limit, int $window): bool
{
    $now = time();

    $stmt = $db->prepare(
        "SELECT attempts, window_start
         FROM rate_limits
         WHERE key = ?",
    );
    $stmt->execute([$key]);

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row || $now - $row["window_start"] >= $window) {
        $stmt = $db->prepare(
            "INSERT OR REPLACE INTO rate_limits
             (key, attempts, window_start)
             VALUES (?, 1, ?)",
        );
        $stmt->execute([$key, $now]);

        return true;
    }

    if ($row["attempts"] >= $limit) {
        return false;
    }

    $stmt = $db->prepare(
        "UPDATE rate_limits
         SET attempts = attempts + 1
         WHERE key = ?",
    );
    $stmt->execute([$key]);

    return true;
}
