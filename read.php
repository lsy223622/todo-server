<?php

require_once('authconnect.php');

// 按 deadline 顺序列出所有未完成的 Todo
$query = $conn_todo->prepare("SELECT Number, UserID, Title, Content, AddTime, Deadline, Priority, Finished FROM todos WHERE UserID = ? AND Finished = 0 ORDER BY Deadline");
$query->bind_param("s", $userId);
$query->execute();
$result = $query->get_result();
$todos = [];
while ($row = $result->fetch_assoc()) {
    $todos[] = $row;
}
$query->close();

$conn_todo->close();

echo json_encode($todos, JSON_UNESCAPED_UNICODE);
