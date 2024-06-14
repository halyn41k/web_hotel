<?php
header("Access-Control-Allow-Origin: http://localhost:8080");
header("Access-Control-Allow-Methods: GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

session_start();

$host = 'localhost';
$dbname = 'web_hotel';
$username = 'root';
$password = '';

$response = [];

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
        $stmt = $pdo->query("SELECT * FROM bookings WHERE status = 'active'");
        $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $stmt = $pdo->query("SELECT * FROM wishes");
        $wishes = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $response = ['bookings' => $bookings, 'wishes' => $wishes];
    } else {
        $response = ['error' => 'Не авторизований.'];
    }
} catch (PDOException $e) {
    $response = ['error' => 'Помилка: ' . $e->getMessage()];
}

echo json_encode($response);
exit;
?>
