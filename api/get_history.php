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
$token = $data['token'] ?? '';

// ✅ ОБЯЗАТЕЛЬНАЯ ПРОВЕРКА: токен должен быть
if (empty($token)) {
    echo json_encode(["success" => false, "message" => "Token required"]);
    $conn->close();
    exit;
}

// ✅ ОБЯЗАТЕЛЬНАЯ ПРОВЕРКА: токен должен соответствовать user_id
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

// ✅ ТОЛЬКО ПОСЛЕ УСПЕШНОЙ ПРОВЕРКИ загружаем историю
$stmt = $conn->prepare("SELECT message, role, created_at FROM chat_history WHERE user_id = ? ORDER BY created_at ASC");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

$messages = [];
while ($row = $result->fetch_assoc()) {
    $messages[] = $row;
}

echo json_encode(["success" => true, "messages" => $messages]);

$stmt->close();
$conn->close();
?>