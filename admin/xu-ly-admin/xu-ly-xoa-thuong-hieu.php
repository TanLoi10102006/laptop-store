<?php
include("../../config/ket-noi.php");

if (!isset($_SESSION['MaTaiKhoan']) || $_SESSION['VaiTro'] !== 'admin') {
    header("Location: ../../nguoi-dung/dang-nhap.php");
    exit();
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    header("Location: ../quan-ly-thuong-hieu-admin/danh-sach.php");
    exit();
}

// Kiểm tra xem có sản phẩm nào thuộc hãng này không
$checkSP = mysqli_query($conn, "SELECT COUNT(*) AS Tong FROM SanPham WHERE MaHang = $id");
$rowSP = mysqli_fetch_assoc($checkSP);

if ($rowSP && $rowSP['Tong'] > 0) {
    echo "<script>alert('Không thể xóa! Đang có " . $rowSP['Tong'] . " sản phẩm thuộc thương hiệu này. Hãy chuyển hoặc xóa các sản phẩm đó trước.'); window.location='../quan-ly-thuong-hieu-admin/danh-sach.php';</script>";
    exit();
}

$sql = "DELETE FROM HangLaptop WHERE MaHang = $id";

if (mysqli_query($conn, $sql)) {
    echo "<script>alert('Đã xóa thương hiệu thành công!'); window.location='../quan-ly-thuong-hieu-admin/danh-sach.php';</script>";
    exit();
} else {
    echo "<script>alert('Không thể xóa thương hiệu: " . addslashes(mysqli_error($conn)) . "'); window.location='../quan-ly-thuong-hieu-admin/danh-sach.php';</script>";
    exit();
}
