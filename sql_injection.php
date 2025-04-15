<?php
// 连接数据库
require 'config.php';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("数据库连接失败: " . $conn->connect_error);
}

// 检查表是否存在
$tableCheck = $conn->query("SHOW TABLES LIKE 'news'");
if ($tableCheck->num_rows === 0) {
    die("表 'news' 不存在！");
}

// 处理查询
$results = [];
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['search'])) {
    $search = $_GET['search'];

    // 打印生成的 SQL 查询（用于调试）
    $sql = "SELECT * FROM news WHERE title LIKE '%$search%' OR content LIKE '%$search%'";
    echo "<p>生成的 SQL 查询: $sql</p>"; // 调试信息

    $result = $conn->query($sql);

    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $results[] = $row;
        }
    } else {
        echo "<p style='color: red;'>查询失败: " . $conn->error . "</p>";
    }
}
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SQL 注入漏洞</title>
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
        <h1>SQL 注入漏洞</h1>

        <!-- 搜索表单 -->
        <form action="sql_injection.php" method="get">
            <label for="search">搜索新闻:</label>
            <input type="text" id="search" name="search" placeholder="输入关键词" required>
            <input type="submit" value="搜索">
        </form>

        <!-- 显示查询结果 -->
        <h2>查询结果</h2>
        <?php
        if (!empty($results)) {
            foreach ($results as $row) {
                echo '<div class="news-item">';
                echo '<h2>' . htmlspecialchars($row['title']) . '</h2>';
                echo '<p>' . htmlspecialchars($row['content']) . '</p>';
                echo '</div>';
            }
        } else {
            echo '<p>未找到相关新闻。</p>';
        }
        ?>
    </div>
</body>
</html>
<?php
// 关闭数据库连接
$conn->close();
?>