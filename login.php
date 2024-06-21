<?php
header("Access-Control-Allow-Origin: http://localhost:8080");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

session_start();

$host = 'localhost';
$dbname = 'web_hotel';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $data = json_decode(file_get_contents("php://input"));

    if (isset($data->email, $data->password)) {
        $email = $data->email;
        $password = $data->password;

        if ($email === 'admin@gmail.com' && $password === 'adminpassword') {
            $_SESSION['admin_logged_in'] = true;
            echo json_encode(['success' => true, 'role' => 'admin']);
        } else {
            $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password'])) {
                echo json_encode(['success' => true, 'user' => $user, 'role' => 'user']);
            } else {
                echo json_encode(['error' => 'Перевірте введений email та пароль.']);
            }
        }
    } else {
        echo json_encode(['error' => 'Невірні дані.']);
    }
} catch (PDOException $e) {
    echo json_encode(['error' => 'Помилка: ' . $e->getMessage()]);
}
?>
