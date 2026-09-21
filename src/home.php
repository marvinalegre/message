<?php
/** @var PDO $db */

require_auth();

$user_id = $_SESSION["user_id"];

if ($user_id === 1) {
    header("Location: /messages");
    exit();
}

$stmt = $db->prepare("
        SELECT
            m.body,
            m.created_at,
            m.sender_id,
            u.username
        FROM messages m
        JOIN users u ON u.id = m.sender_id
        WHERE
            (m.sender_id = :user_id AND m.recipient_id = 1)
            OR
            (m.sender_id = 1 AND m.recipient_id = :user_id)
        ORDER BY m.created_at ASC
    ");

$stmt->execute([
    "user_id" => $user_id,
]);

$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

view("header");
view("home");
view("footer");

exit();
