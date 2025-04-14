<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["success" => false, "error" => "Invalid request method"]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['username']) || !isset($data['password'])) {
    echo json_encode(["success" => false, "error" => "Missing fields"]);
    exit;
}

$username = $data['username'];
$password = password_hash($data['password'], PASSWORD_DEFAULT);

// ✅ Connect to database
$conn = new mysqli("localhost", "root", "", "authdb");
if ($conn->connect_error) {
    echo json_encode(["success" => false, "error" => "DB error"]);
    exit;
}

// ✅ Create table if not exists (you can remove this in production)
$conn->query("CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) UNIQUE,
    password VARCHAR(255)
)");

$stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
$stmt->bind_param("ss", $username, $password);

if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "error" => "Username might already exist"]);
}

$stmt->close();
$conn->close();
