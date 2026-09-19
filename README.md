# Message

A tiny personal messaging app built with native PHP.

## Stack

- PHP
- SQLite
- PHP sessions
- Pico CSS
- Cloudflare Tunnel

## Features

- User signup
- User login
- Session-based authentication
- Logout
- User-to-user messaging
- Conversation view
- Message timestamps
- Automatic cleanup of old messages

## Development

```bash
git clone <repo-url>
cd message
php -S localhost:8000 -t public
```

Then open:

```text
http://localhost:8000
```

## Structure

```text
public/
└── index.php

src/
├── auth.php
├── db.php
└── signup.php

data/
└── database.sqlite
```

## Deployment

Production runs behind **nginx** and a **Cloudflare Tunnel**.
