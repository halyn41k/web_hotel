<?php
// Enable error reporting for debugging
error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
ini_set('display_errors', 0);

// Response headers
header("Access-Control-Allow-Origin: http://localhost:8080");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

// Log the received data
$inputData = file_get_contents('php://input');
file_put_contents('payment_log.txt', date('Y-m-d H:i:s') . " - Received data: " . $inputData . PHP_EOL, FILE_APPEND);

// Database settings
$host = 'localhost';
$dbname = 'hotel_w';
$username = 'root';
$password = '';

// Connect to the database
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => 'Database connection failed', 'details' => $e->getMessage()]);
    exit();
}

// Encryption configuration
$encryption_key = 'your-encryption-key'; // Replace this with a securely generated key
$encryption_method = 'AES-256-CBC';

// Function to securely encrypt data
function encryptData($data, $key, $method) {
    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($method));
    $encryptedData = openssl_encrypt($data, $method, $key, 0, $iv);
    return base64_encode($encryptedData . '::' . $iv);
}

// Process the input data
$data = json_decode($inputData, true);

// Validate input data
if (!isset($data['selectedPaymentMethod'], $data['totalCost'], $data['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'Invalid input data']);
    exit();
}

// Additional validation for card payments
if ($data['selectedPaymentMethod'] === 'card') {
    if (!isset($data['cardNumber'], $data['expiryDate'], $data['cvv']) ||
        empty($data['cardNumber']) || empty($data['expiryDate']) || empty($data['cvv'])) {
        echo json_encode(['success' => false, 'error' => 'Invalid card details']);
        exit();
    }

    // Mask and encrypt card number
    $cardNumber = $data['cardNumber'];
    $maskedCardNumber = substr($cardNumber, 0, 3) . str_repeat('*', strlen($cardNumber) - 6) . substr($cardNumber, -3);
    $encryptedCardNumber = encryptData($maskedCardNumber, $encryption_key, $encryption_method);

    // Encrypt CVV
    $cvv = $data['cvv'];
    $encryptedCvv = encryptData($cvv, $encryption_key, $encryption_method);

    $data['cardNumber'] = $encryptedCardNumber;
    $data['cvv'] = $encryptedCvv;
} else {
    // If payment method is not card, set these values to null
    $data['cardNumber'] = null;
    $data['expiryDate'] = null;
    $data['cvv'] = null;
}

// Save the payment data to the database
try {
    $stmt = $pdo->prepare("INSERT INTO payments (booking_id, payment_method, card_number, expiry_date, cvv, amount) VALUES (:booking_id, :payment_method, :card_number, :expiry_date, :cvv, :amount)");
    $stmt->execute([
        ':booking_id' => $data['booking_id'],
        ':payment_method' => $data['selectedPaymentMethod'],
        ':card_number' => $data['cardNumber'],
        ':expiry_date' => $data['expiryDate'],
        ':cvv' => $data['cvv'],
        ':amount' => $data['totalCost']
    ]);

    $payment_id = $pdo->lastInsertId(); // Retrieve the last inserted payment_id

    // Update the users table with the payment_id
    $stmt = $pdo->prepare("UPDATE users SET payment_id = :payment_id WHERE id = :user_id");
    $stmt->execute([
        ':payment_id' => $payment_id,
        ':user_id' => $data['user_id']
    ]);

    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    // Log PDO error
    file_put_contents('payment_error_log.txt', date('Y-m-d H:i:s') . " - PDO error: " . $e->getMessage() . PHP_EOL, FILE_APPEND);
    echo json_encode(['success' => false, 'error' => 'Database query failed', 'details' => $e->getMessage()]);
    exit();
}
?>
