<?php
/** @var string $scripts */
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($title ?? "Messages") ?></title>

    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.blue.min.css"
    >
    <link rel="stylesheet" href="/assets/css/app.css">

    <script defer src="/assets/vendor/the-fixi-project/fixi-0.9.4.js"></script>
    <script defer src="/assets/js/app.js"></script>
    <?= $scripts ?>
</head>
<body>
