<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Xóa và hủy session
$_SESSION = array();
session_destroy();

// Chuyển hướng thẳng về trang chủ bằng đường dẫn tuyệt đối
header("Location: /web-Ban-LapTop/index.php");
exit();