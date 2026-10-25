<?php
include("../config/ket-noi.php");

$action = isset($_GET['action']) ? $_GET['action'] : '';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (isset($_SESSION['MaTaiKhoan'])) {
    $maTK = (int)$_SESSION['MaTaiKhoan'];

    // Lấy giỏ hàng
    $sqlGH = "SELECT MaGioHang FROM giohang WHERE MaTaiKhoan = $maTK LIMIT 1";
    $resGH = mysqli_query($conn, $sqlGH);

    if ($resGH && mysqli_num_rows($resGH) > 0) {
        $rowGH = mysqli_fetch_assoc($resGH);
        $maGH = (int)$rowGH['MaGioHang'];

        if ($action === 'xoa_tat_ca') {
            mysqli_query($conn, "DELETE FROM chitietgiohang WHERE MaGioHang = $maGH");
        } elseif ($id > 0) {
            mysqli_query($conn, "DELETE FROM chitietgiohang WHERE MaGioHang = $maGH AND MaSanPham = $id");
        }
    }
} else {
    // Session cart
    if ($action === 'xoa_tat_ca') {
        $_SESSION['gio_hang'] = [];
    } elseif ($id > 0 && isset($_SESSION['gio_hang'][$id])) {
        unset($_SESSION['gio_hang'][$id]);
    }
}

header("Location: ../nguoi-dung/gio-hang.php");
exit();