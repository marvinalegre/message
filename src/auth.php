<?php

function createUser(PDO $db, string $username, string $password): int
{
    $stmt = $db->prepare(
        "INSERT INTO users (username, password_hash) VALUES (?, ?)",
    );

    $stmt->execute([$username, password_hash($password, PASSWORD_DEFAULT)]);

    return (int) $db->lastInsertId();
}
