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

$conn->autocommit(FALSE); // Disable autocommit mode

try {
    if ($type == 'booking') {
        // Remove foreign key references in users table before deleting the booking
        $updateUserQuery = "UPDATE users SET booking_id = NULL WHERE booking_id = ?";
        $stmt = $conn->prepare($updateUserQuery);
        $stmt->bind_param('i', $id);
        if (!$stmt->execute()) {
            throw new Exception('Помилка при оновленні користувачів.');
        }
        $stmt->close();

        // Delete booking
        $deleteBookingQuery = "DELETE FROM bookings WHERE id = ?";
        $stmt = $conn->prepare($deleteBookingQuery);
        $stmt->bind_param('i', $id);
        if (!$stmt->execute()) {
            throw new Exception('Помилка при видаленні бронювання.');
        }
    } else if ($type == 'wish') {
        // Delete wish
        $deleteWishQuery = "DELETE FROM wishes WHERE id = ?";
        $stmt = $conn->prepare($deleteWishQuery);
        $stmt->bind_param('i', $id);
        if (!$stmt->execute()) {
            throw new Exception('Помилка при видаленні бажання.');
        }
    }

    $conn->commit(); // Commit the transaction
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    $conn->rollback(); // Rollback the transaction on error
    echo json_encode(['error' => $e->getMessage()]);
}

$stmt->close();
$conn->close();
?>
