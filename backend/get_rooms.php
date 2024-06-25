<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Content-Type: application/json");
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$host = 'localhost';
$dbname = 'hotel_w';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $statement = $pdo->query('SELECT * FROM rooms');
    $rooms = $statement->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(['rooms' => $rooms]);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Помилка виконання запиту: ' . $e->getMessage()]);
}
?>
