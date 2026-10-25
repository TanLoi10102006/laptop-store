<?php
include("../../config/ket-noi.php");

if (!isset($_SESSION['MaTaiKhoan']) || $_SESSION['VaiTro'] !== 'admin') {
    header("Location: ../../nguoi-dung/dang-nhap.php");
    exit();
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    header("Location: ../quan-ly-khach-hang-admin/danh-sach.php");
    exit();
}

/* Không cho xóa admin */
$sql = "SELECT VaiTro FROM TaiKhoan WHERE MaTaiKhoan = $id";
$result = mysqli_query($conn, $sql);

if (!$result || mysqli_num_rows($result) == 0) {
    echo "<script>alert('Khách hàng không tồn tại!'); window.location='../quan-ly-khach-hang-admin/danh-sach.php';</script>";
    exit();
}

$row = mysqli_fetch_assoc($result);

if ($row['VaiTro'] === "admin") {
    echo "<script>alert('Không được xóa tài khoản Admin trong mục này!'); window.location='../quan-ly-khach-hang-admin/danh-sach.php';</script>";
    exit();
}

/* 1. Xóa chi tiết giỏ hàng trước, sau đó xóa giỏ hàng */
mysqli_query($conn, "DELETE ctg FROM chitietgiohang ctg JOIN giohang gh ON ctg.MaGioHang = gh.MaGioHang WHERE gh.MaTaiKhoan = $id");
mysqli_query($conn, "DELETE FROM giohang WHERE MaTaiKhoan = $id");

/* 2. Xóa chi tiết đơn hàng trước, sau đó xóa đơn hàng */
mysqli_query($conn, "DELETE ctdh FROM chitietdonhang ctdh JOIN donhang dh ON ctdh.MaDonHang = dh.MaDonHang WHERE dh.MaTaiKhoan = $id");
mysqli_query($conn, "DELETE FROM donhang WHERE MaTaiKhoan = $id");

/* 3. Xóa đánh giá */
mysqli_query($conn, "DELETE FROM danhgia WHERE MaTaiKhoan = $id");

/* 4. Cuối cùng xóa tài khoản người dùng */
if (mysqli_query($conn, "DELETE FROM TaiKhoan WHERE MaTaiKhoan = $id AND VaiTro = 'user'")) {
    echo "<script>alert('Đã xóa khách hàng và toàn bộ dữ liệu liên quan thành công!'); window.location='../quan-ly-khach-hang-admin/danh-sach.php';</script>";
    exit();
} else {
    echo "<script>alert('Lỗi xóa khách hàng: " . addslashes(mysqli_error($conn)) . "'); window.location='../quan-ly-khach-hang-admin/danh-sach.php';</script>";
    exit();
}
