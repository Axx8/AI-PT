<?php
// 设置文件存储目录
$uploadDir = 'uploads/';

// 获取已上传的文件列表
$files = [];
if (is_dir($uploadDir)) {
    $files = scandir($uploadDir);
    $files = array_diff($files, ['.', '..']); // 去除 . 和 ..
}
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>文件展示</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        header {
            background-color: #333;
            color: #fff;
            padding: 10px 0;
            text-align: center;
        }
        .container {
            width: 80%;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .file-list {
            margin-top: 20px;
        }
        .file-list a {
            display: block;
            margin: 10px 0;
            color: #333;
            text-decoration: none;
        }
        .file-list a:hover {
            text-decoration: underline;
        }
        .file-list img {
            max-width: 100%;
            height: auto;
            display: block;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <header>
        <h1>文件展示</h1>
    </header>
    <div class="container">
        <!-- 返回首页链接 -->
        <a href="index.php">返回首页</a>

        <!-- 展示文件 -->
        <div class="file-list">
            <h2>已上传的文件</h2>
            <?php if (!empty($files)): ?>
                <?php foreach ($files as $file): ?>
                    <?php $filePath = $uploadDir . $file; ?>
                    <?php if (preg_match('/\.(jpg|jpeg|png|gif)$/i', $file)): ?>
                        <!-- 如果是图片，直接显示 -->
                        <img src="<?php echo $filePath; ?>" alt="<?php echo $file; ?>">
                    <?php else: ?>
                        <!-- 如果是其他文件，提供下载链接 -->
                        <a href="<?php echo $filePath; ?>" download><?php echo $file; ?></a>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php else: ?>
                <p>暂无文件。</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>