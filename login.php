

<?php
error_reporting(E_ALL);
ini_set('display_errors', 1); // Temporarily enable for debugging

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

include __DIR__ . '/db.php';

$data = json_decode(file_get_contents("php://input"));

if (!$data || !isset($data->username) || !isset($data->password)) {
    echo json_encode(["success" => false, "error" => "Invalid input."]);
    exit;
}

$username = $data->username;
$password = $data->password;

$stmt = $conn->prepare("SELECT password FROM users WHERE username = ?");
if (!$stmt) {
    echo json_encode(["success" => false, "error" => "Query preparation failed."]);
    exit;
}

$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->bind_result($hashed);
$stmt->fetch();
$stmt->close();

if ($hashed && password_verify($password, $hashed)) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "error" => "Invalid credentials."]);
}
?>

