<?php
// Включення виведення всіх помилок
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Заголовки відповіді
header("Access-Control-Allow-Origin: http://localhost:8080");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

// Параметри підключення до бази даних
$host = 'localhost';
$dbname = 'web_hotel';
$username = 'root';
$password = '';

try {
    // Підключення до бази даних
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Database connection established.\n";

    // Перевірка, чи є POST запит
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Отримання даних з JSON
        $rawData = file_get_contents('php://input');
        $data = json_decode($rawData, true);

        // Перевірка, чи вдалося розпакувати JSON
        if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
            echo json_encode(["success" => false, "error" => "Invalid JSON data received"]);
            exit;
        }

        // Виведення отриманих даних для дебагу
        echo "Received data:\n";
        var_dump($rawData); // Виведення raw даних
        var_dump($data); // Виведення розпакованих даних для перевірки

        // Валідація даних
        if (isset($data['payment_method'], $data['card_number'], $data['expiry_date'], $data['cvv'], $data['amount'], $data['booking_id'])) {
            $paymentMethod = $data['payment_method'];
            $cardNumber = $data['card_number'];
            $expiryDate = $data['expiry_date'];
            $cvv = $data['cvv'];
            $totalCost = $data['amount'];
            $bookingId = $data['booking_id'];

            echo "Validated data:\n";
            echo "Payment Method: $paymentMethod\n";
            echo "Card Number: $cardNumber\n";
            echo "Expiry Date: $expiryDate\n";
            echo "CVV: $cvv\n";
            echo "Total Cost: $totalCost\n";
            echo "Booking ID: $bookingId\n";

            // Валідація даних
            if ($paymentMethod == 'card') {
                if (!preg_match('/^\d{16}$/', str_replace(' ', '', $cardNumber))) {
                    echo "Invalid card number format.\n";
                    echo json_encode(["success" => false, "error" => "Invalid card number format"]);
                    exit;
                }
                if (!preg_match('/^(0[1-9]|1[0-2])\/\d{2}$/', $expiryDate)) {
                    echo "Invalid expiry date format.\n";
                    echo json_encode(["success" => false, "error" => "Invalid expiry date format"]);
                    exit;
                }
                if (!preg_match('/^\d{3}$/', $cvv)) {
                    echo "Invalid CVV format.\n";
                    echo json_encode(["success" => false, "error" => "Invalid CVV format"]);
                    exit;
                }
            }

            // Збереження інформації про платіж
            $stmt = $pdo->prepare("INSERT INTO payments (booking_id, payment_method, card_number, expiry_date, cvv, amount) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$bookingId, $paymentMethod, $cardNumber, $expiryDate, $cvv, $totalCost]);
            echo "Payment record inserted successfully.\n";

            // Оновлення статусу оплати в бронюванні
            $stmt = $pdo->prepare("UPDATE bookings SET paid = 1 WHERE id = ?");
            $stmt->execute([$bookingId]);
            echo "Booking status updated successfully.\n";

            echo json_encode(["success" => true]);
        } else {
            echo json_encode(["success" => false, "error" => "Invalid data"]);
        }
    }
} catch (PDOException $e) {
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>
