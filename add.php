<?php
require_once('authconnect.php');

// 获取 todoInfo
$todoInfo = $_GET['todoInfo'];
if (!isset($todoInfo) || empty($todoInfo)) {
    http_response_code(400);
    die("Invalid todoInfo.");
}

$todoInfo = json_decode($todoInfo, true);

// 添加 Todo
$query = $conn_todo->prepare("INSERT INTO todos (UserID, Title, Content, AddTime, Deadline, Priority, Finished) VALUES (?, ?, ?, ?, ?, ?, ?)");
$title = mysqli_real_escape_string($conn_todo, $todoInfo['title']);
$content = mysqli_real_escape_string($conn_todo, $todoInfo['content']);
$addTime = mysqli_real_escape_string($conn_todo, $todoInfo['addTime']);
$deadline = mysqli_real_escape_string($conn_todo, $todoInfo['deadline']);
$priority = mysqli_real_escape_string($conn_todo, $todoInfo['priority']);
$finished = mysqli_real_escape_string($conn_todo, $todoInfo['finished']);
$query->bind_param("issssii", $userId, $title, $content, $addTime, $deadline, $priority, $finished);
$query->execute();
$query->close();

$conn_todo->close();
echo ("OK")
?>