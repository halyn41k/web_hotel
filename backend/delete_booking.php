<?php
header("Access-Control-Allow-Origin: http://localhost:8080");
header("Access-Control-Allow-Methods: POST, GET, DELETE, OPTIONS");
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
    echo json_encode(['error' => 'Помилка з\'єднання з базою даних.']);
    exit;
}

// Get the request payload
$data = json_decode(file_get_contents('php://input'), true);
$id = $data['id'];
$type = $data['type'];

if ($type == 'booking') {
    // Delete booking
    $query = "DELETE FROM bookings WHERE id = ?";
} else if ($type == 'wish') {
    // Delete wish
    $query = "DELETE FROM wishes WHERE id = ?";
}

$stmt = $conn->prepare($query);
$stmt->bind_param('i', $id);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['error' => 'Помилка при видаленні.']);
}

$stmt->close();
$conn->close();
?>
