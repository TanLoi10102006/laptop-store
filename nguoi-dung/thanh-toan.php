<?php
include("../config/ket-noi.php");

// Lấy danh sách sản phẩm trong giỏ
$cartItems = [];
$totalMoney = 0;
$totalCount = 0;

if (isset($_SESSION['MaTaiKhoan'])) {
    $maTK = (int)$_SESSION['MaTaiKhoan'];
    $sql = "SELECT ctg.SoLuong, sp.MaSanPham, sp.TenSanPham, sp.Gia, sp.HinhAnh, sp.SoLuong AS TonKho, hl.TenHang 
            FROM chitietgiohang ctg 
            JOIN giohang gh ON ctg.MaGioHang = gh.MaGioHang 
            JOIN SanPham sp ON ctg.MaSanPham = sp.MaSanPham 
            LEFT JOIN HangLaptop hl ON sp.MaHang = hl.MaHang 
            WHERE gh.MaTaiKhoan = $maTK";
    $res = mysqli_query($conn, $sql);
    if ($res) {
        while ($row = mysqli_fetch_assoc($res)) {
            $row['ThanhTien'] = $row['Gia'] * $row['SoLuong'];
            $totalMoney += $row['ThanhTien'];
            $totalCount += $row['SoLuong'];
            $cartItems[] = $row;
        }
    }
} else {
    if (isset($_SESSION['gio_hang']) && !empty($_SESSION['gio_hang'])) {
        foreach ($_SESSION['gio_hang'] as $item) {
            $id = (int)$item['id'];
            $sql = "SELECT sp.*, hl.TenHang FROM SanPham sp LEFT JOIN HangLaptop hl ON sp.MaHang = hl.MaHang WHERE sp.MaSanPham = $id";
            $res = mysqli_query($conn, $sql);
            if ($res && $sp = mysqli_fetch_assoc($res)) {
                $itemTotal = $sp['Gia'] * $item['so_luong'];
                $totalMoney += $itemTotal;
                $totalCount += $item['so_luong'];
                $cartItems[] = [
                    'MaSanPham' => $sp['MaSanPham'],
                    'TenSanPham' => $sp['TenSanPham'],
                    'TenHang' => $sp['TenHang'],
                    'Gia' => $sp['Gia'],
                    'HinhAnh' => $sp['HinhAnh'],
                    'SoLuong' => $item['so_luong'],
                    'TonKho' => $sp['SoLuong'],
                    'ThanhTien' => $itemTotal
                ];
            }
        }
    }
}

// Nếu giỏ hàng rỗng thì quay về giỏ hàng
if (empty($cartItems)) {
    header("Location: gio-hang.php");
    exit();
}

// Lấy thông tin người dùng nếu đã đăng nhập
$hoTen = "";
$soDienThoai = "";
$email = "";

