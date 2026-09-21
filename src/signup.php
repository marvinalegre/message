<?php
/** @var PDO $db */

if ($_SERVER["REQUEST_METHOD"] === "GET") {
    view("header");
    view("signup");
    view("footer");

    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"] ?? "");
    $password = $_POST["password"] ?? "";

    /* TODO: ask for a stronger password */
    if ($username === "" || $password === "") {
        echo "Username and password are required";
        exit();
    }

    if (!validUsername($username)) {
        echo "Invalid username";
        exit();
    }

    $stmt = $db->prepare(
        "INSERT INTO users (username, password_hash) VALUES (?, ?)",
    );

    try {
        $stmt->execute([$username, password_hash($password, PASSWORD_DEFAULT)]);

        header("FX-Redirect: /login");
        http_response_code(200);
        exit();
    } catch (PDOException $e) {
        if ($e->getCode() === "23000") {
            echo "Username already exists";
            exit();
        }

        /* TODO: catch this */
        throw $e;
    }
}

http_response_code(405);
echo "Method Not Allowed";
exit();

function validUsername(string $username): bool
{
    return preg_match('/^[a-zA-Z0-9_]{3,20}$/', $username) === 1;
}
