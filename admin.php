<?php
header("Access-Control-Allow-Origin: http://localhost:8080");
header("Access-Control-Allow-Methods: POST, GET, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

$host = 'localhost';
$dbname = 'web_hotel';
$username = 'root';
$password = '';

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    echo json_encode(['error' => 'Помилка з\'єднання з базою даних.']);
    exit;
}

$bookingsQuery = "SELECT * FROM bookings WHERE checkin >= CURDATE()";
$bookingsResult = $conn->query($bookingsQuery);

$wishesQuery = "SELECT * FROM wishes";
$wishesResult = $conn->query($wishesQuery);

$bookings = [];
$wishes = [];

if ($bookingsResult->num_rows > 0) {
    while ($row = $bookingsResult->fetch_assoc()) {
        $bookings[] = $row;
    }
}

if ($wishesResult->num_rows > 0) {
    while ($row = $wishesResult->fetch_assoc()) {
        $wishes[] = $row;
    }
}

echo json_encode([
    'bookings' => $bookings,
    'wishes' => $wishes,
]);

$conn->close();
?>
