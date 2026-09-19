-- Migration: add_rate_limiter
-- UP
CREATE TABLE rate_limits (
  key TEXT PRIMARY KEY,
  attempts INTEGER NOT NULL,
  window_start INTEGER NOT NULL
);

-- DOWN
DROP TABLE rate_limits;
