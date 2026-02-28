<?php
require 'db.php';

// Only allow PUT
if ($_SERVER['REQUEST_METHOD'] !== 'PUT') {
    http_response_code(405);
    echo json_encode(["status" => "error", "message" => "Method not allowed"]);
    exit;
}

// Decode JSON body
$data = json_decode(file_get_contents("php://input"), true);

// Validate ID
if (empty($data['id']) || intval($data['id']) <= 0) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Valid note ID is required"]);
    exit;
}

// Validate fields
if (empty($data['title']) || empty($data['content'])) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Title and content are required"]);
    exit;
}

$id      = intval($data['id']);
$title   = trim($data['title']);
$content = trim($data['content']);

if (strlen($title) > 255) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Title must be 255 characters or fewer"]);
    exit;
}

// Confirm note exists before updating
$check = $conn->prepare("SELECT id FROM notes WHERE id = ?");
$check->bind_param("i", $id);
$check->execute();
$check->store_result();

if ($check->num_rows === 0) {
    http_response_code(404);
    echo json_encode(["status" => "error", "message" => "Note not found"]);
    $check->close();
    exit;
}
$check->close();

// Perform update
$stmt = $conn->prepare("UPDATE notes SET title = ?, content = ? WHERE id = ?");
$stmt->bind_param("ssi", $title, $content, $id);

if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Note updated"]);
} else {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Failed to update note"]);
}

$stmt->close();
$conn->close();
?>
