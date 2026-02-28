<?php
require 'db.php';

// Only allow GET
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(["status" => "error", "message" => "Method not allowed"]);
    exit;
}

// Optional: fetch a single note by ID
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);  // Cast to int — prevents SQL injection

    if ($id <= 0) {
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "Invalid ID"]);
        exit;
    }

    $stmt = $conn->prepare("SELECT id, title, content, created_at FROM notes WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $note   = $result->fetch_assoc();

    if ($note) {
        echo json_encode(["status" => "success", "data" => $note]);
    } else {
        http_response_code(404);
        echo json_encode(["status" => "error", "message" => "Note not found"]);
    }

    $stmt->close();

} else {
    // Return all notes, newest first
    $result = $conn->query("SELECT id, title, content, created_at FROM notes ORDER BY created_at DESC");

    if ($result === false) {
        http_response_code(500);
        echo json_encode(["status" => "error", "message" => "Failed to retrieve notes"]);
        exit;
    }

    $notes = [];
    while ($row = $result->fetch_assoc()) {
        $notes[] = $row;
    }

    echo json_encode(["status" => "success", "data" => $notes]);
}

$conn->close();
?>
