<?php

function createUser(PDO $db, string $username, string $password): int
{
    $stmt = $db->prepare(
        "INSERT INTO users (username, password_hash) VALUES (?, ?)",
    );

    $stmt->execute([$username, password_hash($password, PASSWORD_DEFAULT)]);

    return (int) $db->lastInsertId();
}

function login(PDO $db, string $username, string $password): bool
{
    $stmt = $db->prepare(
        "SELECT id, password_hash FROM users WHERE username = ?",
    );

    $stmt->execute([$username]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user || !password_verify($password, $user["password_hash"])) {
        return false;
    }

    session_start();
    $_SESSION["user_id"] = $user["id"];

    return true;
}
