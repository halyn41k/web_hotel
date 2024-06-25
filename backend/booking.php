<?php
header("Access-Control-Allow-Origin: http://localhost:8080");
header("Access-Control-Allow-Methods: POST, GET, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

$host = 'localhost';
$dbname = 'hotel_w';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = json_decode(file_get_contents('php://input'), true);

        if (isset($data['checkin']) && isset($data['checkout']) && isset($data['user_id']) && isset($data['room_id']) && isset($data['price'])) {
            $checkin = $data['checkin'];
            $checkout = $data['checkout'];
            $user_id = $data['user_id'];
            $room_id = $data['room_id'];
            $price = $data['price'];

            // Check if the user has active bookings
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM bookings WHERE user_id = ? AND checkout >= CURDATE() AND is_deleted = 0");
            $stmt->execute([$user_id]);
            $activeBookingsCount = $stmt->fetchColumn();

            if ($activeBookingsCount > 0) {
                echo json_encode(['success' => false, 'error' => 'У вас вже є активне бронювання. Неможливо здійснити нове бронювання.']);
                exit();
            }

            // Insert booking into database
            $stmt = $pdo->prepare("INSERT INTO bookings (user_id, room_id, checkin, checkout, price) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$user_id, $room_id, $checkin, $checkout, $price]);

            $booking_id = $pdo->lastInsertId();

            // Update room availability
            $stmt = $pdo->prepare("UPDATE rooms SET availability = 'Заброньовано' WHERE id = ?");
            $stmt->execute([$room_id]);

            // Update booking_id in users table
            $stmt = $pdo->prepare("UPDATE users SET booking_id = ? WHERE id = ?");
            $stmt->execute([$booking_id, $user_id]);

            echo json_encode(['success' => true, 'message' => 'Бронювання успішно завершено.', 'booking_id' => $booking_id]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Некоректні дані.']);
        }

    } elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
        if (isset($_GET['user_id'])) {
            $user_id = $_GET['user_id'];
            
            // Fetch bookings along with room details
            $stmt = $pdo->prepare("
                SELECT bookings.*, rooms.name AS room_name, rooms.price AS room_price 
                FROM bookings 
                JOIN rooms ON bookings.room_id = rooms.id 
                WHERE bookings.user_id = ? AND bookings.is_deleted = 0
            ");
            $stmt->execute([$user_id]);
            $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode(['success' => true, 'bookings' => $bookings]);
        } else {
            // Fetch available rooms
            $stmt = $pdo->query("SELECT * FROM rooms WHERE availability = 'Вільно'");
            $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode(['success' => true, 'rooms' => $rooms]);
        }

    } elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
        // Handling DELETE requests (for soft deleting bookings)

        $data = json_decode(file_get_contents('php://input'), true);
        if (isset($data['id'])) {
            $id = $data['id'];

            // Retrieve room_id before soft deleting the booking
            $stmt = $pdo->prepare("SELECT room_id FROM bookings WHERE id = ?");
            $stmt->execute([$id]);
            $room_id = $stmt->fetchColumn();

            // Soft delete the booking by setting is_deleted = 1
            $stmt = $pdo->prepare("UPDATE bookings SET is_deleted = 1 WHERE id = ?");
            $stmt->execute([$id]);

            // Update room availability if room_id is valid
            if ($room_id) {
                $stmt = $pdo->prepare("UPDATE rooms SET availability = 'Вільно' WHERE id = ?");
                $stmt->execute([$room_id]);
            }

            echo json_encode(['success' => true, 'message' => 'Бронювання успішно видалено (soft delete).']);
        } else {
            echo json_encode(['success' => false, 'error' => 'Некоректні дані для видалення бронювання.']);
        }

    }
} catch (PDOException $e) {
    echo json_encode(['error' => 'Помилка: ' . $e->getMessage()]);
}
?>