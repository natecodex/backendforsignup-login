<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// Get the username from the query string
$username = $_GET['username'] ?? '';

include('db.php'); // ✅ Make sure this is the correct file and path

if (!$username) {
    echo json_encode(["success" => false, "error" => "Username is required."]);
    exit();
}

// Prepare and run the query
$stmt = $conn->prepare("SELECT username FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($user) {
    echo json_encode(["success" => true, "data" => $user]);
} else {
    echo json_encode(["success" => false, "error" => "User not found."]);
}

$conn->close();
?>
