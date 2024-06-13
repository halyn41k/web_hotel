<?php
header("Access-Control-Allow-Origin: http://localhost:8080");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

$host = 'localhost';
$dbname = 'web_hotel';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = json_decode(file_get_contents('php://input'), true);

        if (isset($data['checkin']) && isset($data['checkout']) && isset($data['adults']) && isset($data['children']) && isset($data['user_id']) && isset($data['room_id'])) {
            $checkin = $data['checkin'];
            $checkout = $data['checkout'];
            $adults = $data['adults'];
            $children = $data['children'];
            $user_id = $data['user_id'];
            $room_id = $data['room_id'];

            // Insert booking into database
            $stmt = $pdo->prepare("INSERT INTO bookings (user_id, room_id, checkin, checkout, adults, children) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$user_id, $room_id, $checkin, $checkout, $adults, $children]);

            // Update room availability
            $stmt = $pdo->prepare("UPDATE rooms SET available = available - 1 WHERE id = ?");
            $stmt->execute([$room_id]);

            echo json_encode(['success' => true, 'message' => 'Бронювання успішно завершено.']);
        } else {
            echo json_encode(['success' => false, 'error' => 'Некоректні дані.']);
        }
    } elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $stmt = $pdo->query("SELECT * FROM rooms WHERE available > 0");
        $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['success' => true, 'rooms' => $rooms]);
    }
} catch (PDOException $e) {
    echo json_encode(['error' => 'Помилка: ' . $e->getMessage()]);
}
?>
