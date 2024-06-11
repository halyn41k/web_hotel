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

    // Отримання даних з POST-запиту
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $payment = $_POST['payment'];

    // Перевірка імені та прізвища
    if (!preg_match("/^[A-Z][a-z]*$/", $name)) {
        throw new Exception('Ім\'я повинно починатися з великої літери та містити тільки букви.');
    }
    if (!preg_match("/^[A-Z][a-z]*$/", $surname)) {
        throw new Exception('Прізвище повинно починатися з великої літери та містити тільки букви.');
    }

    // Перевірка телефону
    if (!preg_match("/^\d{10,}$/", $phone)) {
        throw new Exception('Невірний формат номеру телефону.');
    }

    // Підготовка та виконання запиту для вставки даних
    $statement = $pdo->prepare("INSERT INTO users (name, surname, phone, email, payment) VALUES (?, ?, ?, ?, ?)");
    $statement->execute([$name, $surname, $phone, $email, $payment]);

    // Встановлюємо заголовок Content-Type
    header("Content-Type: application/json");

    // Відправка відповіді з успіхом
    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    // Повертаємо помилку у форматі JSON
    echo json_encode(['error' => 'Помилка виконання запиту: ' . $e->getMessage()]);
} catch (Exception $e) {
    // Повертаємо помилку у форматі JSON
    echo json_encode(['error' => $e->getMessage()]);
}
?>
