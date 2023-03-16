<?php
require_once('authconnect.php');

// 把当前 Session 的 ExpiryTime 更新为 30 分钟后
$query = $conn_todo->prepare("UPDATE sessions SET ExpiryTime = DATE_ADD(NOW(), INTERVAL 30 MINUTE) WHERE UserID = ? AND SessionID = ?");
$query->bind_param("is", $userId, $sessionId);
$query->execute();
$query->close();

$conn_todo->close();
echo ("OK");