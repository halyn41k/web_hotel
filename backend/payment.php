<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Заголовки відповіді
header("Access-Control-Allow-Origin: http://localhost:8080");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

// Логування отриманих даних
$inputData = file_get_contents('php://input');
file_put_contents('payment_log.txt', date('Y-m-d H:i:s') . " - Received data: " . $inputData . PHP_EOL, FILE_APPEND);

// Налаштування бази даних
$host = 'localhost';
$dbname = 'hotel_w';
$username = 'root';
$password = '';

// Підключення до бази даних
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => 'Database connection failed']);
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

// Обробка даних з запиту
$data = json_decode($inputData, true);

// Перевірка отриманих даних
if (!isset($data['selectedPaymentMethod'], $data['totalCost'])) {
    echo json_encode(['success' => false, 'error' => 'Invalid input data']);
    exit();
}

// Додаткова перевірка для платіжних карт
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
    // Якщо оплата не карткою, встановлюємо ці значення в null
    $data['cardNumber'] = null;
    $data['expiryDate'] = null;
    $data['cvv'] = null;
}

// Збереження даних про платіж у базі даних
try {
    $stmt = $pdo->prepare("INSERT INTO payments (payment_method, card_number, expiry_date, cvv, amount) VALUES (:payment_method, :card_number, :expiry_date, :cvv, :amount)");
    $stmt->execute([
        ':payment_method' => $data['selectedPaymentMethod'],
        ':card_number' => $data['cardNumber'],
        ':expiry_date' => $data['expiryDate'],
        ':cvv' => $data['cvv'],
        ':amount' => $data['totalCost']
    ]);
    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    // Логування помилки PDO
    file_put_contents('payment_error_log.txt', date('Y-m-d H:i:s') . " - PDO error: " . $e->getMessage() . PHP_EOL, FILE_APPEND);
    echo json_encode(['success' => false, 'error' => 'Database query failed', 'details' => $e->getMessage()]);
    exit();
}
?>
