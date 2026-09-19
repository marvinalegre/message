<?php

function db(): PDO
{
    static $db;

    if ($db === null) {
        $db = new PDO("sqlite:" . __DIR__ . "/../data/message.db");
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $db->exec("PRAGMA foreign_keys = ON");
    }

    return $db;
}
