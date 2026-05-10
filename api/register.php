<?php
header("Content-Type: application/json");

// ПРОВЕРКА: существует ли config.php?
$configPath = __DIR__ . "/../config.php";
if (!file_exists($configPath)) {
    echo json_encode([
        "success" => false,
        "message" => "Config file not found at: " . $configPath
    ]);
    exit;
}

// подключение к БД
require_once $configPath;

// ПРОВЕРКА: установлено ли соединение?
if (!isset($conn) || $conn->connect_error) {
    echo json_encode([
        "success" => false,
        "message" => "Database connection failed"
    ]);
    exit;
}

// получаем JSON
$data = json_decode(file_get_contents("php://input"), true);

$fullname = trim($data['fullname'] ?? '');
$email = trim($data['email'] ?? '');
$password = $data['password'] ?? '';

$errors = [];

// Валидация
if (!$fullname || strlen($fullname) < 3) {
    $errors[] = "ФИО должно быть не менее 3 символов";
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Некорректный email";
}

if (!$password || strlen($password) < 6) {
    $errors[] = "Пароль должен быть минимум 6 символов";
}

// если есть ошибки
if (!empty($errors)) {
    echo json_encode([
        "success" => false,
        "errors" => $errors
    ]);
    exit;
}

// Проверка на существующий email
$stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    echo json_encode([
        "success" => false,
        "errors" => ["Пользователь с таким email уже существует"]
    ]);
    $stmt->close();
    exit;
}
$stmt->close();

// Хешируем пароль
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Сохраняем
$stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $fullname, $email, $hashedPassword);

if ($stmt->execute()) {
    echo json_encode([
        "success" => true,
        "message" => "Регистрация успешна"
    ]);
} else {
    echo json_encode([
        "success" => false,
        "message" => "Ошибка сервера: " . $conn->error
    ]);
}

$stmt->close();
$conn->close();
?>