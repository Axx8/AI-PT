<?php
session_start(); // 启动会话

// 销毁所有会话数据
session_unset(); // 清除所有会话变量
session_destroy(); // 销毁会话

// 重定向到登录页面
header("Location: login.php");
exit(); // 确保脚本终止
?>