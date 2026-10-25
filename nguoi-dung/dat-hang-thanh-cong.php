<?php
include("../config/ket-noi.php");

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    header("Location: ../index.php");
    exit();
}

$sql = "SELECT dh.*, tk.HoTen AS TenKhach 
        FROM donhang dh 
        LEFT JOIN TaiKhoan tk ON dh.MaTaiKhoan = tk.MaTaiKhoan 
        WHERE dh.MaDonHang = $id";
$res = mysqli_query($conn, $sql);

if (!$res || mysqli_num_rows($res) == 0) {
    header("Location: ../index.php");
    exit();
}

$order = mysqli_fetch_assoc($res);

// Lấy chi tiết sản phẩm đã đặt
$sqlCT = "SELECT ctdh.*, sp.TenSanPham, sp.HinhAnh 
          FROM chitietdonhang ctdh 
          JOIN SanPham sp ON ctdh.MaSanPham = sp.MaSanPham 
          WHERE ctdh.MaDonHang = $id";
$resCT = mysqli_query($conn, $sqlCT);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đặt hàng thành công #<?php echo $order['MaDonHang']; ?> - SHOP LAPTOP</title>

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
        <div class="order-success-card">
            <h1>ĐẶT HÀNG THÀNH CÔNG</h1>
            <p class="success-msg">Cảm ơn bạn đã tin tưởng mua sắm tại <strong>SHOP LAPTOP</strong>. Đơn hàng của bạn đã được tiếp nhận và nhân viên sẽ liên hệ xác nhận trong thời gian sớm nhất.</p>

            <div class="order-code-box">
                <span>MÃ ĐƠN HÀNG:</span>
                <strong>#<?php echo $order['MaDonHang']; ?></strong>
            </div>

            <!-- Hướng dẫn chuyển khoản nếu chọn QR -->
            <?php if (strpos($order['PhuongThucThanhToan'] ?? '', 'QR') !== false): ?>
                <div class="qr-payment-box">
                    <h3>THÔNG TIN THANH TOÁN CHUYỂN KHOẢN</h3>
                    <p>Vui lòng chuyển khoản đúng số tiền với nội dung dưới đây để đơn hàng được xử lý ngay lập tức:</p>
                    <div class="qr-details">
                        <p><strong>Ngân hàng:</strong> Ngân hàng TMCP Ngoại Thương Việt Nam (Vietcombank)</p>
                        <p><strong>Số tài khoản:</strong> 999988886666</p>
                        <p><strong>Chủ tài khoản:</strong> CONG TY TNHH SHOP LAPTOP</p>
                        <p><strong>Số tiền:</strong> <span class="qr-money"><?php echo format_price($order['TongTien']); ?></span></p>
                        <p><strong>Nội dung chuyển khoản:</strong> <span class="qr-content">LAPTOP <?php echo $order['MaDonHang']; ?></span></p>
                    </div>
                </div>
            <?php endif; ?>

            <div class="order-info-summary">
                <h3>Chi tiết giao nhận</h3>
                <div class="info-row">
                    <span>Số điện thoại:</span>
                    <strong><?php echo htmlspecialchars($order['SoDienThoai']); ?></strong>
                </div>
                <div class="info-row">
                    <span>Địa chỉ giao hàng:</span>
                    <span><?php echo htmlspecialchars($order['DiaChiNhanHang']); ?></span>
                </div>
                <div class="info-row">
                    <span>Phương thức thanh toán:</span>
                    <strong><?php echo htmlspecialchars($order['PhuongThucThanhToan'] ?? 'COD'); ?></strong>
                </div>
                <div class="info-row">
                    <span>Trạng thái:</span>
                    <span class="status-badge-pending"><?php echo htmlspecialchars($order['TrangThai']); ?></span>
                </div>
            </div>

            <div class="order-items-table">
                <h3>Sản phẩm đã đặt</h3>
                <?php while ($it = mysqli_fetch_assoc($resCT)): 
                    $hinh = !empty($it['HinhAnh']) && file_exists(__DIR__ . "/../uploads/san-pham/" . $it['HinhAnh'])
                        ? "../uploads/san-pham/" . htmlspecialchars($it['HinhAnh'])
                        : "../uploads/san-pham/default.jpg";
                ?>
                    <div class="order-item-row">
                        <img src="<?php echo $hinh; ?>" alt="<?php echo htmlspecialchars($it['TenSanPham']); ?>">
                        <div class="order-item-title">
                            <strong><?php echo htmlspecialchars($it['TenSanPham']); ?></strong>
                            <span>Số lượng: x<?php echo $it['SoLuong']; ?></span>
                        </div>
                        <div class="order-item-price">
                            <?php echo format_price($it['DonGia'] * $it['SoLuong']); ?>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>

            <div class="order-total-bar">
                <span>TỔNG TIỀN:</span>
                <strong><?php echo format_price($order['TongTien']); ?></strong>
            </div>

            <div class="success-buttons">
                <?php if (isset($_SESSION['MaTaiKhoan'])): ?>
                    <a href="don-hang-cua-toi.php" class="btn-view-orders">Quản lý đơn hàng của tôi</a>
                <?php endif; ?>
                <a href="../index.php" class="btn-back-home">Tiếp tục mua sắm</a>
            </div>
        </div>
    </main>

    <?php include("../includes/footer.php"); ?>

</body>
</html>
