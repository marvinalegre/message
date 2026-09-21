<?php
/** @var array $matches */
/** @var PDO $db */

if ($_SERVER["REQUEST_METHOD"] === "GET") {
    require_auth();

    if ($_SESSION["user_id"] !== 1) {
        header("Location: /");
        exit();
    }

    $user_id = (int) $matches[1];

    if ($user_id === 1) {
        http_response_code(404);
        exit("Conversation not found.");
    }

    $stmt = $db->prepare("
        SELECT username
        FROM users
        WHERE id = :id
    ");

    $stmt->execute(["id" => $user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        http_response_code(404);
        exit("User not found.");
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
            (m.sender_id = 1 AND m.recipient_id = :user_id)
            OR
            (m.sender_id = :user_id AND m.recipient_id = 1)
        ORDER BY m.created_at ASC
    ");

    $stmt->execute(["user_id" => $user_id]);
    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

    view("header");
    view("chat");
    view("footer");

    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    require_auth();

    if ($_SESSION["user_id"] !== 1) {
        header("Location: /");
        exit();
    }

    $user_id = (int) $matches[1];
    $body = trim($_POST["body"] ?? "");

    if ($body === "") {
        http_response_code(400);
        exit("Message cannot be empty.");
    }

    $stmt = $db->prepare("
        INSERT INTO messages (sender_id, recipient_id, body)
        VALUES (1, :recipient_id, :body)
    ");

    $stmt->execute([
        "recipient_id" => $user_id,
        "body" => $body,
    ]);

    header("Location: /messages/" . $user_id);
    exit();
}
