<?php
global $messages; ?>

<main class="container">
  <header>
    <h1>marvin</h1>

    <form method="POST" action="/logout">
      <button class="secondary">Log out</button>
    </form>
  </header>

  <section id="messages">
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
  </section>

  <form method="POST" action="/messages">
    <textarea name="body" required></textarea>
    <button type="submit">Send</button>
  </form>
</main>