if (isset($_SESSION['MaTaiKhoan'])) {
    $maTK = (int)$_SESSION['MaTaiKhoan'];
    $sqlUser = "SELECT * FROM TaiKhoan WHERE MaTaiKhoan = $maTK";
    $resUser = mysqli_query($conn, $sqlUser);
    if ($resUser && $user = mysqli_fetch_assoc($resUser)) {
        $hoTen = $user['HoTen'] ?? '';
        $soDienThoai = $user['SoDienThoai'] ?? '';
        $email = $user['Email'] ?? '';
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thanh toán đơn hàng - SHOP LAPTOP</title>

    <link rel="stylesheet" href="../assets/css/header.css">
    <link rel="stylesheet" href="../assets/css/menu.css">
    <link rel="stylesheet" href="../assets/css/index.css">
    <link rel="stylesheet" href="../assets/css/footer.css">
    <link rel="stylesheet" href="../assets/css/gio-hang.css">
</head>
<body>

    <?php include("../includes/header.php"); ?>
    <?php include("../includes/menu.php"); ?>

    <main class="cart-page-container">
        <div class="cart-header">
            <h1>ĐẶT HÀNG & THANH TOÁN</h1>
            <p>Vui lòng điền thông tin người nhận để hoàn tất đơn hàng</p>
        </div>

        <form action="../xu-ly/xu-ly-thanh-toan.php" method="POST" class="checkout-grid">
            <!-- Cột trái: Thông tin giao hàng -->
            <div class="checkout-form-box">
                <h2>1. Thông tin giao hàng</h2>

                <div class="form-group">
                    <label for="HoTen">Họ và tên người nhận <span class="required">*</span></label>
                    <input type="text" id="HoTen" name="HoTen" value="<?php echo htmlspecialchars($hoTen); ?>" placeholder="Ví dụ: Nguyễn Văn A" required>
                </div>

                <div class="form-group">
                    <label for="SoDienThoai">Số điện thoại liên hệ <span class="required">*</span></label>
                    <input type="tel" id="SoDienThoai" name="SoDienThoai" value="<?php echo htmlspecialchars($soDienThoai); ?>" placeholder="Ví dụ: 0987654321" required>
                </div>

                <div class="form-group">
                    <label for="Email">Địa chỉ Email (để nhận mã đơn hàng)</label>
                    <input type="email" id="Email" name="Email" value="<?php echo htmlspecialchars($email); ?>" placeholder="email@example.com">
                </div>

                <div class="form-group">
                    <label for="DiaChiNhanHang">Địa chỉ nhận hàng chi tiết <span class="required">*</span></label>
                    <textarea id="DiaChiNhanHang" name="DiaChiNhanHang" rows="3" placeholder="Số nhà, tên đường, phường/xã, quận/huyện, tỉnh/thành phố..." required></textarea>
                </div>

                <div class="form-group">
                    <label for="GhiChu">Ghi chú đơn hàng (nếu có)</label>
                    <textarea id="GhiChu" name="GhiChu" rows="2" placeholder="Ví dụ: Giao giờ hành chính, gọi trước khi giao..."></textarea>
                </div>

                <h2 style="margin-top: 30px;">2. Phương thức thanh toán</h2>
                <div class="payment-options">
                    <label class="pay-option">
                        <input type="radio" name="PhuongThucThanhToan" value="COD" checked>
                        <div class="pay-desc">
                            <strong>Thanh toán khi nhận hàng (COD)</strong>
                            <p>Kiểm tra laptop đầy đủ trước khi thanh toán cho nhân viên giao hàng</p>
                        </div>
                    </label>

                    <label class="pay-option">
                        <input type="radio" name="PhuongThucThanhToan" value="Chuyển khoản QR">
                        <div class="pay-desc">
                            <strong>Chuyển khoản ngân hàng qua mã QR</strong>
                            <p>Quét mã QR thanh toán nhanh chóng 24/7 qua Vietcombank, Techcombank, MB Bank</p>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Cột phải: Xem lại đơn hàng -->
            <div class="checkout-review-box">
                <h2>3. Sản phẩm trong đơn (<?php echo $totalCount; ?>)</h2>

                <div class="review-items-list">
                    <?php foreach ($cartItems as $item): 
                        $hinh = !empty($item['HinhAnh']) && file_exists(__DIR__ . "/../uploads/san-pham/" . $item['HinhAnh'])
                            ? "../uploads/san-pham/" . htmlspecialchars($item['HinhAnh'])
                            : "../uploads/san-pham/default.jpg";
                    ?>
                        <div class="review-item">
                            <img src="<?php echo $hinh; ?>" alt="<?php echo htmlspecialchars($item['TenSanPham']); ?>">
                            <div class="review-item-info">
                                <h4><?php echo htmlspecialchars($item['TenSanPham']); ?></h4>
                                <p>Số lượng: <strong>x<?php echo $item['SoLuong']; ?></strong></p>
                                <span class="review-item-price"><?php echo format_price($item['ThanhTien']); ?></span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <hr style="margin: 20px 0; border: none; border-top: 1px solid #444;">

                <div class="summary-line">
                    <span>Tạm tính:</span>
                    <span><?php echo format_price($totalMoney); ?></span>
                </div>
                <div class="summary-line">
                    <span>Phí giao hàng:</span>
                    <span class="free-shipping">MIỄN PHÍ TOÀN QUỐC</span>
                </div>
                <div class="summary-line total-line" style="margin-top: 15px;">
                    <span>Tổng tiền thanh toán:</span>
                    <strong class="total-price"><?php echo format_price($totalMoney); ?></strong>
                </div>

                <button type="submit" class="btn-submit-order">
                    XÁC NHẬN ĐẶT HÀNG
                </button>

                <p class="order-secure-note">Thông tin đặt hàng của bạn được bảo mật tuyệt đối.</p>
            </div>
        </form>
    </main>

    <?php include("../includes/footer.php"); ?>

</body>
</html>
