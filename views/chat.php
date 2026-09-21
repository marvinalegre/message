<?php
global $user;
global $messages;
?>

<main class="container">
  <header>
    <h1><?= htmlspecialchars($user["username"]) ?></h1>
    <a href="/messages" role="button" class="secondary">Back</a>
  </header>

  <?php foreach ($messages as $message): ?>
  <?php $class =
      $message["sender_id"] === $_SESSION["user_id"]
          ? "message mine"
          : "message"; ?>

  <article class="<?= $class ?>">
    <strong><?= htmlspecialchars($message["username"]) ?></strong>
    <p><?= htmlspecialchars($message["body"]) ?></p>
  </article>
  <?php endforeach; ?>

  <form method="POST">
    <textarea name="body" required></textarea>
    <button type="submit">Send</button>
  </form>
</main>
