<?php
// 连接数据库
require 'config.php';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("数据库连接失败: " . $conn->connect_error);
}

// 处理搜索请求（存在 SQL 注入漏洞）
$searchResults = [];
if (isset($_GET['search'])) {
    $searchKeyword = $_GET['search']; // 直接使用用户输入，未做任何处理
    $sql = "SELECT title, content, published_at FROM news WHERE title LIKE '%$searchKeyword%' OR content LIKE '%$searchKeyword%' ORDER BY published_at DESC";
    $searchResult = $conn->query($sql);
    if ($searchResult->num_rows > 0) {
        while ($row = $searchResult->fetch_assoc()) {
            $searchResults[] = $row;
        }
    }
}

// 查询最新新闻数据
$sql = "SELECT title, content, published_at FROM news ORDER BY published_at DESC LIMIT 5";
$result = $conn->query($sql);

// 获取uploads目录下的文件列表
$uploadDir = 'uploads/';
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
    <title>爱派(AiPy)实现自动化渗透测试攻击-靶场</title>
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
        .columns {
            display: flex;
            justify-content: space-between;
        }
        .left-column {
            width: 65%;
        }
        .right-column {
            width: 30%;
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
        .search-form {
            margin-bottom: 20px;
        }
        .search-form input[type="text"] {
            width: 70%;
            padding: 10px;
            font-size: 16px;
        }
        .search-form input[type="submit"] {
            padding: 10px 20px;
            font-size: 16px;
            background-color: #333;
            color: #fff;
            border: none;
            cursor: pointer;
        }
        /* 轮播图样式 */
        .carousel {
            position: relative;
            width: 100%;
            max-width: 800px;
            margin: 20px auto;
            overflow: hidden;
            border: 2px solid #ddd;
            border-radius: 10px;
        }
        .carousel-inner {
            display: flex;
            transition: transform 0.5s ease;
        }
        .carousel-item {
            min-width: 100%;
            box-sizing: border-box;
        }
        .carousel-item img {
            width: 100%;
            display: block;
        }
        .carousel-control {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background-color: rgba(0, 0, 0, 0.5);
            color: #fff;
            border: none;
            padding: 10px;
            cursor: pointer;
            font-size: 18px;
        }
        .carousel-control.prev {
            left: 10px;
        }
        .carousel-control.next {
            right: 10px;
        }
        /* 资源下载中心样式 */
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
    </style>
</head>
<body>
    <header>
        <h1>爱派(AiPy)实现自动化渗透测试攻击-靶场</h1>
    </header>
    <div class="container">
        <!-- 轮播图 -->
        <div class="carousel">
            <div class="carousel-inner">
                <div class="carousel-item">
                    <img src="images/1.png" alt="轮播图1">
                </div>
                <div class="carousel-item">
                    <img src="images/2.png" alt="轮播图2">
                </div>
                <div class="carousel-item">
                    <img src="images/3.png" alt="轮播图3">
                </div>
                <div class="carousel-item">
                    <img src="images/4.png" alt="轮播图3">
                </div>
            </div>
            <button class="carousel-control prev" onclick="prevSlide()">&#10094;</button>
            <button class="carousel-control next" onclick="nextSlide()">&#10095;</button>
        </div>

        <!-- 搜索表单 -->
        <form class="search-form" action="" method="GET">
            <input type="text" name="search" placeholder="请输入关键词..." required>
            <input type="submit" value="搜索">
        </form>

        <!-- 两栏布局 -->
        <div class="columns">
            <!-- 左侧栏目：最新新闻 -->
            <div class="left-column">
                <!-- 展示搜索结果或最新新闻 -->
                <?php if (!empty($searchResults)): ?>
                    <h2>搜索结果</h2>
                    <?php foreach ($searchResults as $row): ?>
                        <div class="news-item">
                            <h2><?php echo htmlspecialchars($row['title']); ?></h2>
                            <p><?php echo htmlspecialchars($row['content']); ?></p>
                            <p class="date">发布时间: <?php echo $row['published_at']; ?></p>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <h2>最新新闻</h2>
                    <?php if ($result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <div class="news-item">
                                <h2><?php echo htmlspecialchars($row['title']); ?></h2>
                                <p><?php echo htmlspecialchars($row['content']); ?></p>
                                <p class="date">发布时间: <?php echo $row['published_at']; ?></p>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p>暂无新闻。</p>
                    <?php endif; ?>
                <?php endif; ?>
            </div>

            <!-- 右侧栏目：资源下载中心 -->
            <div class="right-column">
                <h2>资源下载中心</h2>
                <div class="file-list">
                    <?php if (!empty($files)): ?>
                        <?php foreach ($files as $file): ?>
                            <?php $filePath = $uploadDir . $file; ?>
                            <!-- 只展示文件名称，不展示图片 -->
                            <a href="<?php echo $filePath; ?>" download><?php echo $file; ?></a>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>暂无文件。</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <script>
        // 轮播图逻辑
        let currentIndex = 0;
        const slides = document.querySelectorAll('.carousel-item');
        const totalSlides = slides.length;

        function showSlide(index) {
            const carouselInner = document.querySelector('.carousel-inner');
            const offset = -index * 100;
            carouselInner.style.transform = `translateX(${offset}%)`;
        }

        function nextSlide() {
            currentIndex = (currentIndex + 1) % totalSlides;
            showSlide(currentIndex);
        }

        function prevSlide() {
            currentIndex = (currentIndex - 1 + totalSlides) % totalSlides;
            showSlide(currentIndex);
        }

        // 自动播放
        setInterval(nextSlide, 3000); // 每3秒切换一次
    </script>
</body>
</html>
<?php
// 关闭数据库连接
$conn->close();
?>