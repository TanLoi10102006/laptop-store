<?php
include("../config/ket-noi.php");

// Lấy ID sản phẩm
$id = 0;
$soLuong = 1;
$action = 'them';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    $soLuong = isset($_POST['so_luong']) ? max(1, (int)$_POST['so_luong']) : 1;
    $action = isset($_POST['action']) ? $_POST['action'] : 'them';
} elseif (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $soLuong = isset($_GET['so_luong']) ? max(1, (int)$_GET['so_luong']) : 1;
}

if ($id <= 0) {
    header("Location: ../index.php");
    exit();
}

// Kiểm tra sản phẩm trong CSDL
$sqlSP = "SELECT * FROM SanPham WHERE MaSanPham = $id";
$resSP = mysqli_query($conn, $sqlSP);

if (!$resSP || mysqli_num_rows($resSP) == 0) {
    echo "<script>alert('Sản phẩm không tồn tại!'); window.history.back();</script>";
    exit();
}

$sp = mysqli_fetch_assoc($resSP);

// Kiểm tra số lượng tồn kho
if ($sp['SoLuong'] <= 0) {
    echo "<script>alert('Sản phẩm này tạm thời hết hàng!'); window.history.back();</script>";
    exit();
}

// Giới hạn số lượng mua không vượt quá tồn kho
if ($soLuong > $sp['SoLuong']) {
    $soLuong = $sp['SoLuong'];
}

// Nếu đã đăng nhập: Lưu vào bảng giohang & chitietgiohang
if (isset($_SESSION['MaTaiKhoan'])) {
    $maTK = (int)$_SESSION['MaTaiKhoan'];

    // Tìm giỏ hàng hiện tại của user
    $sqlGH = "SELECT MaGioHang FROM giohang WHERE MaTaiKhoan = $maTK LIMIT 1";
    $resGH = mysqli_query($conn, $sqlGH);

    if (mysqli_num_rows($resGH) > 0) {
        $rowGH = mysqli_fetch_assoc($resGH);
        $maGH = (int)$rowGH['MaGioHang'];
    } else {
        mysqli_query($conn, "INSERT INTO giohang (MaTaiKhoan) VALUES ($maTK)");
        $maGH = mysqli_insert_id($conn);
    }

    // Kiểm tra sản phẩm đã có trong chitietgiohang chưa
    $sqlCT = "SELECT MaChiTiet, SoLuong FROM chitietgiohang WHERE MaGioHang = $maGH AND MaSanPham = $id";
    $resCT = mysqli_query($conn, $sqlCT);

    if (mysqli_num_rows($resCT) > 0) {
        $rowCT = mysqli_fetch_assoc($resCT);
        $newQty = $rowCT['SoLuong'] + $soLuong;
        if ($newQty > $sp['SoLuong']) {
            $newQty = $sp['SoLuong'];
        }
        $maCT = $rowCT['MaChiTiet'];
        mysqli_query($conn, "UPDATE chitietgiohang SET SoLuong = $newQty WHERE MaChiTiet = $maCT");
    } else {
        mysqli_query($conn, "INSERT INTO chitietgiohang (MaGioHang, MaSanPham, SoLuong) VALUES ($maGH, $id, $soLuong)");
    }
} else {
    // Nếu chưa đăng nhập: Lưu vào SESSION
    if (!isset($_SESSION['gio_hang'])) {
        $_SESSION['gio_hang'] = [];
    }

    if (isset($_SESSION['gio_hang'][$id])) {
        $newQty = $_SESSION['gio_hang'][$id]['so_luong'] + $soLuong;
        if ($newQty > $sp['SoLuong']) {
            $newQty = $sp['SoLuong'];
        }
        $_SESSION['gio_hang'][$id]['so_luong'] = $newQty;
    } else {
        $_SESSION['gio_hang'][$id] = [
            'id' => $sp['MaSanPham'],
            'ten' => $sp['TenSanPham'],
            'gia' => $sp['Gia'],
            'hinh' => $sp['HinhAnh'],
            'so_luong' => $soLuong,
            'ton_kho' => $sp['SoLuong']
        ];
    }
}

// Chuyển hướng
if ($action === 'muangay') {
    header("Location: ../nguoi-dung/thanh-toan.php");
    exit();
} else {
    header("Location: ../nguoi-dung/gio-hang.php");
    exit();
}
