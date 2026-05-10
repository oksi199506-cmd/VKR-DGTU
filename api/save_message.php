<?php
header("Content-Type: application/json");

$host = "localhost";
$db   = "yamagistr"; 
$user = "root";
$pass = "root";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    echo json_encode(["success" => false, "message" => "DB error"]);
    exit;
}

$conn->set_charset("utf8mb4");

$data = json_decode(file_get_contents("php://input"), true);

$userId = intval($data['user_id'] ?? 0);
$message = $data['message'] ?? '';
$role = $data['role'] ?? 'user';
$token = $data['token'] ?? '';

// ✅ ОБЯЗАТЕЛЬНАЯ ПРОВЕРКА
if (empty($token)) {
    echo json_encode(["success" => false, "message" => "Token required"]);
    $conn->close();
    exit;
}

// ✅ ПРОВЕРКА ТОКЕНА
$checkStmt = $conn->prepare("SELECT id FROM users WHERE id = ? AND token = ?");
$checkStmt->bind_param("is", $userId, $token);
$checkStmt->execute();
$checkResult = $checkStmt->get_result();

if ($checkResult->num_rows === 0) {
    echo json_encode(["success" => false, "message" => "Invalid token"]);
    $checkStmt->close();
    $conn->close();
    exit;
}
$checkStmt->close();

// Сохраняем сообщение
$stmt = $conn->prepare("INSERT INTO chat_history (user_id, message, role) VALUES (?, ?, ?)");
$stmt->bind_param("iss", $userId, $message, $role);

if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "message" => $conn->error]);
}

$stmt->close();
$conn->close();
?>