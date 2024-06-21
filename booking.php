<?php
header("Access-Control-Allow-Origin: http://localhost:8080");
header("Access-Control-Allow-Methods: POST, GET, DELETE, OPTIONS");
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

            // Перевірити, чи є в користувача активні бронювання
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM bookings WHERE user_id = ? AND checkout >= CURDATE()");
            $stmt->execute([$user_id]);
            $activeBookingsCount = $stmt->fetchColumn();

            if ($activeBookingsCount > 0) {
                echo json_encode(['success' => false, 'error' => 'У вас вже є активне бронювання. Неможливо здійснити нове бронювання.']);
                exit();
            }

            // Вставити бронювання в базу даних
            $stmt = $pdo->prepare("INSERT INTO bookings (user_id, room_id, checkin, checkout, adults, children, paid) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$user_id, $room_id, $checkin, $checkout, $adults, $children, 0]);

            // Оновити доступність кімнати
            $stmt = $pdo->prepare("UPDATE rooms SET available = available - 1 WHERE id = ?");
            $stmt->execute([$room_id]);

            $booking_id = $pdo->lastInsertId();

            echo json_encode(['success' => true, 'message' => 'Бронювання успішно завершено.', 'booking_id' => $booking_id]);
        } elseif (isset($data['booking_id']) && isset($data['status'])) {
            $booking_id = $data['booking_id'];
            $status = $data['status'];

            // Оновити статус бронювання
            $stmt = $pdo->prepare("UPDATE bookings SET paid = ? WHERE id = ?");
            $stmt->execute([$status === 'paid' ? 1 : 0, $booking_id]);

            echo json_encode(['success' => true, 'message' => 'Статус бронювання успішно оновлено.']);
        } else {
            echo json_encode(['success' => false, 'error' => 'Некоректні дані.']);
        }
    } elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
        if (isset($_GET['user_id'])) {
            $user_id = $_GET['user_id'];
            // Об'єднати таблиці бронювань та кімнат для отримання необхідних деталей
            $stmt = $pdo->prepare("SELECT bookings.*, rooms.name AS room_name, rooms.price AS room_price FROM bookings JOIN rooms ON bookings.room_id = rooms.id WHERE bookings.user_id = ?");
            $stmt->execute([$user_id]);
            $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode(['success' => true, 'bookings' => $bookings]);
        } else {
            $stmt = $pdo->query("SELECT * FROM rooms WHERE available > 0");
            $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode(['success' => true, 'rooms' => $rooms]);
        }
    } elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
        $data = json_decode(file_get_contents('php://input'), true);
        if (isset($data['id'])) {
            $id = $data['id'];

            // Отримати ідентифікатор кімнати перед видаленням бронювання
            $stmt = $pdo->prepare("SELECT room_id FROM bookings WHERE id = ?");
            $stmt->execute([$id]);
            $room_id = $stmt->fetchColumn();

            // Видалити бронювання з бази даних
            $stmt = $pdo->prepare("DELETE FROM bookings WHERE id = ?");
            $stmt->execute([$id]);

            // Оновити доступність кімнати
            if ($room_id) {
                $stmt = $pdo->prepare("UPDATE rooms SET available = available + 1 WHERE id = ?");
                $stmt->execute([$room_id]);
            }

            echo json_encode(['success' => true, 'message' => 'Бронювання успішно видалено.']);
        } else {
            echo json_encode(['success' => false, 'error' => 'Некоректні дані.']);
        }
    }
} catch (PDOException $e) {
    echo json_encode(['error' => 'Помилка: ' . $e->getMessage()]);
}
?>
