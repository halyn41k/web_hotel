<?php
header("Access-Control-Allow-Origin: http://localhost:8080");
header("Access-Control-Allow-Methods: POST, GET, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

$host = 'localhost';
$dbname = 'web_hotel';
$username = 'root';
$password = '';

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    echo json_encode(['success' => false, 'error' => 'Помилка з\'єднання з базою даних.']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

$identifier = $data['identifier'] ?? '';
$password = $data['password'] ?? '';

if (empty($identifier) || empty($password)) {
    echo json_encode(['success' => false, 'error' => 'Всі поля є обов\'язковими.']);
    exit;
}

$identifier = $conn->real_escape_string($identifier);

// Спочатку перевіряємо, чи це адміністратор
$query = "SELECT * FROM admins WHERE username = '$identifier'";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    $admin = $result->fetch_assoc();
    if (password_verify($password, $admin['password'])) {
        echo json_encode(['success' => true, 'user' => $admin, 'role' => 'admin']);
    } else {
        echo json_encode(['success' => false, 'error' => 'Невірний пароль.']);
    }
} else {
    // Якщо не адміністратор, перевіряємо користувача за електронною поштою
    $query = "SELECT * FROM users WHERE email = '$identifier'";
    $result = $conn->query($query);
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            echo json_encode(['success' => true, 'user' => $user, 'role' => 'user']);
        } else {
            echo json_encode(['success' => false, 'error' => 'Невірний пароль.']);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Користувач не знайдений.']);
    }
}

$conn->close();
?>
