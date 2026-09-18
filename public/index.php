<?php

require __DIR__ . "/../src/db.php";
require __DIR__ . "/../src/auth.php";

$db = db();

$userId = createUser($db, "marvin", "test-password");

echo "Created user: $userId";
