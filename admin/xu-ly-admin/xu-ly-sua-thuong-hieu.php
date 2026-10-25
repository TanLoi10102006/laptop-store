<?php
include("../../config/ket-noi.php");

if (!isset($_SESSION['MaTaiKhoan']) || $_SESSION['VaiTro'] !== 'admin') {
    header("Location: ../../nguoi-dung/dang-nhap.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../quan-ly-thuong-hieu-admin/danh-sach.php");
    exit();
}

$maHang = (int)($_POST['MaHang'] ?? 0);
$tenHang = clean_input($conn, $_POST['TenHang'] ?? '');

if ($maHang <= 0 || empty($tenHang)) {
    echo "<script>alert('Vui lòng nhập đầy đủ thông tin!'); window.history.back();</script>";
    exit();
}

$sql = "UPDATE HangLaptop SET TenHang = '$tenHang' WHERE MaHang = $maHang";

if (mysqli_query($conn, $sql)) {
    echo "<script>alert('Cập nhật thương hiệu thành công!'); window.location='../quan-ly-thuong-hieu-admin/danh-sach.php';</script>";
    exit();
} else {
    echo "<script>alert('Lỗi cập nhật: " . addslashes(mysqli_error($conn)) . "'); window.history.back();</script>";
    exit();
}
