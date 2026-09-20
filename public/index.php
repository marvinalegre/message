<?php

require __DIR__ . "/../src/db.php";
require __DIR__ . "/../src/auth.php";
require __DIR__ . "/../src/rate_limit.php";

if (getenv("APP_ENV") === "development") {
    session_save_path(__DIR__ . "/../data/sessions");
}
session_start();

$db = db();
$path = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);

$page = "signup";
if ($path === "/$page") {
    load($page);
}

$page = "login";
if ($path === "/$page") {
    load($page);
}

if ($path === "/logout" && $_SERVER["REQUEST_METHOD"] === "POST") {
    session_unset();
    session_destroy();

    header("Location: /login");
    exit();
}

if ($path === "/messages") {
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
}

if ($path === "/") {
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

    require __DIR__ . "/../views/header.php";
    echo "<main class='container'>";
    echo '<header><h1>Messages</h1>
    <form method="POST" action="/logout">
        <button class="secondary">Log out</button>
    </form></header>
    ';

    foreach ($messages as $message) {
        $class =
            $message["sender_id"] === $_SESSION["user_id"]
                ? "message mine"
                : "message";

        echo "<article class='$class'>";
        echo "<strong>" . htmlspecialchars($message["username"]) . "</strong>";
        echo "<p>" . htmlspecialchars($message["body"]) . "</p>";
        echo "</article>";
    }

    echo '
    <form method="POST" action="/messages">
        <textarea name="body" required></textarea>
        <button type="submit">Send</button>
    </form>

';

    echo "</main>";
    require __DIR__ . "/../views/footer.php";

    exit();
}

if (
    preg_match('#^/messages/(\d+)$#', $path, $matches) &&
    $_SERVER["REQUEST_METHOD"] === "POST"
) {
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

if (preg_match('#^/messages/(\d+)$#', $path, $matches)) {
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

    require __DIR__ . "/../views/header.php";

    echo "<main class='container'>";

    echo '
<header>
    <h1>Conversation with ' .
        htmlspecialchars($user["username"]) .
        '</h1>
    <a href="/messages" role="button" class="secondary">Back</a>
</header>
';

    foreach ($messages as $message) {
        $class =
            $message["sender_id"] === $_SESSION["user_id"]
                ? "message mine"
                : "message";

        echo "<article class='$class'>";
        echo "<strong>" . htmlspecialchars($message["username"]) . "</strong>";
        echo "<p>" . htmlspecialchars($message["body"]) . "</p>";
        echo "</article>";
    }

    echo '
    <form method="POST">
        <textarea name="body" required></textarea>
        <button type="submit">Send</button>
    </form>';

    echo "</main>";

    require __DIR__ . "/../views/footer.php";

    exit();
}

http_response_code(404);
echo "Not Found";

function script(string $page): string
{
    return '<script defer src="/assets/js/' . $page . '.js"></script>';
}

function css(string $page): string
{
    return '<link rel="stylesheet" href="/assets/css/' . $page . '.css">';
}

function view(string $name): void
{
    require __DIR__ . "/../views/$name.php";
}

function load(string $page): never
{
    require __DIR__ . "/../src/$page.php";

    exit();
}
