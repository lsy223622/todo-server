<?php
$sessionKey = $_GET['sessionKey'];
$userId = $_GET['userId'];
$todoInfo = $_GET['todoInfo'];
$todoInfo = json_decode($todoInfo, true);

if (!isset($sessionKey, $userId)) {
    http_response_code(400);
    die("Invalid request.");
}

require_once('conn_todo.php');

// 添加 todo
$query = $conn_todo->prepare("INSERT INTO todos (UserID, Title, Content, AddDate, Deadline, Priority, Finished) VALUES (?, ?, ?, ?, ?, ?, ?)");
$query->bind_param("issssii", $userId, $todoInfo['title'], $todoInfo['content'], $todoInfo['addDate'], $todoInfo['deadline'], $todoInfo['priority'], $todoInfo['finished']);
$query->execute();
$query->close();

echo($userId.$todoInfo['title'].$todoInfo['content'].$todoInfo['addDate'].$todoInfo['deadline'].$todoInfo['priority'].$todoInfo['finished']);

$conn_todo->close();
echo("OK")
?>