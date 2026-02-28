<?php
// ─── Database Configuration ───────────────────────────────────────────────────
// Update these values to match your MySQL setup
define('DB_HOST', 'localhost');
define('DB_USER', 'notes_user');       // MySQL username
define('DB_PASS', 'your_password');    // MySQL password
define('DB_NAME', 'notes_db');         // Database name

// ─── Connection ───────────────────────────────────────────────────────────────
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Database connection failed: " . $conn->connect_error]);
    exit;
}

$conn->set_charset("utf8mb4");

// ─── JSON response helper ─────────────────────────────────────────────────────
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type");
?>
