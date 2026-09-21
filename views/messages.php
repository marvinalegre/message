<?php
global $conversations; ?>

<main class="container">
    <header>
        <h1>Messages</h1>

        <form method="POST" action="/logout">
            <button class="secondary">Log out</button>
        </form>
    </header>

    <?php foreach ($conversations as $conversation): ?>
        <article>
            <h3>
                <a href="/messages/<?= (int) $conversation["id"] ?>">
                    <?= htmlspecialchars($conversation["username"]) ?>
                </a>
            </h3>

            <p><?= htmlspecialchars($conversation["body"]) ?></p>
        </article>
    <?php endforeach; ?>
</main>
