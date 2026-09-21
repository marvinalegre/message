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
    require __DIR__ . "/../src/$page.php";
    exit();
}

$page = "login";
if ($path === "/$page") {
    require __DIR__ . "/../src/$page.php";
    exit();
}

if ($path === "/logout" && $_SERVER["REQUEST_METHOD"] === "POST") {
    session_unset();
    session_destroy();

    header("Location: /login");
    exit();
}

$page = "messages";
if ($path === "/$page") {
    require __DIR__ . "/../src/$page.php";
    exit();
}

$page = "home";
if ($path === "/") {
    require __DIR__ . "/../src/$page.php";
    exit();
}

$page = "chat";
if (preg_match('#^/messages/(\d+)$#', $path, $matches)) {
    require __DIR__ . "/../src/$page.php";
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
