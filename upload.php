<?php
session_start();

// 检查用户是否登录
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // 重定向到登录页面
    exit();
}

// 文件上传目录
$uploadDir = 'uploads/';

// 处理文件上传
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    $file = $_FILES['file'];

    // 检查文件是否上传成功
    if ($file['error'] === UPLOAD_ERR_OK) {
        // 获取文件扩展名
        $fileExt = pathinfo($file['name'], PATHINFO_EXTENSION);

        // 使用时间戳重命名文件
        $fileName = time() . '.' . $fileExt; // 例如：1697049600.jpg
        $filePath = $uploadDir . $fileName;

        // 将文件移动到上传目录
        if (move_uploaded_file($file['tmp_name'], $filePath)) {
            echo "<p style='color: green;'>文件上传成功！</p>";
        } else {
            echo "<p style='color: red;'>文件上传失败。</p>";
        }
    } else {
        echo "<p style='color: red;'>文件上传出错。</p>";
    }
}
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>文件上传</title>
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
        input[type="file"] {
            margin-bottom: 15px;
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
        .uploaded-images {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }
        .uploaded-images img {
            max-width: 100%;
            height: auto;
            border-radius: 5px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
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
    </style>
    <script>
        // 前端 JavaScript 限制文件类型
        function validateFile() {
            const fileInput = document.getElementById('file');
            const filePath = fileInput.value;
            const allowedExtensions = /(\.jpg|\.jpeg|\.png|\.gif)$/i;

            if (!allowedExtensions.exec(filePath)) {
                alert('只允许上传 JPG, JPEG, PNG 或 GIF 文件。');
                fileInput.value = '';
                return false;
            }
            return true;
        }
    </script>
</head>
<body>
    <div class="container">
        <div class="logout">
            <a href="logout.php">注销</a>
        </div>
        <h1>文件上传</h1>

        <!-- 文件上传表单 -->
        <form action="upload.php" method="post" enctype="multipart/form-data" onsubmit="return validateFile()">
            <label for="file">选择图片文件:</label>
            <input type="file" id="file" name="file" required>
            <input type="submit" value="上传">
        </form>

        <!-- 显示上传的图片 -->
        <h2>已上传的图片</h2>
        <div class="uploaded-images">
            <?php
            if (is_dir($uploadDir)) {
                $files = scandir($uploadDir);
                foreach ($files as $file) {
                    if ($file !== '.' && $file !== '..') {
                        $fileUrl = $uploadDir . urlencode($file); // 解决中文乱码问题
                        echo "<img src='$fileUrl' alt='$file'>";
                    }
                }
            }
            ?>
        </div>
    </div>
</body>
</html>