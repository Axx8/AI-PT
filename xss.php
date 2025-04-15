<?php
// 连接数据库
require 'config.php';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("数据库连接失败: " . $conn->connect_error);
}

// 处理表单提交
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];

    // 将用户输入直接插入数据库（存在存储型 XSS 漏洞）
    $sql = "INSERT INTO news (title, content, author_id) VALUES (?, ?, 1)"; // 假设 author_id 为 1
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $title, $content);

    if ($stmt->execute()) {
        echo "<p style='color: green;'>新闻插入成功！</p>";
    } else {
        echo "<p style='color: red;'>新闻插入失败: " . $stmt->error . "</p>";
    }

    $stmt->close();
}

// 从数据库中读取新闻并显示
$sql = "SELECT title, content FROM news ORDER BY published_at DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>存储型 XSS 漏洞</title>
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
        input[type="text"], textarea {
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
        .news-item {
            margin-bottom: 20px;
            padding-bottom: 20px;
            border-bottom: 1px solid #ddd;
        }
        .news-item h2 {
            margin: 0 0 10px;
            color: #333;
        }
        .news-item p {
            margin: 0;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>存储型 XSS 漏洞</h1>

        <!-- 表单：插入新闻 -->
        <form action="xss.php" method="post">
            <label for="title">新闻标题:</label>
            <input type="text" id="title" name="title" required>

            <label for="content">新闻内容:</label>
            <textarea id="content" name="content" rows="5" required></textarea>

            <input type="submit" value="提交">
        </form>

        <!-- 显示新闻 -->
        <h2>新闻列表</h2>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo '<div class="news-item">';
                echo '<h2>' . $row['title'] . '</h2>';
                echo '<p>' . $row['content'] . '</p>';
                echo '</div>';
            }
        } else {
            echo '<p>暂无新闻。</p>';
        }
        ?>
    </div>
</body>
</html>
<?php
// 关闭数据库连接
$conn->close();
?>