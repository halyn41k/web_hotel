<?php
header("Access-Control-Allow-Origin: http://localhost:8080");
header("Access-Control-Allow-Methods: POST");
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
    echo json_encode(['error' => 'Помилка з\'єднання з базою даних.']);
    exit;
}

$name = $_POST['name'];
$description = $_POST['description'];
$price = $_POST['price'];
$bedType = $_POST['bedType'];
$availability = $_POST['availability'];
$maxGuests = $_POST['maxGuests'];
$additionalServices = $_POST['additionalServices'];

$image = $_FILES['image'];

// Check if image file is a valid image type
$allowedFileTypes = ['image/jpeg', 'image/png', 'image/gif'];
if ($image && !in_array($image['type'], $allowedFileTypes)) {
    echo json_encode(['error' => 'Невірний формат зображення.']);
    exit;
}

// Insert room into the database
$roomQuery = "INSERT INTO rooms (name, description, price, bedType, availability, maxGuests, additionalServices) VALUES (?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($roomQuery);
$stmt->bind_param('ssdssds', $name, $description, $price, $bedType, $availability, $maxGuests, $additionalServices);

if ($stmt->execute()) {
    $roomId = $stmt->insert_id;

    // Handle image upload
    if ($image && $image['error'] === UPLOAD_ERR_OK) {
        $targetDir = 'C:/xampp/htdocs/new-hotel-website/src/assets/';
        $targetFile = $targetDir . basename($image['name']);
        if (move_uploaded_file($image['tmp_name'], $targetFile)) {
            $imageQuery = "INSERT INTO images (category, image_name, room_id) VALUES ('rooms', ?, ?)";
            $imageStmt = $conn->prepare($imageQuery);
            $imageStmt->bind_param('si', $image['name'], $roomId);
            $imageStmt->execute();
        } else {
            echo json_encode(['error' => 'Помилка завантаження зображення.']);
            exit;
        }
    }

    echo json_encode(['success' => true]);
} else {
    echo json_encode(['error' => 'Помилка додавання кімнати.']);
}

$stmt->close();
$conn->close();
?>
