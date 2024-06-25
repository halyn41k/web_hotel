<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

$host = 'localhost';
$dbname = 'hotel_w';
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
    $name = $data['name'];
    $email = $data['email'];
    $message = $data['message'];

    $stmt = $conn->prepare("INSERT INTO wishes (name, email, message) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $message);

    if ($stmt->execute()) {
        echo json_encode(['success' => 'Wish submitted successfully.']);
    } else {
        echo json_encode(['error' => 'Error: ' . $stmt->error]);
    }

    $stmt->close();
} else {
    echo json_encode(['error' => 'Invalid input.']);
}

$conn->close();
?>
