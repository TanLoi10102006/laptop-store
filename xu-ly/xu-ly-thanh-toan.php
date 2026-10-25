<?php
include("../config/ket-noi.php");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../nguoi-dung/gio-hang.php");
    exit();
}

// Lấy thông tin form
$hoTen = clean_input($conn, $_POST['HoTen'] ?? '');
$soDienThoai = clean_input($conn, $_POST['SoDienThoai'] ?? '');
$email = clean_input($conn, $_POST['Email'] ?? '');
$diaChi = clean_input($conn, $_POST['DiaChiNhanHang'] ?? '');
$ghiChu = clean_input($conn, $_POST['GhiChu'] ?? '');
$phuongThuc = clean_input($conn, $_POST['PhuongThucThanhToan'] ?? 'COD');

if (empty($hoTen) || empty($soDienThoai) || empty($diaChi)) {
    echo "<script>alert('Vui lòng điền đầy đủ họ tên, số điện thoại và địa chỉ giao hàng!'); window.history.back();</script>";
    exit();
}

// Lấy danh sách sản phẩm trong giỏ
$cartItems = [];
$totalMoney = 0;
$maGH = 0;

if (isset($_SESSION['MaTaiKhoan'])) {
    $maTK = (int)$_SESSION['MaTaiKhoan'];
    $sqlGH = "SELECT MaGioHang FROM giohang WHERE MaTaiKhoan = $maTK LIMIT 1";
    $resGH = mysqli_query($conn, $sqlGH);
    if ($resGH && $rowGH = mysqli_fetch_assoc($resGH)) {
        $maGH = (int)$rowGH['MaGioHang'];
        $sqlCT = "SELECT ctg.*, sp.TenSanPham, sp.Gia, sp.SoLuong AS TonKho 
                  FROM chitietgiohang ctg 
                  JOIN SanPham sp ON ctg.MaSanPham = sp.MaSanPham 
                  WHERE ctg.MaGioHang = $maGH";
        $resCT = mysqli_query($conn, $sqlCT);
        while ($row = mysqli_fetch_assoc($resCT)) {
            $cartItems[] = [
                'id' => (int)$row['MaSanPham'],
                'ten' => $row['TenSanPham'],
                'gia' => (float)$row['Gia'],
                'so_luong' => (int)$row['SoLuong'],
                'ton_kho' => (int)$row['TonKho']
            ];
            $totalMoney += ((float)$row['Gia'] * (int)$row['SoLuong']);
        }
    }
} else {
    if (isset($_SESSION['gio_hang']) && !empty($_SESSION['gio_hang'])) {
        foreach ($_SESSION['gio_hang'] as $item) {
            $id = (int)$item['id'];
            $sql = "SELECT * FROM SanPham WHERE MaSanPham = $id";
            $res = mysqli_query($conn, $sql);
            if ($res && $sp = mysqli_fetch_assoc($res)) {
                $cartItems[] = [
                    'id' => $id,
                    'ten' => $sp['TenSanPham'],
                    'gia' => (float)$sp['Gia'],
                    'so_luong' => (int)$item['so_luong'],
                    'ton_kho' => (int)$sp['SoLuong']
                ];
                $totalMoney += ((float)$sp['Gia'] * (int)$item['so_luong']);
            }
        }
    }
}

if (empty($cartItems)) {
    echo "<script>alert('Giỏ hàng của bạn đang trống!'); window.location='../nguoi-dung/gio-hang.php';</script>";
    exit();
}

// Kiểm tra số lượng tồn kho
foreach ($cartItems as $it) {
    if ($it['so_luong'] > $it['ton_kho']) {
        echo "<script>alert('Sản phẩm " . addslashes($it['ten']) . " chỉ còn " . $it['ton_kho'] . " máy trong kho. Vui lòng cập nhật lại giỏ hàng!'); window.location='../nguoi-dung/gio-hang.php';</script>";
        exit();
    }
}

// Xác định MaTaiKhoan
$maTKValue = "NULL";
if (isset($_SESSION['MaTaiKhoan'])) {
    $maTKValue = (int)$_SESSION['MaTaiKhoan'];
} elseif (!empty($email)) {
    // Nếu chưa đăng nhập nhưng email trùng tài khoản đã có, gán mã
    $checkUser = mysqli_query($conn, "SELECT MaTaiKhoan FROM TaiKhoan WHERE Email = '$email' LIMIT 1");
    if ($checkUser && $u = mysqli_fetch_assoc($checkUser)) {
        $maTKValue = (int)$u['MaTaiKhoan'];
    }
}

// Tạo đơn hàng
$sqlOrder = "INSERT INTO donhang (MaTaiKhoan, TongTien, TrangThai, DiaChiNhanHang, GhiChu, PhuongThucThanhToan, SoDienThoai) 
             VALUES ($maTKValue, $totalMoney, 'Chờ xác nhận', '$diaChi', '$ghiChu', '$phuongThuc', '$soDienThoai')";

if (!mysqli_query($conn, $sqlOrder)) {
    die("Lỗi đặt hàng: " . mysqli_error($conn));
}

$maDonHang = mysqli_insert_id($conn);

// Thêm chi tiết đơn hàng & trừ tồn kho
foreach ($cartItems as $it) {
    $pId = (int)$it['id'];
    $pQty = (int)$it['so_luong'];
    $pPrice = (float)$it['gia'];

    mysqli_query($conn, "INSERT INTO chitietdonhang (MaDonHang, MaSanPham, SoLuong, DonGia) 
                         VALUES ($maDonHang, $pId, $pQty, $pPrice)");

    mysqli_query($conn, "UPDATE SanPham 
                         SET SoLuong = GREATEST(0, SoLuong - $pQty) 
                         WHERE MaSanPham = $pId");
}

// Xóa giỏ hàng
if ($maGH > 0) {
    mysqli_query($conn, "DELETE FROM chitietgiohang WHERE MaGioHang = $maGH");
}
$_SESSION['gio_hang'] = [];

header("Location: ../nguoi-dung/dat-hang-thanh-cong.php?id=$maDonHang");
exit();
