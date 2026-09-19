<?php

require __DIR__ . "/../src/db.php";
require __DIR__ . "/../src/auth.php";
require __DIR__ . "/../src/rate_limit.php";

session_start();

$db = db();

$path = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);

if ($path === "/signup") {
    require __DIR__ . "/../src/signup.php";
    exit();
}

if ($path === "/login" && $_SERVER["REQUEST_METHOD"] === "GET") { ?>
    <form method="POST">
        <input name="username" required>
        <input name="password" type="password" required>
        <button>Log in</button>
    </form>
    <?php exit();
}

if ($path === "/login" && $_SERVER["REQUEST_METHOD"] === "POST") {
    if (!rate_limit($db, "login:" . $_SERVER["REMOTE_ADDR"], 5, 60)) {
        http_response_code(429);
        echo "Too many requests";
        exit();
    }

    if (login($db, $_POST["username"], $_POST["password"])) {
        session_regenerate_id(true);
        $_SESSION["user_id"] = $db
            ->query(
                "SELECT id FROM users WHERE username = " .
                    $db->quote($_POST["username"]),
            )
            ->fetchColumn();

        header("Location: /");
        exit();
    }

    echo "Invalid username or password";
    exit();
}

if ($path === "/logout" && $_SERVER["REQUEST_METHOD"] === "POST") {
    session_unset();
    session_destroy();

    header("Location: /login");
    exit();
}

if ($path === "/messages") {
    require_auth();

    if ($_SESSION["user_id"] !== 1) {
        header("Location: /");
        exit();
    }

    echo '<form method="POST" action="/logout">';
    echo "<button>Log out</button>";
    echo "</form>";

    exit();
}

if ($path === "/") {
    require_auth();

    if ($_SESSION["user_id"] === 1) {
        header("Location: /messages");
        exit();
    }

    $stmt = $db->prepare("SELECT username FROM users WHERE id = ?");
    $stmt->execute([$_SESSION["user_id"]]);

    $username = $stmt->fetchColumn();

    echo "<h1>Hello, " . htmlspecialchars($username) . "</h1>";
    echo '<form method="POST" action="/logout">';
    echo "<button>Log out</button>";
    echo "</form>";

    exit();
}

http_response_code(404);
echo "Not Found";
