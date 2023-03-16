<?php
$queryUserId = $_GET['userId'] ?? null;
$userKey = $_GET['userKey'] ?? null;

// 检查参数是否存在
if (!isset($queryUserId, $userKey)) {
    http_response_code(400);
    die("Invalid request.");
}

// 检查参数是否合法
if (!ctype_digit($queryUserId)) {
    http_response_code(400);
    die("Invalid user ID");
}

// 连接数据库
require_once 'db_credentials.php';
$conn_todo = mysqli_connect("localhost", DB_USER, DB_PASS, DB_NAME);

// 检查连接是否成功
if ($conn_todo->connect_error) {
    http_response_code(500);
    die("Database connection failed: " . $conn_todo->connect_error);
}

// 查询 Key
$stmt = $conn_todo->prepare("SELECT UserKey FROM userid WHERE ID = ? LIMIT 1");
$stmt->bind_param("i", $queryUserId);
$stmt->execute();
$queryKey = $stmt->get_result()->fetch_assoc()['UserKey'];
$stmt->close();

// 对比 Key
if (!hash_equals($userKey, $queryKey)) {
    $conn_todo->close();
    die("Authentification failed.");
}

// 查询最后一条相同 ID 的记录
$stmt = $conn_todo->prepare("SELECT Number FROM sessions WHERE ID = ? ORDER BY Number DESC LIMIT 1");
$stmt->bind_param("i", $queryUserId);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();

// 将上一条记录的 Open 字段设置为 False
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $last_session_number = $row['Number'];
    $stmt = $conn_todo->prepare("UPDATE sessions SET Open = 0 WHERE Number = ? LIMIT 1");
    $stmt->bind_param("i", $last_session_number);
    if (!$stmt->execute()) {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}

// 生成 sessionKey 和 expiryTime
$sessionKey = base64_encode(random_int(100000000, 999999999));
$expiryTime = date("Y-m-d H:i:s", time() + 1800);

// 添加记录到 Sessions 表
$stmt = $conn_todo->prepare("INSERT INTO sessions (Time, ID, SessionKey, ExpiryTime, Open) VALUES (NOW(), ?, ?, ?, TRUE)");
$stmt->bind_param("iss", $queryUserId, $sessionKey, $expiryTime);
if ($stmt->execute()) {
    echo $sessionKey;
}
$stmt->close();

$conn_todo->close();