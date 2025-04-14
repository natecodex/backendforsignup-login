<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// Include database connection
include('db.php');

// Get the username from the POST body
$data = json_decode(file_get_contents('php://input'), true);
$username = $data['username'] ?? '';

if ($username) {
    // Delete user query
    $sql = "DELETE FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $username);

    if ($stmt->execute()) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "error" => "Failed to delete account."]);
    }

    $stmt->close();
} else {
    echo json_encode(["success" => false, "error" => "Username is missing."]);
}

$conn->close();
?>
