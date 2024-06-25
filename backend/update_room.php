<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$host = 'localhost';
$dbname = 'hotel_w';
$username = 'root';
$password = '';

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    echo json_encode(['success' => false, 'error' => 'Помилка з\'єднання з базою даних: ' . $conn->connect_error]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['id'], $data['field'], $data['value'])) {
    $id = $conn->real_escape_string($data['id']);
    $field = $conn->real_escape_string($data['field']);
    $value = $conn->real_escape_string($data['value']);

    $updateQuery = "UPDATE rooms SET $field = '$value' WHERE id = '$id'";
    if ($conn->query($updateQuery) === TRUE) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Помилка оновлення кімнати: ' . $conn->error]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Неправильні дані.']);
}

$conn->close();
?>
