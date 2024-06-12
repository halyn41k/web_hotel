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

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['profile_photo'])) {
        $profile_photo = $_FILES['profile_photo'];

        if ($profile_photo['error'] === UPLOAD_ERR_OK) {
            // Move the uploaded file to the desired directory
            $uploadDir = 'profile_photos/';
            $uploadPath = $uploadDir . basename($profile_photo['name']);
            move_uploaded_file($profile_photo['tmp_name'], $uploadPath);

            // Update the user's profile photo path in the database
            $stmt = $pdo->prepare("UPDATE users SET profile_photo = ? WHERE email = ?");
            $stmt->execute([$uploadPath, $_POST['email']]);

            echo json_encode(['success' => true, 'profile_photo' => $uploadPath]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Помилка завантаження файлу.']);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Некоректний запит.']);
    }
} catch (PDOException $e) {
    echo json_encode(['error' => 'Помилка: ' . $e->getMessage()]);
}
?>
