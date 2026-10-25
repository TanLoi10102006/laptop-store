<?php
include("../config/ket-noi.php");

if (!isset($_SESSION['MaTaiKhoan'])) {
    header("Location: ../nguoi-dung/dang-nhap.php");
    exit();
}

$maTK = (int)$_SESSION['MaTaiKhoan'];
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    header("Location: ../nguoi-dung/don-hang-cua-toi.php");
    exit();
}

// Kiểm tra đơn hàng có tồn tại và đúng chủ sở hữu
$sql = "SELECT * FROM donhang WHERE MaDonHang = $id AND MaTaiKhoan = $maTK";
$res = mysqli_query($conn, $sql);

if (!$res || mysqli_num_rows($res) == 0) {
    echo "<script>alert('Đơn hàng không tồn tại!'); window.history.back();</script>";
    exit();
}

$order = mysqli_fetch_assoc($res);

if ($order['TrangThai'] !== 'Chờ xác nhận') {
    echo "<script>alert('Chỉ có thể hủy đơn hàng khi đang ở trạng thái Chờ xác nhận!'); window.history.back();</script>";
    exit();
}

// Hoàn trả số lượng tồn kho sản phẩm
$sqlCT = "SELECT * FROM chitietdonhang WHERE MaDonHang = $id";
$resCT = mysqli_query($conn, $sqlCT);
while ($item = mysqli_fetch_assoc($resCT)) {
    $pId = (int)$item['MaSanPham'];
    $qty = (int)$item['SoLuong'];
    mysqli_query($conn, "UPDATE SanPham SET SoLuong = SoLuong + $qty WHERE MaSanPham = $pId");
}

// Cập nhật trạng thái đơn thành 'Đã hủy'
mysqli_query($conn, "UPDATE donhang SET TrangThai = 'Đã hủy' WHERE MaDonHang = $id");

echo "<script>alert('Đã hủy đơn hàng thành công!'); window.location='../nguoi-dung/don-hang-cua-toi.php';</script>";
exit();
