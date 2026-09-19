# Message

A simple personal messaging app built with native PHP.

Live at: **message.marvinalegre.dev**

## Stack

- PHP
- SQLite
- Composer
- PHP sessions
- Cloudflare Tunnel

## Features

- User signup
- User login
- Session-based authentication
- Logout
- Basic messaging

## Development

```bash
git clone <repo-url>
cd message
composer install
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
```

## Status

Work in progress.
