<?php
include("../../config/ket-noi.php");

if (!isset($_SESSION['MaTaiKhoan']) || $_SESSION['VaiTro'] !== 'admin') {
    header("Location: ../../nguoi-dung/dang-nhap.php");
    exit();
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    header("Location: ../quan-ly-don-hang-admin/danh-sach.php");
    exit();
}

// 1. Xóa tất cả chi tiết đơn hàng trước để tránh lỗi Foreign Key Constraint
mysqli_query($conn, "DELETE FROM chitietdonhang WHERE MaDonHang = $id");

// 2. Xóa đơn hàng
$sql = "DELETE FROM donhang WHERE MaDonHang = $id";

if (mysqli_query($conn, $sql)) {
    echo "<script>alert('Đã xóa đơn hàng thành công!'); window.location='../quan-ly-don-hang-admin/danh-sach.php';</script>";
    exit();
} else {
    echo "<script>alert('Lỗi xóa đơn hàng: " . addslashes(mysqli_error($conn)) . "'); window.location='../quan-ly-don-hang-admin/danh-sach.php';</script>";
    exit();
}
