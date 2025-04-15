-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- 主机： localhost
-- 生成日期： 2025-04-15 09:40:51
-- 服务器版本： 5.7.44-log
-- PHP 版本： 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 数据库： `sql-sec`
--
CREATE DATABASE IF NOT EXISTS `sql-sec` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `sql-sec`;

-- --------------------------------------------------------

--
-- 表的结构 `news`
--

CREATE TABLE `news` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `published_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `author_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `news`
--

INSERT INTO `news` (`id`, `title`, `content`, `published_at`, `author_id`) VALUES
(6, '爱派，Python Use, AI Freedom Me!', '爱派(AiPy)，用Python Use，给AI装上双手，开放源码、本地部署，除了帮你思考，更能帮你干活，成为您的超级人工智能助手！从此，你只要说出你的想法，爱派帮你分析本地数据，操作本地应用，给你最终结果！', '2025-04-15 01:23:27', 1),
(9, '新活动通知-测试数据', '学校将于下周举办运动会，请同学们踊跃报名。\r\n\r\n', '2025-04-15 01:27:38', 1),
(11, '关注公众号', '公众号搜索：渗透测试', '2025-04-15 01:34:26', 1),
(12, 'AiPy是IDE吗？与Cursor、Windsurf等有什么区别？', 'AiPy不是IDE，与AI IDE最大的区别是AiPy不直接交付代码，而是交付任务结果。当然如果你的任务是交付代码，AiPy也是可以帮你完成这个任务的！\r\n\r\n', '2025-04-15 01:34:36', 1),
(13, 'AiPy 目前能干哪些事情？', '理论上讲能通过Python自动调度完整的任务工作，AiPy都可以完成。不过目前我们还处于开发的初级阶段，目前推荐去尝试一些轻量级任务。\r\n\r\n', '2025-04-15 01:34:47', 1),
(14, 'AiPy和现在的Ai有什么区别？', '当前大模型，只能问答，回答问题，不能实际动手操作计算机帮你完成具体的任务。AiPy是面向任务的Ai系统，你只用告诉他你想做什么，AiPy会帮你完成。现有大模型面向问答，AiPy面向任务。\r\n\r\n', '2025-04-15 01:35:03', 1);

-- --------------------------------------------------------

--
-- 表的结构 `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `created_at`) VALUES
(1, 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@iis.cm', '2025-03-22 10:48:09'),
(13, 'administrator', '$2y$10$3GmB7DASHM2q/236TUKSgOhEHzJAOkUJRgKCmb97zTYvXOOHvIk5G', 'administrator@iis.cm', '2025-03-22 12:16:28'),
(15, 'test', '$2y$10$QvcPL/5ruQ3/qOhT0fF1HuahM2bVrY/0yC4qs03zSxrAgA0W3WLh6', 'test@iis.cm', '2025-04-15 00:56:08'),
(16, 'root', '$2y$10$gjssXe7fLVKHYxQV.rn8g.ZQ1nxpHAg9JRtm79ZLc/30zaudUSuSq', 'root@iis.cm', '2025-04-15 01:32:09'),
(17, 'cs', '$2y$10$A1kTloUsHc3J/k9njPQsO.tOHsnkPpuc.0FlxvQLw1yWBZzum1mgW', 'cs@qq.com', '2025-04-15 01:32:31'),
(18, 'user', '$2y$10$aIiX5uVSbMwmn10ghA2IMO.HwzmHpDMZUof1YU2DprFZCqsF/u8iK', 'user@qq.com', '2025-04-15 01:33:23');

--
-- 转储表的索引
--

--
-- 表的索引 `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`id`),
  ADD KEY `author_id` (`author_id`);

--
-- 表的索引 `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `news`
--
ALTER TABLE `news`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- 使用表AUTO_INCREMENT `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- 限制导出的表
--

--
-- 限制表 `news`
--
ALTER TABLE `news`
  ADD CONSTRAINT `news_ibfk_1` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
