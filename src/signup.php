<?php

if ($_SERVER["REQUEST_METHOD"] === "GET") {
    view("header");
    view("signup");
    view("footer");

    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"] ?? "");
    $password = $_POST["password"] ?? "";

    if ($username === "" || $password === "") {
        echo "Username and password are required";
        exit();
    }

    $db = db();
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
            echo '<small id="server-message">Username already exists</small>';
            exit();
        }

        throw $e;
    }
}

http_response_code(405);
echo "Method Not Allowed";
