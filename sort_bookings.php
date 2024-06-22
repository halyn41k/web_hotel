<?php
header("Access-Control-Allow-Origin: http://localhost:8080");
header("Access-Control-Allow-Methods: POST, GET, DELETE, OPTIONS");
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
    echo json_encode(['error' => 'Помилка з\'єднання з базою даних.']);
    exit;
}

$field = isset($_GET['field']) ? $_GET['field'] : 'id';
$validFields = ['id', 'checkin', 'checkout', 'room_id', 'user_id', 'price'];

if (!in_array($field, $validFields)) {
    echo json_encode(['error' => 'Недійсне поле сортування.']);
    exit;
}

// Fetch sorted bookings
$bookingsQuery = "SELECT * FROM bookings WHERE checkin >= CURDATE() ORDER BY $field";
$bookingsResult = $conn->query($bookingsQuery);

$bookings = [];

if ($bookingsResult->num_rows > 0) {
    while ($row = $bookingsResult->fetch_assoc()) {
        $bookings[] = $row;
    }
}

echo json_encode(['bookings' => $bookings]);

$conn->close();
?>
