<?php
include("../../config/ket-noi.php");

if (!isset($_SESSION['MaTaiKhoan']) || $_SESSION['VaiTro'] !== 'admin') {
    header("Location: ../../nguoi-dung/dang-nhap.php");
    exit();
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    header("Location: danh-sach.php");
    exit();
}

$sql = "SELECT dh.*, tk.HoTen, tk.Email 
        FROM donhang dh 
        LEFT JOIN TaiKhoan tk ON dh.MaTaiKhoan = tk.MaTaiKhoan 
        WHERE dh.MaDonHang = $id";
$res = mysqli_query($conn, $sql);

if (!$res || mysqli_num_rows($res) == 0) {
    echo "<script>alert('Không tìm thấy đơn hàng!'); window.location='danh-sach.php';</script>";
    exit();
}

$order = mysqli_fetch_assoc($res);

// Lấy danh sách sản phẩm trong đơn
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
    <title>Chi tiết đơn hàng #<?php echo $order['MaDonHang']; ?> - Admin</title>
    <link rel="stylesheet" href="../assets-admin/css-admin/header-admin.css?v=<?=time()?>">
    <link rel="stylesheet" href="../assets-admin/css-admin/sidebar-admin.css?v=<?=time()?>">
    <link rel="stylesheet" href="../assets-admin/css-admin/san-pham-admin.css?v=<?=time()?>">
    <link rel="stylesheet" href="../assets-admin/css-admin/them-san-pham-admin.css?v=<?=time()?>">
    <style>
        .order-detail-card {
            background: #232733;
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 25px;
            color: #fff;
        }

        .order-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .info-group {
            margin-bottom: 12px;
            font-size: 15px;
        }

        .info-group span {
            color: #8fa0bc;
            display: inline-block;
            width: 170px;
        }

        .status-form {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-top: 15px;
            padding: 15px;
            background: #1a1d27;
            border-radius: 8px;
        }

        .status-select {
            padding: 10px 15px;
            background: #2a3042;
            color: #fff;
            border: 1px solid #4a5568;
            border-radius: 6px;
            font-size: 15px;
        }
    </style>
</head>

<body>

    <?php include("../includes-admin/header-admin.php"); ?>
    <?php include("../includes-admin/sidebar-admin.php"); ?>

    <div class="main-content">
        <div class="page-title">
            <h1>CHI TIẾT ĐƠN HÀNG #<?php echo $order['MaDonHang']; ?></h1>
            <a href="danh-sach.php" class="btn-add">← Quay lại danh sách</a>
        </div>

        <div class="order-detail-card">
            <h2>Thông tin đơn hàng & Người nhận</h2>
            <div class="order-grid">
                <div>
                    <div class="info-group">
                        <span>Khách hàng:</span>
                        <strong><?php echo htmlspecialchars($order['HoTen'] ?? 'Khách vãng lai'); ?></strong>
                    </div>
                    <div class="info-group">
                        <span>Email:</span>
                        <span><?php echo htmlspecialchars($order['Email'] ?? 'N/A'); ?></span>
                    </div>
                    <div class="info-group">
                        <span>Số điện thoại:</span>
                        <strong><?php echo htmlspecialchars($order['SoDienThoai']); ?></strong>
                    </div>
                    <div class="info-group">
                        <span>Ngày đặt hàng:</span>
                        <span><?php echo date("d/m/Y H:i:s", strtotime($order['NgayDat'])); ?></span>
                    </div>
                </div>

                <div>
                    <div class="info-group">
                        <span>Địa chỉ nhận hàng:</span>
                        <span><?php echo htmlspecialchars($order['DiaChiNhanHang']); ?></span>
                    </div>
                    <div class="info-group">
                        <span>Ghi chú khách:</span>
                        <span><?php echo htmlspecialchars($order['GhiChu'] ?: 'Không có ghi chú'); ?></span>
                    </div>
                    <div class="info-group">
                        <span>Phương thức:</span>
                        <strong><?php echo htmlspecialchars($order['PhuongThucThanhToan'] ?? 'COD'); ?></strong>
                    </div>
                    <div class="info-group">
                        <span>Trạng thái hiện tại:</span>
                        <strong style="color: #f39c12; font-size: 16px;"><?php echo htmlspecialchars($order['TrangThai']); ?></strong>
                    </div>
                </div>
            </div>

            <form action="../xu-ly-admin/xu-ly-sua-don-hang.php" method="POST" class="status-form">
                <input type="hidden" name="MaDonHang" value="<?php echo $order['MaDonHang']; ?>">
                <label><strong>Cập nhật trạng thái đơn hàng:</strong></label>
                <select name="TrangThai" class="status-select">
                    <option value="Chờ xác nhận" <?php if ($order['TrangThai'] == 'Chờ xác nhận') echo 'selected'; ?>>Chờ xác nhận</option>
                    <option value="Đang giao" <?php if ($order['TrangThai'] == 'Đang giao') echo 'selected'; ?>>Đang giao</option>
                    <option value="Đã giao" <?php if ($order['TrangThai'] == 'Đã giao') echo 'selected'; ?>>Đã giao (Hoàn tất)</option>
                    <option value="Đã hủy" <?php if ($order['TrangThai'] == 'Đã hủy') echo 'selected'; ?>>Đã hủy</option>
                </select>
                <button type="submit" class="btn-save" style="padding: 10px 20px;">LƯU TRẠNG THÁI</button>
            </form>
        </div>

        <div class="order-detail-card">
            <h2>Danh sách laptop đặt mua</h2>
            <table>
                <thead>
                    <tr>
                        <th>Hình</th>
                        <th>Tên sản phẩm</th>
                        <th>Thương hiệu</th>
                        <th>Đơn giá</th>
                        <th>Số lượng</th>
                        <th>Thành tiền</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($it = mysqli_fetch_assoc($resCT)):
                        $img = !empty($it['HinhAnh']) && file_exists(__DIR__ . "/../../uploads/san-pham/" . $it['HinhAnh'])
                            ? "../../uploads/san-pham/" . htmlspecialchars($it['HinhAnh'])
                            : "../../uploads/san-pham/default.jpg";
                    ?>
                        <tr>
                            <td><img src="<?php echo $img; ?>" width="60" style="border-radius: 6px;"></td>
                            <td><strong><?php echo htmlspecialchars($it['TenSanPham']); ?></strong></td>
                            <td><?php echo htmlspecialchars($it['TenHang'] ?? 'Laptop'); ?></td>
                            <td><?php echo format_price($it['DonGia']); ?></td>
                            <td><strong>x<?php echo $it['SoLuong']; ?></strong></td>
                            <td style="color: #2ed573; font-weight: bold;"><?php echo format_price($it['DonGia'] * $it['SoLuong']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="5" style="text-align: right; font-size: 16px; font-weight: bold; padding: 15px;">TỔNG TIỀN ĐƠN HÀNG:</td>
                        <td style="color: #2ed573; font-size: 18px; font-weight: bold; padding: 15px;"><?php echo format_price($order['TongTien']); ?></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

</body>

</html>
