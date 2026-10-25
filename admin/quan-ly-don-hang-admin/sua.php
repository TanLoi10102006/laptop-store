<?php
include("../../config/ket-noi.php");

if (!isset($_SESSION['MaTaiKhoan']) || $_SESSION['VaiTro'] !== 'admin') {
    header("Location: ../../nguoi-dung/dang-nhap.php");
    exit();
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    header("Location: danh-sach.php");
    exit();
}

// Chuyển hướng sang chi-tiet.php nơi có đầy đủ giao diện sửa trạng thái và xem chi tiết
header("Location: chi-tiet.php?id=$id");
exit();
