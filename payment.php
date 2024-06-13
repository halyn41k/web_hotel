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

        // Перевірка наявності обов'язкових даних
        if (isset($data['selectedPaymentMethod']) && isset($data['cardNumber']) && isset($data['expiryDate']) && isset($data['cvv']) && isset($data['totalCost']) && isset($data['userId']) && isset($data['roomId'])) {
            $selectedPaymentMethod = $data['selectedPaymentMethod'];
            $cardNumber = $data['cardNumber'];
            $expiryDate = $data['expiryDate'];
            $cvv = $data['cvv'];
            $totalCost = $data['totalCost'];
            $userId = $data['userId'];
            $roomId = $data['roomId'];

            // Логіка для обробки платежу і зберігання даних в базі
            // Наприклад, можна виконати вставку у таблицю payments

            // Для прикладу, якщо ви хочете просто симулювати
// Логіка для обробки платежу і зберігання даних в базі
$stmt = $pdo->prepare("INSERT INTO payments (user_id, room_id, payment_method, total_cost) VALUES (?, ?, ?, ?)");
$stmt->execute([$userId, $roomId, $selectedPaymentMethod, $totalCost]);

// Отримання останнього ID вставленого запису
$paymentId = $pdo->lastInsertId();

// Логіка оновлення стану кімнати або резервації, якщо це потрібно
// Наприклад, оновлення стану заброньованості кімнати
$stmt = $pdo->prepare("UPDATE rooms SET available = available - 1 WHERE id = ?");
$stmt->execute([$roomId]);

echo json_encode(['success' => true, 'message' => 'Оплата успішно здійснена.']);

?>