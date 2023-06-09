<?php

$sessionKey = $_GET['sessionKey'];
$userId = $_GET['userId'];
// 验证 sessionKey 和 userId 的合法性
if (!isset($sessionKey) || empty($sessionKey) || !is_string($sessionKey) || !isset($userId) || empty($userId) || !is_numeric($userId)) {
    http_response_code(400);
    die("Invalid request.");
}

// 连接数据库
require_once('db_credentials.php');
$conn_todo = new mysqli("localhost", DB_USER, DB_PASS, DB_NAME);

// 检测连接
if ($conn_todo->connect_error) {
    die("连接失败: " . $conn_todo->connect_error);
}

// 设置编码，防止中文乱码
$conn_todo->set_charset("utf8mb4");

// 获取最后一次 Session 的 sessionKey 和 open
$query = $conn_todo->prepare("SELECT SessionKey, Open FROM sessions WHERE ID = ? ORDER BY Number DESC LIMIT 1");
$query->bind_param("s", $userId);
$query->execute();
$query->bind_result($lastSessionKey, $lastSessionOpen);
$query->fetch();
$query->close();

// 验证 sessionKey
if (!($lastSessionKey && $sessionKey === $lastSessionKey)) {
    http_response_code(401);
    die("No valid session.");
}

// 验证 sessionOpen
if (!($lastSessionOpen === 1)) {
    http_response_code(403);
    die("Session closed.");
}
