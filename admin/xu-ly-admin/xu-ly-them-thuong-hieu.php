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

$tenHang = clean_input($conn, $_POST['TenHang'] ?? '');

if (empty($tenHang)) {
    echo "<script>alert('Vui lòng nhập tên thương hiệu!'); window.history.back();</script>";
    exit();
}

$sql = "INSERT INTO HangLaptop (TenHang) VALUES ('$tenHang')";

if (mysqli_query($conn, $sql)) {
    echo "<script>alert('Thêm thương hiệu thành công!'); window.location='../quan-ly-thuong-hieu-admin/danh-sach.php';</script>";
    exit();
} else {
    echo "<script>alert('Lỗi thêm thương hiệu: " . addslashes(mysqli_error($conn)) . "'); window.history.back();</script>";
    exit();
}
