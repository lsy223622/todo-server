<?php
require_once('db_credentials.php');

$conn_todo = new mysqli("localhost", DB_USER, DB_PASS, DB_NAME);

// 检测连接
if ($conn_todo->connect_error) {
    die("连接失败: " . $conn_todo->connect_error);
}

// 把所有过期的 session 的 open 置为 0
$query = $conn_todo->prepare("UPDATE sessions SET Open = 0 WHERE Open = 1 AND ExpiryDate < NOW()");
$query->execute();
$query->close();

$conn_todo->close();
echo("OK");
?>