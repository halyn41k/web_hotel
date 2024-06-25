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
    echo json_encode(['success' => false, 'error' => 'Помилка з\'єднання з базою даних: ' . $conn->connect_error]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image'], $_POST['id'])) {
    $id = $conn->real_escape_string($_POST['id']);
    $image = $_FILES['image'];

    $targetDir = "C:/xampp/htdocs/new-hotel-website/src/assets/";
    $targetFile = $targetDir . basename($image["name"]);
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    // Check if image file is a valid image type
    $allowedFileTypes = ['image/jpeg', 'image/png', 'image/gif'];
    if (!in_array($image['type'], $allowedFileTypes)) {
        echo json_encode(['success' => false, 'error' => 'Невірний формат зображення.']);
        exit;
    }

    // Check if the file is an actual image
    $check = getimagesize($image["tmp_name"]);
    if ($check !== false) {
        // Upload file
        if (move_uploaded_file($image["tmp_name"], $targetFile)) {
            // Check if the room already has an image in the images table
            $checkImageQuery = "SELECT id FROM images WHERE room_id = ?";
            $stmt = $conn->prepare($checkImageQuery);
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                // Update existing image record
                $stmt->bind_result($imageId);
                $stmt->fetch();
                $updateImageQuery = "UPDATE images SET image_name = ? WHERE id = ?";
                $updateStmt = $conn->prepare($updateImageQuery);
                $updateStmt->bind_param('si', $image['name'], $imageId);
                $updateStmt->execute();
                echo json_encode(['success' => true, 'message' => 'Зображення оновлено успішно.']);
            } else {
                // Insert new image record
                $insertImageQuery = "INSERT INTO images (category, image_name, room_id) VALUES ('rooms', ?, ?)";
                $insertStmt = $conn->prepare($insertImageQuery);
                $insertStmt->bind_param('si', $image['name'], $id);
                $insertStmt->execute();
                echo json_encode(['success' => true, 'message' => 'Зображення додано успішно.']);
            }
            $stmt->close();
        } else {
            echo json_encode(['success' => false, 'error' => 'Помилка завантаження файлу.']);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Файл не є зображенням.']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Неправильний запит.']);
}

$conn->close();
?>
