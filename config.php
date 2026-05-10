<?php
$host = "localhost";
$db   = "yamagistr"; 
$user = "root";
$pass = "root";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Ошибка подключения: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");
?>