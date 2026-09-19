<?php

require __DIR__ . "/../src/db.php";
require __DIR__ . "/../src/auth.php";

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

if ($path === "/logout") {
    session_unset();
    session_destroy();

    header("Location: /login");
    exit();
}

if ($path === "/") {
    if (!isset($_SESSION["user_id"])) {
        header("Location: /login");
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
