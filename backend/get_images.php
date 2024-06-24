<?php
header("Access-Control-Allow-Origin: http://localhost:8080");
header("Access-Control-Allow-Methods: POST, GET, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/error_log.txt');
ini_set('display_errors', 1);
error_reporting(E_ALL);

$host = 'localhost';
$dbname = 'hotel_w';
$username = 'root';
$password = '';

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die(json_encode(['error' => 'Connection failed: ' . $conn->connect_error]));
}

// SQL query to select images with specific categories
$categories = ['aboutus', 'rooms', 'booking', 'footer', 'location', 'header', 'caurosel', 'menu', 'arrow', 'leef', 'login', 'admin', 'payment'];
$placeholders = implode(',', array_fill(0, count($categories), '?'));
$sql = "SELECT category, image_name, room_id FROM images WHERE category IN ($placeholders)";
$stmt = $conn->prepare($sql);

// Bind parameters for the categories
$stmt->bind_param(str_repeat('s', count($categories)), ...$categories);

// Execute query
$stmt->execute();

// Get result
$result = $stmt->get_result();

$images = array();

// Fetch images and add to the array
while ($row = $result->fetch_assoc()) {
    $images[] = $row;
}

// Close statement and connection
$stmt->close();
$conn->close();

echo json_encode($images);
?>
