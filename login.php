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
        echo "<p style='color: red; text-align: center;'>用户名或密码错误</p>";
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
    <title>爱派(AiPy)实现自动化渗透测试攻击-登录页面</title>
    <style>
        /* 全局样式 */
        body, html {
            margin: 0;
            padding: 0;
            height: 100%;
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #6a11cb, #2575fc); /* 渐变背景 */
            color: #fff;
        }

        /* 居中容器 */
        .login-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100%;
        }

        /* 登录表单样式 */
        .login-form {
            background: rgba(255, 255, 255, 0.9); /* 半透明白色背景 */
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            width: 300px;
            text-align: center;
        }

        .login-form h2 {
            margin-bottom: 20px;
            color: #333;
        }

        .login-form label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #555;
        }

        .login-form input[type="text"],
        .login-form input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        .login-form input[type="submit"] {
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

        .login-form input[type="submit"]:hover {
            background-color: #218838;
        }

        .error-message {
            color: red;
            margin-bottom: 15px;
        }

        .register-link {
            margin-top: 15px;
            text-align: center;
        }

        .register-link a {
            color: #007bff;
            text-decoration: none;
            font-weight: bold;
        }

        .register-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-form">
            <h2 style="color: #333;">爱派(AiPy)实现自动化渗透测试攻击-登录页面</h2>
            <?php
            if (isset($error)) {
                echo "<p class='error-message'>$error</p>";
            }
            ?>
            <form action="login.php" method="post">
                <label for="username">用户名:</label>
                <input type="text" id="username" name="username" required>

                <label for="password">密码:</label>
                <input type="password" id="password" name="password" required>

                <input type="submit" value="登录">
            </form>

            <!-- 注册链接 -->
            <div class="register-link">
                <p>没有账号？<a href="register.php">立即注册</a></p>
            </div>
        </div>
    </div>
</body>
</html>