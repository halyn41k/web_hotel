<?php
header("Access-Control-Allow-Origin: http://localhost:8080");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$host = 'localhost';
$dbname = 'web_hotel';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Check if data is received
        $postData = json_decode(file_get_contents('php://input'), true);
        $response = null;

        // Debugging logs
        error_log('Received POST request: ' . json_encode($postData));

        if (isset($postData['email'], $postData['paymentMethod'])) {
            // Update payment method
            $paymentMethod = $postData['paymentMethod'];
            $stmt = $pdo->prepare("UPDATE users SET payment_method = ? WHERE email = ?");
            $stmt->execute([$paymentMethod, $postData['email']]);
            $response = ['success' => true];
        } elseif (isset($postData['email'])) {
            // Get user's payment method
            $stmt = $pdo->prepare("SELECT payment_method FROM users WHERE email = ?");
            $stmt->execute([$postData['email']]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($row) {
                $payment_method = $row['payment_method'];
                $response = ['success' => true, 'payment_method' => $payment_method];
            } else {
                $response = ['success' => false, 'error' => 'User not found.'];
            }
        } elseif (isset($postData['action']) && $postData['action'] === 'getBookings') {
            // Get user's bookings
            $stmt = $pdo->prepare("SELECT * FROM bookings WHERE email = ?");
            $stmt->execute([$postData['email']]);
            $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $response = ['success' => true, 'bookings' => $bookings];
        } else {
            $response = ['success' => false, 'error' => 'Invalid request.'];
        }

        echo json_encode($response);
    } else {
        echo json_encode(['success' => false, 'error' => 'Invalid request method.']);
    }
} catch (PDOException $e) {
    error_log('Database error: ' . $e->getMessage());
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>
