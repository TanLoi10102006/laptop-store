<?php
include("../config/ket-noi.php");

if (!isset($_SESSION['MaTaiKhoan']) || $_SESSION['VaiTro'] !== 'admin') {
    header("Location: ../nguoi-dung/dang-nhap.php");
    exit();
}

// THỐNG KÊ TỔNG QUAN
// Tổng sản phẩm
$sqlSanPham = "SELECT COUNT(*) AS Tong FROM SanPham";
$rowSanPham = mysqli_fetch_assoc(mysqli_query($conn, $sqlSanPham));

// Tổng khách hàng
$sqlKhachHang = "SELECT COUNT(*) AS Tong FROM TaiKhoan WHERE VaiTro='user'";
$rowKhachHang = mysqli_fetch_assoc(mysqli_query($conn, $sqlKhachHang));

// Tổng đơn hàng
$sqlDonHang = "SELECT COUNT(*) AS Tong FROM DonHang";
$rowDonHang = mysqli_fetch_assoc(mysqli_query($conn, $sqlDonHang));

// Doanh thu thực tế (các đơn Đã giao)
$sqlDoanhThu = "SELECT IFNULL(SUM(TongTien),0) AS Tong FROM DonHang WHERE TrangThai='Đã giao'";
$rowDoanhThu = mysqli_fetch_assoc(mysqli_query($conn, $sqlDoanhThu));

// Doanh thu tạm tính (các đơn Đang giao)
$sqlDoanhThuDang = "SELECT IFNULL(SUM(TongTien),0) AS Tong FROM DonHang WHERE TrangThai='Đang giao'";
$rowDoanhThuDang = mysqli_fetch_assoc(mysqli_query($conn, $sqlDoanhThuDang));

// Đơn hàng theo trạng thái
$rowCho = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS Tong FROM DonHang WHERE TrangThai='Chờ xác nhận'"));
$rowDang = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS Tong FROM DonHang WHERE TrangThai='Đang giao'"));
$rowDa = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS Tong FROM DonHang WHERE TrangThai='Đã giao'"));
$rowHuy = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS Tong FROM DonHang WHERE TrangThai='Đã hủy'"));

// 5 sản phẩm mới
$sqlSanPhamMoi = "SELECT sp.*, hl.TenHang FROM SanPham sp LEFT JOIN HangLaptop hl ON sp.MaHang = hl.MaHang ORDER BY MaSanPham DESC LIMIT 5";
$resultSanPhamMoi = mysqli_query($conn, $sqlSanPhamMoi);

// 5 đơn hàng mới
$sqlDonHangMoi = "SELECT DonHang.*, TaiKhoan.HoTen 
                  FROM DonHang 
                  LEFT JOIN TaiKhoan ON DonHang.MaTaiKhoan = TaiKhoan.MaTaiKhoan 
                  ORDER BY DonHang.MaDonHang DESC LIMIT 5";
$resultDonHangMoi = mysqli_query($conn, $sqlDonHangMoi);

// 5 đánh giá mới
$sqlDanhGiaMoi = "SELECT DanhGia.*, TaiKhoan.HoTen, SanPham.TenSanPham 
                  FROM DanhGia 
                  LEFT JOIN TaiKhoan ON DanhGia.MaTaiKhoan = TaiKhoan.MaTaiKhoan 
                  LEFT JOIN SanPham ON DanhGia.MaSanPham = SanPham.MaSanPham 
                  ORDER BY MaDanhGia DESC LIMIT 5";
$resultDanhGiaMoi = mysqli_query($conn, $sqlDanhGiaMoi);

// Thống kê theo thương hiệu
$sqlThongKeHang = "SELECT hl.TenHang, COUNT(sp.MaSanPham) AS SoLuongSP 
                   FROM HangLaptop hl 
                   LEFT JOIN SanPham sp ON hl.MaHang = sp.MaHang 
                   GROUP BY hl.MaHang";
$resThongKeHang = mysqli_query($conn, $sqlThongKeHang);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Thống Kê - SHOP LAPTOP ADMIN</title>
    <link rel="stylesheet" href="assets-admin/css-admin/header-admin.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="assets-admin/css-admin/sidebar-admin.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="assets-admin/css-admin/dashboard-admin.css?v=<?php echo time(); ?>">
