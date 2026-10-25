<?php
include("../config/ket-noi.php");

// Kiểm tra đăng nhập
if (!isset($_SESSION['MaTaiKhoan'])) {
    header("Location: dang-nhap.php");
    exit();
}

$maTK = (int)$_SESSION['MaTaiKhoan'];
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    header("Location: don-hang-cua-toi.php");
    exit();
}

// Lấy thông tin đơn hàng (đảm bảo đúng chủ sở hữu)
$sql = "SELECT * FROM donhang WHERE MaDonHang = $id AND MaTaiKhoan = $maTK";
$res = mysqli_query($conn, $sql);

if (!$res || mysqli_num_rows($res) == 0) {
    echo "<script>alert('Không tìm thấy đơn hàng hoặc bạn không có quyền xem!'); window.location='don-hang-cua-toi.php';</script>";
    exit();
}

$order = mysqli_fetch_assoc($res);

// Lấy chi tiết các sản phẩm trong đơn
$sqlCT = "SELECT ctdh.*, sp.TenSanPham, sp.HinhAnh, hl.TenHang 
          FROM chitietdonhang ctdh 
          JOIN SanPham sp ON ctdh.MaSanPham = sp.MaSanPham 
          LEFT JOIN HangLaptop hl ON sp.MaHang = hl.MaHang 
          WHERE ctdh.MaDonHang = $id";
$resCT = mysqli_query($conn, $sqlCT);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi tiết đơn hàng #<?php echo $order['MaDonHang']; ?> - SHOP LAPTOP</title>

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
            <h1>CHI TIẾT ĐƠN HÀNG #<?php echo $order['MaDonHang']; ?></h1>
            <p>Thời gian đặt: <strong><?php echo date("d/m/Y H:i:s", strtotime($order['NgayDat'])); ?></strong></p>
        </div>

        <div class="checkout-grid">
            <div class="checkout-form-box">
                <h2>Thông tin nhận hàng</h2>
                <div class="info-row">
                    <span>Số điện thoại:</span>
                    <strong><?php echo htmlspecialchars($order['SoDienThoai']); ?></strong>
                </div>
                <div class="info-row">
                    <span>Địa chỉ giao hàng:</span>
                    <span><?php echo htmlspecialchars($order['DiaChiNhanHang']); ?></span>
                </div>
                <div class="info-row">
                    <span>Ghi chú đơn hàng:</span>
                    <span><?php echo htmlspecialchars($order['GhiChu'] ?: 'Không có ghi chú'); ?></span>
                </div>
                <div class="info-row">
                    <span>Phương thức thanh toán:</span>
                    <strong><?php echo htmlspecialchars($order['PhuongThucThanhToan'] ?? 'COD'); ?></strong>
                </div>
                <div class="info-row">
                    <span>Trạng thái đơn:</span>
                    <strong><?php echo htmlspecialchars($order['TrangThai']); ?></strong>
                </div>

                <div style="margin-top: 30px;">
                    <a href="don-hang-cua-toi.php" class="btn-continue">Quay lại danh sách đơn hàng</a>
                    <?php if ($order['TrangThai'] === 'Chờ xác nhận'): ?>
                        <a href="../xu-ly/xu-ly-huy-don-hang.php?id=<?php echo $order['MaDonHang']; ?>" 
                           onclick="return confirm('Bạn có chắc chắn muốn hủy đơn hàng này?')" 
                           class="btn-clear-cart" style="margin-left: 15px;">
                            Hủy đơn hàng này
                        </a>
                    <?php endif; ?>
                </div>
            </div>

            <div class="checkout-review-box">
                <h2>Danh sách laptop đặt mua</h2>
                <div class="review-items-list">
                    <?php while ($it = mysqli_fetch_assoc($resCT)): 
                        $hinh = !empty($it['HinhAnh']) && file_exists(__DIR__ . "/../uploads/san-pham/" . $it['HinhAnh'])
                            ? "../uploads/san-pham/" . htmlspecialchars($it['HinhAnh'])
                            : "../uploads/san-pham/default.jpg";
                    ?>
                        <div class="review-item">
                            <img src="<?php echo $hinh; ?>" alt="<?php echo htmlspecialchars($it['TenSanPham']); ?>">
                            <div class="review-item-info">
                                <h4><?php echo htmlspecialchars($it['TenSanPham']); ?></h4>
                                <p>Đơn giá: <?php echo format_price($it['DonGia']); ?></p>
                                <p>Số lượng: <strong>x<?php echo $it['SoLuong']; ?></strong></p>
                                <span class="review-item-price"><?php echo format_price($it['DonGia'] * $it['SoLuong']); ?></span>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>

                <hr style="margin: 20px 0; border: none; border-top: 1px solid #444;">

                <div class="summary-line">
                    <span>Tạm tính:</span>
                    <span><?php echo format_price($order['TongTien']); ?></span>
                </div>
                <div class="summary-line">
                    <span>Phí vận chuyển:</span>
                    <span class="free-shipping">MIỄN PHÍ</span>
                </div>
                <div class="summary-line total-line" style="margin-top: 15px;">
                    <span>Tổng số tiền:</span>
                    <strong class="total-price"><?php echo format_price($order['TongTien']); ?></strong>
                </div>
            </div>
        </div>
    </main>

    <?php include("../includes/footer.php"); ?>

</body>
</html>
