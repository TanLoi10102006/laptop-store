<?php
include("../../config/ket-noi.php");

if (!isset($_SESSION['MaTaiKhoan']) || $_SESSION['VaiTro'] !== 'admin') {
    header("Location: ../../nguoi-dung/dang-nhap.php");
    exit();
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    header("Location: ../quan-ly-tai-khoan-admin/danh-sach.php");
    exit();
}

// 1. Không cho phép tự xóa tài khoản đang đăng nhập
if ($id == $_SESSION['MaTaiKhoan']) {
    echo "<script>alert('Bạn không thể tự xóa tài khoản của chính mình!'); window.location='../quan-ly-tai-khoan-admin/danh-sach.php';</script>";
    exit();
}

// 2. Kiểm tra nếu chỉ còn 1 admin duy nhất trong hệ thống
$countAdmin = mysqli_query($conn, "SELECT COUNT(*) AS Tong FROM TaiKhoan WHERE VaiTro = 'admin'");
$rowAdmin = mysqli_fetch_assoc($countAdmin);
if ($rowAdmin && $rowAdmin['Tong'] <= 1) {
    echo "<script>alert('Hệ thống phải có ít nhất 1 tài khoản quản trị! Không thể xóa.'); window.location='../quan-ly-tai-khoan-admin/danh-sach.php';</script>";
    exit();
}

$sql = "DELETE FROM TaiKhoan WHERE MaTaiKhoan = $id AND VaiTro = 'admin'";

if (mysqli_query($conn, $sql)) {
    echo "<script>alert('Đã xóa tài khoản Admin thành công!'); window.location='../quan-ly-tai-khoan-admin/danh-sach.php';</script>";
    exit();
} else {
    echo "<script>alert('Lỗi khi xóa: " . addslashes(mysqli_error($conn)) . "'); window.location='../quan-ly-tai-khoan-admin/danh-sach.php';</script>";
    exit();
}
