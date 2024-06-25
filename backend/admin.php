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

// Fetch admin user details from 'users' table
$adminQuery = "SELECT * FROM users WHERE role = 'admin' LIMIT 1"; // Assuming there's only one admin
$adminResult = $conn->query($adminQuery);

if ($adminResult->num_rows > 0) {
    $admin = $adminResult->fetch_assoc();
} else {
    echo json_encode(['error' => 'Адміністратор не знайдений.']);
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
} else {
    echo json_encode(['error' => 'Немає активних бронювань.']);
    exit;
}

if ($wishesResult->num_rows > 0) {
    while ($row = $wishesResult->fetch_assoc()) {
        $wishes[] = $row;
    }
} else {
    echo json_encode(['error' => 'Немає побажань користувачів.']);
    exit;
}

echo json_encode([
    'admin' => $admin,
    'bookings' => $bookings,
    'wishes' => $wishes,
]);

$conn->close();
?>
