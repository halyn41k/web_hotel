<?php
header("Access-Control-Allow-Origin: http://localhost:8080");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

$host = 'localhost';
$dbname = 'hotel_w';
$username = 'root';
$password = '';

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    echo json_encode(['success' => false, 'error' => 'Помилка з\'єднання з базою даних.']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

$name = $data['name'] ?? '';
$surname = $data['surname'] ?? '';
$phone = $data['phone'] ?? '';
$email = $data['email'] ?? '';
$password = $data['password'] ?? '';

if (empty($name) || empty($surname) || empty($phone) || empty($email) || empty($password)) {
    echo json_encode(['success' => false, 'error' => 'Всі поля є обов\'язковими.']);
    exit;
}

$name = $conn->real_escape_string($name);
$surname = $conn->real_escape_string($surname);
$phone = $conn->real_escape_string($phone);
$email = $conn->real_escape_string($email);
$password = password_hash($conn->real_escape_string($password), PASSWORD_BCRYPT);

$query = "INSERT INTO users (name, surname, phone, email, password, role) VALUES ('$name', '$surname', '$phone', '$email', '$password', 'user')";

if ($conn->query($query) === TRUE) {
    echo json_encode(['success' => true, 'message' => 'Реєстрація успішна.']);
} else {
    echo json_encode(['success' => false, 'error' => 'Помилка реєстрації: ' . $conn->error]);
}

$conn->close();
?>

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
