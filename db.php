<?php
$conn = new mysqli("localhost", "root", "", "authdb");

if ($conn->connect_error) {
    die(json_encode(["success" => false, "error" => "Database connection failed."]));
}
?>
