
<?php
if ($_SERVER["REQUEST_METHOD"] === "GET") { ?>
    <form method="POST">
        <input name="username" required>
        <input name="password" type="password" required>
        <button>Sign up</button>
    </form>
<?php exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"] ?? "");
    $password = $_POST["password"] ?? "";

    if ($username === "" || $password === "") {
        echo "Username and password are required";
        exit();
    }

    $stmt = $db->prepare(
        "INSERT INTO users (username, password_hash) VALUES (?, ?)",
    );

    try {
        $stmt->execute([$username, password_hash($password, PASSWORD_DEFAULT)]);

        header("Location: /login");
        exit();
    } catch (PDOException $e) {
        if ($e->getCode() === "23000") {
            echo "Username already exists";
            exit();
        }

        throw $e;
    }
}

http_response_code(405);
echo "Method Not Allowed";
