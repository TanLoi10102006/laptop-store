<?php
include("../config/ket-noi.php");

$cartItems = [];
$totalMoney = 0;
$totalCount = 0;

if (isset($_SESSION['MaTaiKhoan'])) {
    $maTK = (int)$_SESSION['MaTaiKhoan'];
    $sql = "SELECT ctg.MaChiTiet, ctg.SoLuong, sp.MaSanPham, sp.TenSanPham, sp.Gia, sp.HinhAnh, sp.SoLuong AS TonKho, hl.TenHang 
            FROM chitietgiohang ctg 
            JOIN giohang gh ON ctg.MaGioHang = gh.MaGioHang 
            JOIN SanPham sp ON ctg.MaSanPham = sp.MaSanPham 
            LEFT JOIN HangLaptop hl ON sp.MaHang = hl.MaHang 
            WHERE gh.MaTaiKhoan = $maTK 
            ORDER BY ctg.MaChiTiet DESC";
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
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giỏ hàng - SHOP LAPTOP</title>

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
            <h1>GIỎ HÀNG</h1>
            <p>Có <strong><?php echo $totalCount; ?></strong> sản phẩm trong giỏ hàng của bạn</p>
        </div>

        <?php if (!empty($cartItems)): ?>
            <div class="cart-content-grid">
                <!-- Cột bảng giỏ hàng -->
                <div class="cart-table-wrap">
                    <table class="cart-table">
                        <thead>
                            <tr>
                                <th>Sản phẩm</th>
                                <th>Đơn giá</th>
                                <th>Số lượng</th>
                                <th>Thành tiền</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($cartItems as $item): 
                                $hinh = !empty($item['HinhAnh']) && file_exists(__DIR__ . "/../uploads/san-pham/" . $item['HinhAnh'])
                                    ? "../uploads/san-pham/" . htmlspecialchars($item['HinhAnh'])
                                    : "../uploads/san-pham/default.jpg";
                            ?>
                                <tr>
                                    <td class="product-col">
                                        <div class="cart-product-info">
                                            <a href="../pages/chi-tiet.php?id=<?php echo $item['MaSanPham']; ?>">
                                                <img src="<?php echo $hinh; ?>" alt="<?php echo htmlspecialchars($item['TenSanPham']); ?>">
                                            </a>
                                            <div>
                                                <span class="cart-brand"><?php echo htmlspecialchars($item['TenHang'] ?? ''); ?></span>
                                                <a href="../pages/chi-tiet.php?id=<?php echo $item['MaSanPham']; ?>" class="cart-pname">
                                                    <?php echo htmlspecialchars($item['TenSanPham']); ?>
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="price-col">
                                        <?php echo format_price($item['Gia']); ?>
                                    </td>
                                    <td class="qty-col">
                                        <div class="cart-qty-ctrl">
                                            <a href="../xu-ly/xu-ly-cap-nhat-gio-hang.php?action=giam&id=<?php echo $item['MaSanPham']; ?>" class="btn-qty">-</a>
                                            <span class="qty-num"><?php echo $item['SoLuong']; ?></span>
                                            <a href="../xu-ly/xu-ly-cap-nhat-gio-hang.php?action=tang&id=<?php echo $item['MaSanPham']; ?>" class="btn-qty">+</a>
                                        </div>
                                    </td>
                                    <td class="subtotal-col">
                                        <?php echo format_price($item['ThanhTien']); ?>
                                    </td>
                                    <td class="action-col">
                                        <a href="../xu-ly/xu-ly-xoa-gio-hang.php?id=<?php echo $item['MaSanPham']; ?>" 
                                           onclick="return confirm('Bạn muốn xóa laptop này khỏi giỏ hàng?')" 
                                           class="btn-remove-item" title="Xóa">
                                            Xóa
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                    <div class="cart-bottom-actions">
                        <a href="../index.php" class="btn-continue">Tiếp tục xem sản phẩm</a>
                        <a href="../xu-ly/xu-ly-xoa-gio-hang.php?action=xoa_tat_ca" 
                           onclick="return confirm('Bạn có chắc muốn làm trống giỏ hàng?')" 
                           class="btn-clear-cart">
                            Xóa toàn bộ giỏ hàng
                        </a>
                    </div>
                </div>

                <!-- Cột tóm tắt đơn hàng -->
                <div class="cart-summary-card">
                    <h2>TÓM TẮT ĐƠN HÀNG</h2>
                    <div class="summary-line">
                        <span>Số lượng sản phẩm:</span>
                        <strong><?php echo $totalCount; ?> máy</strong>
                    </div>
                    <div class="summary-line">
                        <span>Tạm tính:</span>
                        <span><?php echo format_price($totalMoney); ?></span>
                    </div>
                    <div class="summary-line">
                        <span>Phí vận chuyển:</span>
                        <span class="free-shipping">MIỄN PHÍ</span>
                    </div>
                    <hr>
                    <div class="summary-line total-line">
                        <span>Tổng thanh toán:</span>
                        <strong class="total-price"><?php echo format_price($totalMoney); ?></strong>
                    </div>

                    <a href="thanh-toan.php" class="btn-checkout">
                        TIẾN HÀNH THANH TOÁN
                    </a>

                    <div class="cart-features">
                        <p>Bảo hành chính hãng 12 - 24 tháng</p>
                        <p>1 đổi 1 trong 30 ngày nếu có lỗi</p>
                        <p>Miễn phí giao hàng trên toàn quốc</p>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="empty-cart-view">
                <h2>Giỏ hàng của bạn đang trống</h2>
                <p>Hãy chọn cho mình chiếc laptop ưng ý nhất tại hệ thống của chúng tôi.</p>
                <a href="../index.php" class="btn-empty-shop">Khám phá sản phẩm</a>
            </div>
        <?php endif; ?>
    </main>

    <?php include("../includes/footer.php"); ?>

</body>
</html>
