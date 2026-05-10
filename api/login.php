<?php
header("Content-Type: application/json");

$host = "localhost";
$db   = "yamagistr"; 
$user = "root";
$pass = "root";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    echo json_encode(["success" => false, "message" => "Ошибка БД"]);
    exit;
}

$conn->set_charset("utf8mb4");

$data = json_decode(file_get_contents("php://input"), true);

$email = trim($data['email'] ?? '');
$password = $data['password'] ?? '';
$remember = $data['remember'] ?? false;

// Ищем пользователя
$stmt = $conn->prepare("SELECT id, username, email, password FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(["success" => false, "message" => "Пользователь не найден"]);
    $conn->close();
    exit;
}

$user = $result->fetch_assoc();

// Проверяем пароль
if (!password_verify($password, $user['password'])) {
    echo json_encode(["success" => false, "message" => "Неверный пароль"]);
    $conn->close();
    exit;
}

// Генерируем токен
$token = bin2hex(random_bytes(32));

// Сохраняем токен в БД
$updateStmt = $conn->prepare("UPDATE users SET token = ? WHERE id = ?");
$updateStmt->bind_param("si", $token, $user['id']);
$updateStmt->execute();
$updateStmt->close();

// Возвращаем успешный ответ
echo json_encode([
    "success" => true,
    "token" => $token,
    "user" => [
        "id" => $user['id'],
        "fullname" => $user['username'],
        "email" => $user['email']
    ],
    "message" => "Вход выполнен"
]);

$conn->close();
?>