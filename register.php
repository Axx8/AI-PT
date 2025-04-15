<?php
session_start();

if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require 'config.php';

    $conn = new mysqli($host, $user, $pass, $db);

    if ($conn->connect_error) {
        die("数据库连接失败: " . $conn->connect_error);
    }

    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // 哈希密码
    $email = $_POST['email'];

    // 检查用户名是否已存在
    $sql = "SELECT id FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo "<p style='color: red; text-align: center;'>用户名已存在！</p>";
    } else {
        // 插入新用户
        $sql = "INSERT INTO users (username, password, email) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $username, $password, $email);

        if ($stmt->execute()) {
            echo "<p style='color: green; text-align: center;'>注册成功！<a href='login.php'>立即登录</a></p>";
        } else {
            echo "<p style='color: red; text-align: center;'>注册失败: " . $stmt->error . "</p>";
        }
    }

    $stmt->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>在线注册</title>
    <style>
        body, html {
            margin: 0;
            padding: 0;
            height: 100%;
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #6a11cb, #2575fc);
            color: #fff;
        }
        .register-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100%;
        }
        .register-form {
            background: rgba(255, 255, 255, 0.9);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            width: 300px;
            text-align: center;
        }
        .register-form h2 {
            margin-bottom: 20px;
            color: #333;
        }
        .register-form label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #555;
        }
        .register-form input[type="text"],
        .register-form input[type="password"],
        .register-form input[type="email"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }
        .register-form input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #28a745;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .register-form input[type="submit"]:hover {
            background-color: #218838;
        }
        .login-link {
            margin-top: 15px;
            text-align: center;
        }
        .login-link a {
            color: #007bff;
            text-decoration: none;
            font-weight: bold;
        }
        .login-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="register-form">
            <h2 style="color: #333;">在线注册</h2>
            <form action="register.php" method="post">
                <label for="username">用户名:</label>
                <input type="text" id="username" name="username" required>

                <label for="password">密码:</label>
                <input type="password" id="password" name="password" required>

                <label for="email">邮箱:</label>
                <input type="email" id="email" name="email" required>

                <input type="submit" value="注册">
            </form>

            <!-- 登录链接 -->
            <div class="login-link">
                <p>已有账号？<a href="login.php">立即登录</a></p>
            </div>
        </div>
    </div>
</body>
</html>