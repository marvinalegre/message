<?php
/** @var PDO $db */

if ($_SERVER["REQUEST_METHOD"] === "GET") {
    view("header");
    view("login");
    view("footer");

    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
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
