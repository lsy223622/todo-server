<?php
require_once('authconnect.php');

// 获取 todoInfo
$todoInfo = $_GET['todoInfo'];
if (!isset($todoInfo) || empty($todoInfo)) {
    http_response_code(400);
    die("Invalid todoInfo.");
}

$todoInfo = json_decode($todoInfo, true);

// 根据 number 修改 Todo
$query = $conn_todo->prepare("UPDATE todos SET Title = ?, Content = ?, Deadline = ?, Priority = ?, Finished = ? WHERE UserID = ? AND Number = ?");
$title = mysqli_real_escape_string($conn_todo, $todoInfo['title']);
$content = mysqli_real_escape_string($conn_todo, $todoInfo['content']);
$deadline = mysqli_real_escape_string($conn_todo, $todoInfo['deadline']);
$priority = mysqli_real_escape_string($conn_todo, $todoInfo['priority']);
$finished = mysqli_real_escape_string($conn_todo, $todoInfo['finished']);
$number = mysqli_real_escape_string($conn_todo, $todoInfo['number']);
$query->bind_param("sssiiii", $title, $content, $deadline, $priority, $finished, $userId, $number);
$query->execute();
$query->close();

$conn_todo->close();
echo ("OK");