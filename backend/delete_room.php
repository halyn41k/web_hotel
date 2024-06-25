<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header("Access-Control-Allow-Origin: http://localhost:8080");
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
    echo json_encode(['success' => false, 'error' => 'Помилка з\'єднання з базою даних: ' . $conn->connect_error]);
    exit;
}

// Read POST data
$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['id'])) {
    $id = $conn->real_escape_string($data['id']);
    
    // Start transaction
    $conn->begin_transaction();
    try {
        // Delete related bookings
        $deleteBookingsQuery = "DELETE FROM bookings WHERE room_id = '$id'";
        $conn->query($deleteBookingsQuery);

        // Delete related images
        $deleteImagesQuery = "DELETE FROM images WHERE room_id = '$id'";
        $conn->query($deleteImagesQuery);

        // Delete the room
        $deleteRoomQuery = "DELETE FROM rooms WHERE id = '$id'";
        $conn->query($deleteRoomQuery);

        // Commit transaction
        $conn->commit();
        echo json_encode(['success' => true]);
    } catch (mysqli_sql_exception $exception) {
        // Rollback transaction
        $conn->rollback();
        echo json_encode(['success' => false, 'error' => 'Помилка видалення кімнати: ' . $exception->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Не вказано ID кімнати.']);
}

$conn->close();
?>
