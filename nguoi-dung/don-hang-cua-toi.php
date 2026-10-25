<?php
include("../config/ket-noi.php");

// Kiểm tra đăng nhập
if (!isset($_SESSION['MaTaiKhoan'])) {
    header("Location: dang-nhap.php");
    exit();
}

$maTK = (int)$_SESSION['MaTaiKhoan'];

// Lấy danh sách đơn hàng của người dùng này
$sql = "SELECT * FROM donhang WHERE MaTaiKhoan = $maTK ORDER BY MaDonHang DESC";
$res = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đơn hàng của tôi - SHOP LAPTOP</title>

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
            <h1>LỊCH SỬ ĐƠN HÀNG</h1>
            <p>Theo dõi quá trình vận chuyển và thông tin chi tiết các đơn hàng bạn đã đặt</p>
        </div>

        <?php if ($res && mysqli_num_rows($res) > 0): ?>
            <div class="order-list-table-wrap">
                <table class="cart-table">
                    <thead>
                        <tr>
                            <th>Mã đơn</th>
                            <th>Ngày đặt</th>
                            <th>Số điện thoại</th>
                            <th>Địa chỉ nhận hàng</th>
                            <th>Tổng thanh toán</th>
                            <th>Phương thức</th>
                            <th>Trạng thái</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($dh = mysqli_fetch_assoc($res)): 
                            $statusClass = 'status-cho';
                            if ($dh['TrangThai'] == 'Đang giao') $statusClass = 'status-giao';
                            elseif ($dh['TrangThai'] == 'Đã giao') $statusClass = 'status-xong';
                            elseif ($dh['TrangThai'] == 'Đã hủy') $statusClass = 'status-huy';
                        ?>
                            <tr>
                                <td><strong>#<?php echo $dh['MaDonHang']; ?></strong></td>
                                <td><?php echo date("d/m/Y H:i", strtotime($dh['NgayDat'])); ?></td>
                                <td><?php echo htmlspecialchars($dh['SoDienThoai']); ?></td>
                                <td style="max-width: 250px; text-align: left;"><?php echo htmlspecialchars($dh['DiaChiNhanHang']); ?></td>
                                <td style="font-weight: bold; color: #2ed573;"><?php echo format_price($dh['TongTien']); ?></td>
                                <td><?php echo htmlspecialchars($dh['PhuongThucThanhToan'] ?? 'COD'); ?></td>
                                <td>
                                    <span class="badge-status <?php echo $statusClass; ?>">
                                        <?php echo htmlspecialchars($dh['TrangThai']); ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="table-actions">
                                        <a href="chi-tiet-don-hang.php?id=<?php echo $dh['MaDonHang']; ?>" class="btn-sm btn-view">
                                            Xem chi tiết
                                        </a>
                                        <?php if ($dh['TrangThai'] === 'Chờ xác nhận'): ?>
                                            <a href="../xu-ly/xu-ly-huy-don-hang.php?id=<?php echo $dh['MaDonHang']; ?>" 
                                               onclick="return confirm('Bạn có chắc chắn muốn hủy đơn hàng này?')" 
                                               class="btn-sm btn-cancel">
                                                Hủy đơn
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="empty-cart-view">
                <h2>Bạn chưa có đơn hàng nào</h2>
                <p>Khám phá ngay các dòng laptop mới nhất với giá cực kỳ ưu đãi.</p>
                <a href="../index.php" class="btn-empty-shop">Khám phá sản phẩm ngay</a>
            </div>
        <?php endif; ?>
    </main>

    <?php include("../includes/footer.php"); ?>

</body>
</html>
