<?php
include("../../config/ket-noi.php");

if (!isset($_SESSION['MaTaiKhoan']) || $_SESSION['VaiTro'] !== 'admin') {
    header("Location: ../../nguoi-dung/dang-nhap.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../quan-ly-don-hang-admin/danh-sach.php");
    exit();
}

$id = isset($_POST['MaDonHang']) ? (int)$_POST['MaDonHang'] : 0;
$trangThai = clean_input($conn, $_POST['TrangThai'] ?? '');

if ($id <= 0 || empty($trangThai)) {
    echo "<script>alert('Dữ liệu không hợp lệ!'); window.history.back();</script>";
    exit();
}

// Kiểm tra trạng thái cũ
$sqlOld = "SELECT TrangThai FROM donhang WHERE MaDonHang = $id";
$resOld = mysqli_query($conn, $sqlOld);
$oldOrder = mysqli_fetch_assoc($resOld);

// Nếu chuyển sang Đã hủy và trước đó chưa hủy -> hoàn lại tồn kho
if ($trangThai === 'Đã hủy' && $oldOrder['TrangThai'] !== 'Đã hủy') {
    $resItems = mysqli_query($conn, "SELECT * FROM chitietdonhang WHERE MaDonHang = $id");
    while ($it = mysqli_fetch_assoc($resItems)) {
        $pId = (int)$it['MaSanPham'];
        $qty = (int)$it['SoLuong'];
        mysqli_query($conn, "UPDATE SanPham SET SoLuong = SoLuong + $qty WHERE MaSanPham = $pId");
    }
}

$sql = "UPDATE donhang SET TrangThai = '$trangThai' WHERE MaDonHang = $id";

if (mysqli_query($conn, $sql)) {
    echo "<script>alert('Cập nhật trạng thái đơn hàng thành công!'); window.location='../quan-ly-don-hang-admin/chi-tiet.php?id=$id';</script>";
    exit();
} else {
    echo "<script>alert('Lỗi cập nhật: " . addslashes(mysqli_error($conn)) . "'); window.history.back();</script>";
    exit();
}