</head>
<body>

    <?php include("includes-admin/header-admin.php"); ?>
    <?php include("includes-admin/sidebar-admin.php"); ?>

    <div class="main-content">
        <h1>BẢNG ĐIỀU KHIỂN & BÁO CÁO DOANH THU</h1>

        <!-- Hàng 1: Thống kê cơ bản -->
        <div class="card-container">
            <div class="card">
                <h2><?php echo $rowSanPham['Tong']; ?></h2>
                <p>Tổng sản phẩm laptop</p>
            </div>

            <div class="card">
                <h2><?php echo $rowKhachHang['Tong']; ?></h2>
                <p>Khách hàng thành viên</p>
            </div>

            <div class="card">
                <h2><?php echo $rowDonHang['Tong']; ?></h2>
                <p>Tổng số đơn đặt hàng</p>
            </div>

            <div class="card" style="border-left: 5px solid #2ed573;">
                <h2 style="color: #2ed573;"><?php echo format_price($rowDoanhThu['Tong']); ?></h2>
                <p>Doanh thu đã thu (Đã giao)</p>
            </div>
        </div>

        <!-- Hàng 2: Trạng thái đơn hàng -->
        <div class="card-container">
            <div class="card" style="border-left: 5px solid #f39c12;">
                <h2 style="color: #f39c12;"><?php echo $rowCho['Tong']; ?></h2>
                <p>Đơn chờ xác nhận</p>
            </div>

            <div class="card" style="border-left: 5px solid #3498db;">
                <h2 style="color: #3498db;"><?php echo $rowDang['Tong']; ?></h2>
                <p>Đơn đang giao</p>
            </div>

            <div class="card" style="border-left: 5px solid #2ed573;">
                <h2 style="color: #2ed573;"><?php echo $rowDa['Tong']; ?></h2>
                <p>Đơn đã giao thành công</p>
            </div>

            <div class="card" style="border-left: 5px solid #e74c3c;">
                <h2 style="color: #e74c3c;"><?php echo $rowHuy['Tong']; ?></h2>
                <p>Đơn đã hủy</p>
            </div>
        </div>

        <!-- Đơn hàng mới nhất -->
        <div class="table-box">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                <h2>Đơn hàng mới nhận</h2>
                <a href="quan-ly-don-hang-admin/danh-sach.php" style="color: #3498db; text-decoration: none;">Xem tất cả →</a>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Mã đơn</th>
                        <th>Khách hàng</th>
                        <th>Tổng tiền</th>
                        <th>Ngày đặt</th>
                        <th>Trạng thái</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($resultDonHangMoi) > 0): ?>
                        <?php while ($dh = mysqli_fetch_assoc($resultDonHangMoi)): 
                            $stColor = "orange";
                            if ($dh['TrangThai'] == 'Đang giao') $stColor = "#3498db";
                            elseif ($dh['TrangThai'] == 'Đã giao') $stColor = "#2ed573";
                            elseif ($dh['TrangThai'] == 'Đã hủy') $stColor = "#e74c3c";
                        ?>
                            <tr>
                                <td><strong>#<?php echo $dh['MaDonHang']; ?></strong></td>
                                <td><?php echo htmlspecialchars($dh['HoTen'] ?? 'Khách vãng lai'); ?></td>
                                <td style="color: #2ed573; font-weight: bold;"><?php echo format_price($dh['TongTien']); ?></td>
                                <td><?php echo date("d/m/Y H:i", strtotime($dh['NgayDat'])); ?></td>
                                <td><strong style="color: <?php echo $stColor; ?>;"><?php echo htmlspecialchars($dh['TrangThai']); ?></strong></td>
                                <td>
                                    <a href="quan-ly-don-hang-admin/chi-tiet.php?id=<?php echo $dh['MaDonHang']; ?>" style="color: #3498db; text-decoration: underline;">Chi tiết</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="6" style="text-align: center; padding: 15px;">Chưa có đơn hàng nào.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- 5 Laptop mới thêm -->
        <div class="table-box">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                <h2>Laptop mới nhất trong kho</h2>
                <a href="quan-ly-san-pham-admin/danh-sach.php" style="color: #3498db; text-decoration: none;">Xem tất cả →</a>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Mã</th>
                        <th>Tên sản phẩm</th>
                        <th>Thương hiệu</th>
                        <th>Giá bán</th>
                        <th>Tồn kho</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($sp = mysqli_fetch_assoc($resultSanPhamMoi)): ?>
                        <tr>
                            <td>#<?php echo $sp['MaSanPham']; ?></td>
                            <td><strong><?php echo htmlspecialchars($sp['TenSanPham']); ?></strong></td>
                            <td><?php echo htmlspecialchars($sp['TenHang'] ?? 'N/A'); ?></td>
                            <td style="color: #2ed573; font-weight: bold;"><?php echo format_price($sp['Gia']); ?></td>
                            <td><?php echo $sp['SoLuong']; ?> máy</td>
                            <td>
                                <a href="quan-ly-san-pham-admin/sua.php?id=<?php echo $sp['MaSanPham']; ?>" style="color: #3498db; text-decoration: underline;">Sửa</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <!-- Đánh giá mới nhất -->
        <div class="table-box">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                <h2>Đánh giá từ khách hàng gần đây</h2>
                <a href="quan-ly-binh-luan-admin/danh-sach.php" style="color: #3498db; text-decoration: none;">Xem tất cả →</a>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Mã</th>
                        <th>Khách hàng</th>
                        <th>Sản phẩm</th>
                        <th>Số sao</th>
                        <th>Nội dung</th>
                        <th>Ngày</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($resultDanhGiaMoi) > 0): ?>
                        <?php while ($dg = mysqli_fetch_assoc($resultDanhGiaMoi)): ?>
                            <tr>
                                <td>#<?php echo $dg['MaDanhGia']; ?></td>
                                <td><strong><?php echo htmlspecialchars($dg['HoTen'] ?? 'Khách hàng'); ?></strong></td>
                                <td><?php echo htmlspecialchars($dg['TenSanPham'] ?? 'Laptop'); ?></td>
                                <td style="color: #f1c40f;">
                                    <?php 
                                    for ($i = 1; $i <= $dg['SoSao']; $i++) echo "★";
                                    for ($i = $dg['SoSao'] + 1; $i <= 5; $i++) echo "☆";
                                    ?>
                                </td>
                                <td><?php echo htmlspecialchars($dg['NoiDung']); ?></td>
                                <td><?php echo date("d/m/Y H:i", strtotime($dg['NgayDanhGia'])); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="6" style="text-align: center; padding: 15px;">Chưa có đánh giá nào.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>
