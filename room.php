<?php
// Відображення помилок (для розробки)
header("Access-Control-Allow-Origin: *"); // Дозволяємо доступ з будь-якого джерела
header("Access-Control-Allow-Methods: GET, POST, OPTIONS"); // Дозволяємо методи запитів GET, POST та OPTIONS
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Параметри підключення до бази даних
$host = 'localhost';
$dbname = 'web_hotel';
$username = 'root';
$password = '';

try {
    // Підключення до бази даних
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Виконання запиту до бази даних
    $statement = $pdo->query('SELECT * FROM get_rooms');
    $rooms = $statement->fetchAll(PDO::FETCH_ASSOC);

    // Встановлюємо заголовок Content-Type
    header("Content-Type: application/json");

    // Повертаємо результат у форматі JSON
    echo json_encode($rooms); 
} catch (PDOException $e) {
    // Повертаємо помилку у форматі JSON
    echo json_encode(['error' => 'Помилка виконання запиту: ' . $e->getMessage()]);
}
?>
