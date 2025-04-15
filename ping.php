<?php
header('Content-Type: text/html; charset=UTF-8');
session_start();

// 检查用户是否登录
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// 处理用户输入
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['host'])) {
    $host = $_POST['host'];

    // 直接拼接用户输入到系统命令中（存在远程命令执行漏洞）
    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
        // Windows 系统
        $command = "ping -n 4 " . $host;
    } else {
        // Linux 或其他系统
        $command = "ping -c 4 " . $host;
    }

    // 执行命令
    $output = shell_exec($command);

    // 显示命令执行结果
    if ($output) {
        $output = iconv('GBK', 'UTF-8', $output); // 将 GBK 编码转换为 UTF-8
        echo "<pre>$output</pre>";
    } else {
        echo "<p style='color: red;'>Ping 失败！</p>";
    }
}
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ping 测试</title>
    <style>
        body, html {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
        }
        .container {
            width: 80%;
            margin: 50px auto;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #333;
        }
        form {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #555;
        }
        input[type="text"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }
        input[type="submit"] {
            padding: 10px 20px;
            background-color: #28a745;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #218838;
        }
        pre {
            background-color: #f8f9fa;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ddd;
            overflow-x: auto;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Ping 测试</h1>

        <!-- Ping 测试表单 -->
        <form action="ping.php" method="post">
            <label for="host">输入主机地址:</label>
            <input type="text" id="host" name="host" placeholder="例如：google.com" required>
            <input type="submit" value="Ping">
        </form>

        <!-- 显示 Ping 结果 -->
        <?php
        if (isset($output)) {
            echo "<h2>Ping 结果</h2>";
            echo "<pre>$output</pre>";
        }
        ?>
    </div>
</body>
</html>