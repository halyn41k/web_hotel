<?php
header("Access-Control-Allow-Origin: http://localhost:8080");
header("Access-Control-Allow-Methods: POST, GET, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

$host = 'localhost';
$dbname = 'hotel_w';
$username = 'root';
$password = '';

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$data = json_decode(file_get_contents("php://input"), true);

$name = $data['name'];
$surname = $data['surname'];
$email = $data['email'];
$password = password_hash($data['password'], PASSWORD_DEFAULT);

$query = "INSERT INTO users (name, surname, phone, email, password, role) VALUES ('$name', '$surname', '', '$email', '$password', 'admin')";

if ($conn->query($query) === TRUE) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["error" => "Error adding admin: " . $conn->error]);
}

$conn->close();
?>
