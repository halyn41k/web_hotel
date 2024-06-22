<?php
header("Access-Control-Allow-Origin: http://localhost:8080");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

// Enable error logging to a file
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/error_log.txt');
ini_set('display_errors', 0);
error_reporting(E_ALL);

// Database connection settings
$host = 'localhost';
$dbname = 'hotel_w';
$username = 'root';
$password = '';

// Create database connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    error_log('Database connection error: ' . $conn->connect_error);
    echo json_encode(['success' => false, 'error' => 'Помилка з\'єднання з базою даних.']);
    exit;
}

// Retrieve and decode JSON input
$data = json_decode(file_get_contents('php://input'), true);

$identifier = $data['identifier'] ?? '';
$password = $data['password'] ?? '';

// Validate input fields
if (empty($identifier) || empty($password)) {
    echo json_encode(['success' => false, 'error' => 'Всі поля є обов\'язковими.']);
    exit;
}

// Escape identifier to prevent SQL injection
$identifier = $conn->real_escape_string($identifier);

// Query to fetch user details
$query = "SELECT * FROM users WHERE email = '$identifier'";
$result = $conn->query($query);

if ($result) {
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            echo json_encode(['success' => true, 'user' => $user, 'role' => $user['role']]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Невірний пароль.']);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Користувач не знайдений.']);
    }
} else {
    error_log('Query error: ' . $conn->error);
    echo json_encode(['success' => false, 'error' => 'Помилка бази даних.']);
}

$conn->close();
?>
