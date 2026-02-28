-- ─── Create database ──────────────────────────────────────────────────────────
CREATE DATABASE IF NOT EXISTS notes_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE notes_db;

-- ─── Notes table ──────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS notes (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    title      VARCHAR(255)  NOT NULL,
    content    TEXT          NOT NULL,
    created_at TIMESTAMP     DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP     DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- ─── Dedicated MySQL user (run as root) ───────────────────────────────────────
-- Replace 'your_password' with a strong password before running
CREATE USER IF NOT EXISTS 'notes_user'@'localhost' IDENTIFIED BY 'your_password';
GRANT SELECT, INSERT, UPDATE, DELETE ON notes_db.* TO 'notes_user'@'localhost';
FLUSH PRIVILEGES;

-- ─── Sample data (optional — delete before production) ────────────────────────
INSERT INTO notes (title, content) VALUES
    ('Welcome to LAMP Notes', 'This is your first note. Edit or delete it to get started.'),
    ('Setup Complete', 'Your LAMP stack is running. Apache, MySQL, and PHP are all working correctly.');
