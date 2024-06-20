<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

$host = 'localhost';
$dbname = 'web_hotel';
$username = 'root';
$password = '';

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    echo json_encode(['error' => 'Database connection error.']);
    exit;
}

// Get the posted data
$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['name']) && isset($data['email']) && isset($data['message'])) {
    $name = $conn->real_escape_string($data['name']);
    $email = $conn->real_escape_string($data['email']);
    $message = $conn->real_escape_string($data['message']);

    $query = "INSERT INTO wishes (name, email, message) VALUES ('$name', '$email', '$message')";

    if ($conn->query($query) === TRUE) {
        echo json_encode(['success' => 'Wish submitted successfully.']);
    } else {
        echo json_encode(['error' => 'Error: ' . $query . '<br>' . $conn->error]);
    }
} else {
    echo json_encode(['error' => 'Invalid input.']);
}

$conn->close();
?>
