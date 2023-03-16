<?php

require_once('authconnect.php');

// 获取 number
$number = $_GET['number'];
if (!isset($number) || empty($number)) {
    http_response_code(400);
    die("Invalid number.");
}

// 根据 number 删除 Todo
$query = $conn_todo->prepare("DELETE FROM todos WHERE UserID = ? AND Number = ?");
$query->bind_param("ii", $userId, $number);
$query->execute();
$query->close();

$conn_todo->close();
echo("OK");
