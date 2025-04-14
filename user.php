<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

include('db.php'); // ✅ This should be your real db connection file

$data = json_decode(file_get_contents('php://input'), true);
$username = $data['username'] ?? '';

if (!$username) {
    echo json_encode(['success' => false, 'error' => 'Username is missing']);
    exit();
}

$stmt = $conn->prepare("SELECT username FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($user) {
    echo json_encode(['success' => true, 'data' => $user]);
} else {
    echo json_encode(['success' => false, 'error' => 'User not found.']);
}

$conn->close();
?>
