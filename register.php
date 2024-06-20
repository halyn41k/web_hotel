<?php
header("Access-Control-Allow-Origin: http://localhost:8080");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

$host = 'localhost';
$dbname = 'web_hotel';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $data = json_decode(file_get_contents("php://input"));

    if (isset($data->name, $data->surname, $data->phone, $data->email, $data->password, $data->payment)) {
        $name = $data->name;
        $surname = $data->surname;
        $phone = $data->phone;
        $email = $data->email;
        $password = password_hash($data->password, PASSWORD_BCRYPT); // Hash the password
        $payment = $data->payment;

        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->rowCount() > 0) {
            echo json_encode(['error' => 'Користувач з таким email вже існує.']);
            exit;
        }

        $stmt = $pdo->prepare("INSERT INTO users (name, surname, phone, email, password, payment) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$name, $surname, $phone, $email, $password, $payment]);

        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['error' => 'Невірні дані.']);
    }
} catch (PDOException $e) {
    echo json_encode(['error' => 'Помилка: ' . $e->getMessage()]);
}
?>
