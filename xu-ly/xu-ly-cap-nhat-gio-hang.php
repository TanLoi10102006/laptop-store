<?php
include("../config/ket-noi.php");

$action = isset($_GET['action']) ? $_GET['action'] : '';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    header("Location: ../nguoi-dung/gio-hang.php");
    exit();
}

// Lấy tồn kho sản phẩm
$sqlTon = "SELECT SoLuong FROM SanPham WHERE MaSanPham = $id";
$resTon = mysqli_query($conn, $sqlTon);
$tonKho = 999;
if ($resTon && $rowTon = mysqli_fetch_assoc($resTon)) {
    $tonKho = (int)$rowTon['SoLuong'];
}

if (isset($_SESSION['MaTaiKhoan'])) {
    $maTK = (int)$_SESSION['MaTaiKhoan'];

    // Lấy chi tiết giỏ hàng
    $sqlCT = "SELECT ctg.MaChiTiet, ctg.SoLuong 
              FROM chitietgiohang ctg 
              JOIN giohang gh ON ctg.MaGioHang = gh.MaGioHang 
              WHERE gh.MaTaiKhoan = $maTK AND ctg.MaSanPham = $id";
    $resCT = mysqli_query($conn, $sqlCT);

    if ($resCT && mysqli_num_rows($resCT) > 0) {
        $rowCT = mysqli_fetch_assoc($resCT);
        $maCT = (int)$rowCT['MaChiTiet'];
        $currQty = (int)$rowCT['SoLuong'];

        if ($action === 'tang') {
            $newQty = $currQty + 1;
            if ($newQty > $tonKho) $newQty = $tonKho;
            mysqli_query($conn, "UPDATE chitietgiohang SET SoLuong = $newQty WHERE MaChiTiet = $maCT");
        } elseif ($action === 'giam') {
            $newQty = $currQty - 1;
            if ($newQty <= 0) {
                mysqli_query($conn, "DELETE FROM chitietgiohang WHERE MaChiTiet = $maCT");
            } else {
                mysqli_query($conn, "UPDATE chitietgiohang SET SoLuong = $newQty WHERE MaChiTiet = $maCT");
            }
        }
    }
} else {
    // Session cart
    if (isset($_SESSION['gio_hang'][$id])) {
        if ($action === 'tang') {
            $newQty = $_SESSION['gio_hang'][$id]['so_luong'] + 1;
            if ($newQty > $tonKho) $newQty = $tonKho;
            $_SESSION['gio_hang'][$id]['so_luong'] = $newQty;
        } elseif ($action === 'giam') {
            $newQty = $_SESSION['gio_hang'][$id]['so_luong'] - 1;
            if ($newQty <= 0) {
                unset($_SESSION['gio_hang'][$id]);
            } else {
                $_SESSION['gio_hang'][$id]['so_luong'] = $newQty;
            }
        }
    }
}

header("Location: ../nguoi-dung/gio-hang.php");
exit();
