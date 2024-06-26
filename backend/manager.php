<?php
header("Access-Control-Allow-Origin: http://localhost:8080");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

// Database connection settings
$host = 'localhost';
$dbname = 'hotel_w';
$username = 'root';
$password = '';

// Create database connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    echo json_encode(['error' => 'Помилка з\'єднання з базою даних.']);
    exit;
}

// Fetch manager user details from 'users' table
$managerQuery = "SELECT * FROM users WHERE role = 'manager' LIMIT 1"; // Assuming there's only one manager
$managerResult = $conn->query($managerQuery);

if ($managerResult->num_rows > 0) {
    $manager = $managerResult->fetch_assoc();
} else {
    echo json_encode(['error' => 'Менеджер не знайдений.']);
    exit;
}

// Fetch active bookings
$bookingsQuery = "SELECT 
    bookings.id, 
    bookings.user_id, 
    bookings.room_id, 
    bookings.checkin, 
    bookings.checkout, 
    bookings.price, 
    bookings.created_at, 
    users.name AS user_name, 
    users.surname AS user_surname, 
    rooms.name AS room_name 
FROM bookings 
JOIN users ON bookings.user_id = users.id 
JOIN rooms ON bookings.room_id = rooms.id 
WHERE checkin >= CURDATE()";
$bookingsResult = $conn->query($bookingsQuery);

// Fetch wishes
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
    'manager' => $manager,
    'bookings' => $bookings,
    'wishes' => $wishes,
]);

$conn->close();
?>
