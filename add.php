<?php
$sessionKey = $_GET['sessionKey'];
$userId = $_GET['userId'];
$todoInfo = $_GET['todoInfo'];

if (!isset($sessionKey) || empty($sessionKey) || !isset($userId) || empty($userId)) {
    http_response_code(400);
    die("Invalid request.");
}
if (!isset($todoInfo) || empty($todoInfo)) {
    http_response_code(400);
    die("Invalid todoInfo.");
}

$todoInfo = json_decode($todoInfo, true);

require_once('conn_todo.php');

// 添加 todo
$query = $conn_todo->prepare("INSERT INTO todos (UserID, Title, Content, AddDate, Deadline, Priority, Finished) VALUES (?, ?, ?, ?, ?, ?, ?)");
$title = mysqli_real_escape_string($conn_todo, $todoInfo['title']);
$content = mysqli_real_escape_string($conn_todo, $todoInfo['content']);
$addDate = mysqli_real_escape_string($conn_todo, $todoInfo['addDate']);
$deadline = mysqli_real_escape_string($conn_todo, $todoInfo['deadline']);
$priority = mysqli_real_escape_string($conn_todo, $todoInfo['priority']);
$finished = mysqli_real_escape_string($conn_todo, $todoInfo['finished']);
$query->bind_param("issssii", $userId, $title, $content, $addDate, $deadline, $priority, $finished);
$query->execute();
$query->close();

$conn_todo->close();
echo("OK")
?>