<?php
/** @var PDO $db */

require_auth();

if ($_SERVER["REQUEST_METHOD"] !== "GET") {
    http_response_code(405);
    exit();
}

$user_id = $_SESSION["user_id"];
$other_user_id = (int) ($_GET["user"] ?? 0);

if ($other_user_id <= 0) {
    http_response_code(400);
    exit();
}

$stmt = $db->prepare("
    SELECT
        m.sender_id,
        u.username,
        m.body
    FROM messages m
    JOIN users u ON u.id = m.sender_id
    WHERE
        (m.sender_id = :user_id AND m.recipient_id = :other_user_id)
        OR
        (m.sender_id = :other_user_id AND m.recipient_id = :user_id)
    ORDER BY m.created_at ASC
");

$stmt->execute([
    "user_id" => $user_id,
    "other_user_id" => $other_user_id,
]);

$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php foreach ($messages as $message): ?>
    <?php
    $class =
        $message["sender_id"] === $user_id
            ? "message mine"
            : "message";
    ?>

    <article class="<?= $class ?>">
        <strong><?= htmlspecialchars($message["username"]) ?></strong>
        <p><?= htmlspecialchars($message["body"]) ?></p>
    </article>
<?php endforeach; exit()?>
