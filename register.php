<?php
header("Access-Control-Allow-Origin: http://localhost:8080");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

$host = 'localhost';
$dbname = 'hotel_w';
$username = 'root';
$password = '';

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    echo json_encode(['success' => false, 'error' => 'Помилка з\'єднання з базою даних.']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

$name = $data['name'] ?? '';
$surname = $data['surname'] ?? '';
$phone = $data['phone'] ?? '';
$email = $data['email'] ?? '';
$password = $data['password'] ?? '';

if (empty($name) || empty($surname) || empty($phone) || empty($email) || empty($password)) {
    echo json_encode(['success' => false, 'error' => 'Всі поля є обов\'язковими.']);
    exit;
}

$name = $conn->real_escape_string($name);
$surname = $conn->real_escape_string($surname);
$phone = $conn->real_escape_string($phone);
$email = $conn->real_escape_string($email);
$password = password_hash($conn->real_escape_string($password), PASSWORD_BCRYPT);

$query = "INSERT INTO users (name, surname, phone, email, password, role) VALUES ('$name', '$surname', '$phone', '$email', '$password', 'user')";

if ($conn->query($query) === TRUE) {
    echo json_encode(['success' => true, 'message' => 'Реєстрація успішна.']);
} else {
    echo json_encode(['success' => false, 'error' => 'Помилка реєстрації: ' . $conn->error]);
}

$conn->close();
?>
