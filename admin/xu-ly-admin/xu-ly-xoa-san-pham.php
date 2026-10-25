<?php
include("../../config/ket-noi.php");

if (!isset($_SESSION['MaTaiKhoan']) || $_SESSION['VaiTro'] !== 'admin') {
    header("Location: ../../nguoi-dung/dang-nhap.php");
    exit();
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    header("Location: ../quan-ly-san-pham-admin/danh-sach.php");
    exit();
}

// Kiểm tra xem sản phẩm có trong đơn hàng nào chưa
$checkDH = mysqli_query($conn, "SELECT COUNT(*) AS Tong FROM chitietdonhang WHERE MaSanPham = $id");
$rowDH = mysqli_fetch_assoc($checkDH);

if ($rowDH && $rowDH['Tong'] > 0) {
    echo "<script>alert('Sản phẩm này đã có trong " . $rowDH['Tong'] . " đơn hàng của khách! Không thể xóa hẳn để bảo toàn dữ liệu hóa đơn. Hệ thống sẽ cập nhật số lượng về 0 (Hết hàng).'); 
          window.location='../quan-ly-san-pham-admin/danh-sach.php';</script>";
    mysqli_query($conn, "UPDATE SanPham SET SoLuong = 0 WHERE MaSanPham = $id");
    exit();
}

// Xóa các chi tiết giỏ hàng và đánh giá liên quan
mysqli_query($conn, "DELETE FROM chitietgiohang WHERE MaSanPham = $id");
mysqli_query($conn, "DELETE FROM danhgia WHERE MaSanPham = $id");

// Lấy tên ảnh để xóa file
$resImg = mysqli_query($conn, "SELECT HinhAnh FROM SanPham WHERE MaSanPham = $id");
if ($resImg && $rowImg = mysqli_fetch_assoc($resImg)) {
    $imgFile = $rowImg['HinhAnh'];
    if (!empty($imgFile)) {
        $imgPath = "../../uploads/san-pham/" . $imgFile;
        if (file_exists($imgPath)) {
            @unlink($imgPath);
        }
    }
}

// Xóa sản phẩm
$sql = "DELETE FROM SanPham WHERE MaSanPham = $id";

if (mysqli_query($conn, $sql)) {
    echo "<script>alert('Đã xóa sản phẩm thành công!'); window.location='../quan-ly-san-pham-admin/danh-sach.php';</script>";
    exit();
} else {
    echo "<script>alert('Không thể xóa sản phẩm: " . addslashes(mysqli_error($conn)) . "'); window.location='../quan-ly-san-pham-admin/danh-sach.php';</script>";
    exit();
}
