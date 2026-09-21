<?php

require_auth();

if ($_SERVER["REQUEST_METHOD"] === "GET") {
    if ($_SESSION["user_id"] !== 1) {
        header("Location: /");
        exit();
    }

    $db = db();
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

    require __DIR__ . "/../views/header.php";

    echo "<main class='container'>";

    echo '<header><h1>Messages</h1>
    <form method="POST" action="/logout">
        <button class="secondary">Log out</button>
    </form></header>
        ';

    foreach ($conversations as $conversation) {
        echo "<article>";
        echo '<h3><a href="/messages/' . (int) $conversation["id"] . '">';
        echo htmlspecialchars($conversation["username"]);
        echo "</a></h3>";
        echo "<p>";
        echo htmlspecialchars($conversation["body"]);
        echo "</p>";
        echo "</article>";
    }

    echo '
';

    echo "</main>";

    require __DIR__ . "/../views/footer.php";

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

    $db = db();
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
