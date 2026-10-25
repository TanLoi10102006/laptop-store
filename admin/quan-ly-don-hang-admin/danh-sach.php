<?php
include("../../config/ket-noi.php");

if (!isset($_SESSION['MaTaiKhoan']) || $_SESSION['VaiTro'] !== 'admin') {
    header("Location: ../../nguoi-dung/dang-nhap.php");
    exit();
}

$keyword = "";
$whereClauses = ["1=1"];

if (isset($_GET['keyword']) && !empty($_GET['keyword'])) {
    $keyword = clean_input($conn, $_GET['keyword']);
    $whereClauses[] = "(TaiKhoan.HoTen LIKE '%$keyword%' OR DonHang.MaDonHang LIKE '%$keyword%' OR DonHang.SoDienThoai LIKE '%$keyword%')";
}

if (isset($_GET['trangthai']) && !empty($_GET['trangthai'])) {
    $trangThaiFilter = clean_input($conn, $_GET['trangthai']);
    $whereClauses[] = "DonHang.TrangThai = '$trangThaiFilter'";
}

$whereSql = implode(" AND ", $whereClauses);
$sql = "SELECT DonHang.*, TaiKhoan.HoTen 
        FROM DonHang 
        LEFT JOIN TaiKhoan ON DonHang.MaTaiKhoan = TaiKhoan.MaTaiKhoan 
        WHERE $whereSql 
        ORDER BY DonHang.MaDonHang DESC";

$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý đơn hàng - Admin</title>
    <link rel="stylesheet" href="../assets-admin/css-admin/header-admin.css?v=<?=time()?>">
    <link rel="stylesheet" href="../assets-admin/css-admin/sidebar-admin.css?v=<?=time()?>">
    <link rel="stylesheet" href="../assets-admin/css-admin/san-pham-admin.css?v=<?=time()?>">
</head>
<body>

    <?php include("../includes-admin/header-admin.php"); ?>
    <?php include("../includes-admin/sidebar-admin.php"); ?>

    <div class="main-content">
        <div class="page-title">
            <h1>QUẢN LÝ ĐƠN HÀNG</h1>
        </div>

        <form method="GET" class="search-box">
            <input type="text" name="keyword" placeholder="Tìm theo mã đơn, họ tên, số điện thoại..." value="<?php echo htmlspecialchars($keyword); ?>">
            <select name="trangthai" style="height: 42px; border-radius: 6px; padding: 0 10px; background: #2f3542; color: #fff; border: 1px solid #57606f;">
                <option value="">-- Tất cả trạng thái --</option>
                <option value="Chờ xác nhận" <?php if (isset($_GET['trangthai']) && $_GET['trangthai'] == 'Chờ xác nhận') echo 'selected'; ?>>Chờ xác nhận</option>
                <option value="Đang giao" <?php if (isset($_GET['trangthai']) && $_GET['trangthai'] == 'Đang giao') echo 'selected'; ?>>Đang giao</option>
                <option value="Đã giao" <?php if (isset($_GET['trangthai']) && $_GET['trangthai'] == 'Đã giao') echo 'selected'; ?>>Đã giao</option>
                <option value="Đã hủy" <?php if (isset($_GET['trangthai']) && $_GET['trangthai'] == 'Đã hủy') echo 'selected'; ?>>Đã hủy</option>
            </select>
            <button type="submit">Tìm kiếm</button>
        </form>

        <table>
            <thead>
                <tr>
                    <th>Mã đơn</th>
                    <th>Khách hàng</th>
                    <th>Số điện thoại</th>
                    <th>Địa chỉ giao hàng</th>
                    <th>Tổng tiền</th>
                    <th>Ngày đặt</th>
                    <th>Trạng thái</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result && mysqli_num_rows($result) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($result)): 
                        $statusStyle = "color: orange;";
                        if ($row["TrangThai"] == "Đang giao") $statusStyle = "color: #3498db;";
                        elseif ($row["TrangThai"] == "Đã giao") $statusStyle = "color: #2ed573;";
                        elseif ($row["TrangThai"] == "Đã hủy") $statusStyle = "color: #ff4757;";
                    ?>
                        <tr>
                            <td><strong>#<?php echo $row["MaDonHang"]; ?></strong></td>
                            <td><?php echo htmlspecialchars($row["HoTen"] ?? 'Khách vãng lai'); ?></td>
                            <td><?php echo htmlspecialchars($row["SoDienThoai"]); ?></td>
                            <td style="max-width: 200px; text-align: left;"><?php echo htmlspecialchars($row["DiaChiNhanHang"]); ?></td>
                            <td style="font-weight: bold; color: #2ed573;"><?php echo format_price($row["TongTien"]); ?></td>
                            <td><?php echo date("d/m/Y H:i", strtotime($row["NgayDat"])); ?></td>
                            <td>
                                <strong style="<?php echo $statusStyle; ?>"><?php echo htmlspecialchars($row["TrangThai"]); ?></strong>
                            </td>
                            <td>
                                <a class="btn-edit" href="chi-tiet.php?id=<?php echo $row['MaDonHang']; ?>">Chi tiết</a>
                                <a class="btn-delete" onclick="return confirm('Bạn có chắc muốn xóa đơn hàng này? Thao tác này sẽ xóa vĩnh viễn!')"
                                   href="../xu-ly-admin/xu-ly-xoa-don-hang.php?id=<?php echo $row['MaDonHang']; ?>">Xóa</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" style="text-align: center; padding: 25px;">Không có đơn hàng nào phù hợp.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</body>
</html>

