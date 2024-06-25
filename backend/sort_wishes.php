<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

$host = 'localhost';
$dbname = 'hotel_w';
$username = 'root';
$password = '';

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    echo json_encode(['error' => 'Database connection error.']);
    exit;
}

// Validate sort field
$valid_fields = ['id', 'name', 'email', 'message'];
$field = isset($_GET['field']) && in_array($_GET['field'], $valid_fields) ? $_GET['field'] : 'id';

// Perform query with dynamic ORDER BY
$sql = "SELECT * FROM wishes ORDER BY $field";
$result = $conn->query($sql);

$wishes = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $wishes[] = $row;
    }
}

echo json_encode(['wishes' => $wishes]);

$conn->close();
?>
