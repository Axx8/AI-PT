<?php
session_start();
require 'config.php';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("连接失败: " . $conn->connect_error);
}

$username = $_POST['username'];
$password = $_POST['password'];

$sql = "SELECT id, username, password FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($id, $username, $hashed_password);

if ($stmt->fetch() && password_verify($password, $hashed_password)) {
    $_SESSION['user_id'] = $id;
    $_SESSION['username'] = $username;
    header("Location: dashboard.php");
} else {
    echo "用户名或密码错误";
}

$stmt->close();
$conn->close();
?>