<?php
require 'db.php';

// Only allow POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["status" => "error", "message" => "Method not allowed"]);
    exit;
}

// Decode JSON body
$data = json_decode(file_get_contents("php://input"), true);

// Validate required fields
if (empty($data['title']) || empty($data['content'])) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Title and content are required"]);
    exit;
}

$title   = trim($data['title']);
$content = trim($data['content']);

// Enforce reasonable length limits
if (strlen($title) > 255) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Title must be 255 characters or fewer"]);
    exit;
}

// Insert using prepared statement (SQL injection safe)
$stmt = $conn->prepare("INSERT INTO notes (title, content) VALUES (?, ?)");
$stmt->bind_param("ss", $title, $content);

if ($stmt->execute()) {
    http_response_code(201);
    echo json_encode([
        "status"  => "success",
        "message" => "Note created",
        "id"      => $conn->insert_id
    ]);
} else {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Failed to create note"]);
}

$stmt->close();
$conn->close();
?>
