<?php
$sessionKey = $_POST['sessionKey'];
$user = $_POST['user'];
$todoInfo = $_POST['todoInfo'];
$todoInfo = json_decode($todoInfo, true);

require_once('db_credentials.php');

if (!isset($queryMode, $queryInfo, $userId, $sessionKey)) {
    http_response_code(400);
    die("Invalid request.");
}

$conn_todo = new mysqli("localhost", DB_USER, DB_PASS, DB_NAME);

// 检测连接
if ($conn_todo->connect_error) {
    die("连接失败: " . $conn_todo->connect_error);
}

// 设置编码，防止中文乱码
$conn_todo->set_charset("utf8");

$query = $conn_todo->prepare("SELECT SessionKey, Open FROM sessions WHERE ID = ? ORDER BY Number DESC LIMIT 1");
$query->bind_param("s", $userId);
$query->execute();
$result = $query->get_result();
$row = $result->fetch_assoc();
$lastSessionKey = $row['SessionKey'] ?? null;
$lastSessionOpen = $row['Open'] ?? null;
$query->close();
mysqli_close($conn_todo);

if (!($lastSessionKey && $sessionKey === $lastSessionKey)) {
    http_response_code(401);
    die("No valid session.");
}

if (!($lastSessionOpen === 1)) {
    http_response_code(403);
    die("Session closed.");
}