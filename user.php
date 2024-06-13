<?php
header("Access-Control-Allow-Origin: http://localhost:8080");
header("Access-Control-Allow-Methods: POST, OPTIONS");
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
        if (isset($_FILES['profile_photo'])) {
            // Завантаження профільної фотографії користувача
            $profile_photo = $_FILES['profile_photo'];

            if ($profile_photo['error'] === UPLOAD_ERR_OK) {
                $uploadDir = 'profile_photos';
                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                $uploadPath = $uploadDir . '/' . uniqid() . '_' . basename($profile_photo['name']);
                if (move_uploaded_file($profile_photo['tmp_name'], $uploadPath)) {
                    // Оновлення шляху до фотографії користувача в базі даних
                    $stmt = $pdo->prepare("UPDATE users SET profile_photo = ? WHERE email = ?");
                    $stmt->execute([$uploadPath, $_POST['email']]);
                    echo json_encode(['success' => true, 'profile_photo' => $uploadPath]);
                } else {
                    echo json_encode(['success' => false, 'error' => 'Failed to move uploaded file.']);
                }
            } else {
                echo json_encode(['success' => false, 'error' => 'Error uploading file.']);
            }
        } elseif (isset($_POST['email'], $_POST['paymentMethod'])) {
            // Оновлення способу оплати користувача
            $paymentMethod = $_POST['paymentMethod'];
            $stmt = $pdo->prepare("UPDATE users SET payment_method = ? WHERE email = ?");
            $stmt->execute([$paymentMethod, $_POST['email']]);
            echo json_encode(['success' => true]);
        } elseif (isset($_POST['email'])) {
            // Отримання способу оплати для користувача
            $stmt = $pdo->prepare("SELECT payment_method FROM users WHERE email = ?");
            $stmt->execute([$_POST['email']]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $payment_method = $row['payment_method'];
            echo json_encode(['success' => true, 'payment_method' => $payment_method]);
        } elseif (isset($_POST['action']) && $_POST['action'] === 'getBookings') {
            // Отримання списку бронювань користувача
            $stmt = $pdo->prepare("SELECT * FROM bookings WHERE email = ?");
            $stmt->execute([$_POST['email']]);
            $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode(['success' => true, 'bookings' => $bookings]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Invalid request.']);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Invalid request method.']);
    }
} catch (PDOException $e) {
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>
