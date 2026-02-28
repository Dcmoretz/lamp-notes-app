<?php
require 'db.php';

// Only allow DELETE
if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
    http_response_code(405);
    echo json_encode(["status" => "error", "message" => "Method not allowed"]);
    exit;
}

// Get ID from query string: DELETE /api/delete.php?id=5
if (!isset($_GET['id']) || intval($_GET['id']) <= 0) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Valid note ID is required"]);
    exit;
}

$id = intval($_GET['id']);

// Confirm note exists before deleting
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

// Perform delete
$stmt = $conn->prepare("DELETE FROM notes WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Note deleted"]);
} else {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Failed to delete note"]);
}

$stmt->close();
$conn->close();
?>
