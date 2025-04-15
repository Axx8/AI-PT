<?php
session_start();

// 检查用户是否登录
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// 连接数据库
require 'config.php';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("数据库连接失败: " . $conn->connect_error);
}

// 处理删除文章
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['delete_id'])) {
    $deleteId = $_GET['delete_id'];

    // 删除文章
    $sql = "DELETE FROM news WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $deleteId);

    if ($stmt->execute()) {
        echo "<p style='color: green;'>文章删除成功！</p>";
    } else {
        echo "<p style='color: red;'>文章删除失败: " . $stmt->error . "</p>";
    }

    $stmt->close();
}

// 处理插入新闻
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $author_id = $_SESSION['user_id'];

    $sql = "INSERT INTO news (title, content, author_id) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $title, $content, $author_id);

    if ($stmt->execute()) {
        echo "<p style='color: green;'>新闻插入成功！</p>";
    } else {
        echo "<p style='color: red;'>新闻插入失败: " . $stmt->error . "</p>";
    }

    $stmt->close();
}

// 查询所有新闻
$sql = "SELECT id, title, content, published_at FROM news ORDER BY published_at DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>后台管理</title>
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
        .links {
            margin-bottom: 20px;
            text-align: center;
        }
        .links a {
            margin: 0 10px;
            color: #007bff;
            text-decoration: none;
            font-weight: bold;
        }
        .links a:hover {
            text-decoration: underline;
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
        .logout {
            text-align: right;
            margin-bottom: 20px;
        }
        .logout a {
            color: #dc3545;
            text-decoration: none;
            font-weight: bold;
        }
        .logout a:hover {
            text-decoration: underline;
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
        .news-item .date {
            font-size: 0.9em;
            color: #999;
        }
        .news-item .actions {
            margin-top: 10px;
        }
        .news-item .actions a {
            color: #dc3545;
            text-decoration: none;
            font-weight: bold;
        }
        .news-item .actions a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logout">
            <a href="logout.php">注销</a>
        </div>
        <h1>后台管理</h1>

        <!-- 导航链接 -->
        <div class="links">
            <a href="xss.php" target="_blank">XSS 漏洞</a>
            <a href="sql_injection.php" target="_blank">SQL 注入漏洞</a>
            <a href="upload.php" target="_blank">文件上传漏洞</a>
            <a href="ping.php" target="_blank">命令执行漏洞</a>
            </div>

        <!-- 插入新闻表单 -->
        <h2>插入新闻</h2>
        <form action="dashboard.php" method="post">
            <label for="title">新闻标题:</label>
            <input type="text" id="title" name="title" required>

            <label for="content">新闻内容:</label>
            <textarea id="content" name="content" rows="5" required></textarea>

            <input type="submit" value="提交">
        </form>

        <!-- 显示新闻列表 -->
        <h2>新闻列表</h2>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo '<div class="news-item">';
                echo '<h2>' . htmlspecialchars($row['title']) . '</h2>';
                echo '<p>' . htmlspecialchars($row['content']) . '</p>';
                echo '<p class="date">发布时间: ' . $row['published_at'] . '</p>';
                echo '<div class="actions">';
                echo '<a href="dashboard.php?delete_id=' . $row['id'] . '" onclick="return confirm(\'确定删除此文章吗？\');">删除</a>';
                echo '</div>';
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