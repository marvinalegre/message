<?php
/** @var PDO $db */

require_auth();

if ($_SERVER["REQUEST_METHOD"] === "GET") {
    if ($_SESSION["user_id"] !== 1) {
        header("Location: /");
        exit();
    }

    $stmt = $db->query("
        SELECT
            u.id,
            u.username,
            m.body,
            m.created_at
        FROM users u
        JOIN messages m ON m.id = (
            SELECT id
            FROM messages
            WHERE sender_id = u.id
               OR recipient_id = u.id
            ORDER BY created_at DESC
            LIMIT 1
        )
        WHERE u.id != 1
        ORDER BY m.created_at DESC
    ");

    $conversations = $stmt->fetchAll(PDO::FETCH_ASSOC);

    view("header");
    view("messages");
    view("footer");

    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $sender_id = $_SESSION["user_id"];

    if ($sender_id === 1) {
        http_response_code(403);
        exit("Admin cannot send messages here.");
    }

    $body = trim($_POST["body"] ?? "");

    if ($body === "") {
        http_response_code(400);
        exit("Message cannot be empty.");
    }

    $stmt = $db->prepare("
        INSERT INTO messages (sender_id, recipient_id, body)
        VALUES (:sender_id, 1, :body)
    ");

    $stmt->execute([
        "sender_id" => $sender_id,
        "body" => $body,
    ]);

    header("Location: /");
    exit();
}
