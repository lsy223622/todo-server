<?php
$sessionKey = $_POST['sessionKey'];
$user = $_POST['user'];

if (!isset($sessionKey, $user)) {
    http_response_code(400);
    die("Invalid request.");
}

require_once('conn_todo.php');

// 按 Deadline 顺序列出所有未完成的 todo
$query = $conn_todo->prepare("SELECT ID, Title, Content, AddDate, Deadline, Priority, Finished FROM todos WHERE UserID = ? AND Finished = 0 ORDER BY Deadline");
$query->bind_param("s", $userId);
$query->execute();
$result = $query->get_result();
$todos = [];
while ($row = $result->fetch_assoc()) {
    $todos[] = $row;
}
$query->close();

$conn_todo->close();

echo json_encode($todos);
?>