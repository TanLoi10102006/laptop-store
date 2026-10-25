<?php
include("../../config/ket-noi.php");

if (!isset($_SESSION['MaTaiKhoan']) || $_SESSION['VaiTro'] !== 'admin') {
    header("Location: ../../nguoi-dung/dang-nhap.php");
    exit();
}

$keyword = "";
$whereSql = "WHERE tk.VaiTro = 'user'";

if (isset($_GET['keyword']) && !empty($_GET['keyword'])) {
    $keyword = clean_input($conn, $_GET['keyword']);
    $whereSql .= " AND (tk.HoTen LIKE '%$keyword%' OR tk.Email LIKE '%$keyword%' OR tk.SoDienThoai LIKE '%$keyword%')";
}

$sql = "SELECT tk.*, COUNT(dh.MaDonHang) AS TongDonHang 
        FROM TaiKhoan tk 
        LEFT JOIN donhang dh ON tk.MaTaiKhoan = dh.MaTaiKhoan 
        $whereSql 
        GROUP BY tk.MaTaiKhoan 
        ORDER BY tk.MaTaiKhoan DESC";

$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý khách hàng - Admin</title>
    <link rel="stylesheet" href="../assets-admin/css-admin/header-admin.css?v=<?=time()?>">
    <link rel="stylesheet" href="../assets-admin/css-admin/sidebar-admin.css?v=<?=time()?>">
    <link rel="stylesheet" href="../assets-admin/css-admin/danh-sach-khach-hang-admin.css?v=<?=time()?>">
</head>
<body>

    <?php include("../includes-admin/header-admin.php"); ?>
    <?php include("../includes-admin/sidebar-admin.php"); ?>

    <div class="main-content">
        <div class="page-title">
            <h1>QUẢN LÝ KHÁCH HÀNG (USERS)</h1>
        </div>

        <form method="GET" class="search-box">
            <input type="text" name="keyword" placeholder="Tìm theo tên, email hoặc SĐT khách hàng..." value="<?php echo htmlspecialchars($keyword); ?>">
            <button type="submit">Tìm kiếm</button>
        </form>

        <table>
            <thead>
                <tr>
                    <th>Mã</th>
                    <th>Họ và tên</th>
                    <th>Email</th>
                    <th>Số điện thoại</th>
                    <th>Đơn hàng</th>
                    <th>Ngày đăng ký</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result && mysqli_num_rows($result) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td>#<?php echo $row['MaTaiKhoan']; ?></td>
                            <td><strong><?php echo htmlspecialchars($row['HoTen']); ?></strong></td>
                            <td><?php echo htmlspecialchars($row['Email']); ?></td>
                            <td><?php echo htmlspecialchars($row['SoDienThoai'] ?: 'Chưa cập nhật'); ?></td>
                            <td>
                                <a href="../quan-ly-don-hang-admin/danh-sach.php?keyword=<?php echo urlencode($row['HoTen']); ?>" style="color: #2ed573; text-decoration: underline;">
                                    <strong><?php echo $row['TongDonHang']; ?> đơn</strong>
                                </a>
                            </td>
                            <td><?php echo date("d/m/Y H:i", strtotime($row['NgayTao'])); ?></td>
                            <td>
                                <a class="btn-delete" onclick="return confirm('Bạn có chắc muốn xóa khách hàng này cùng toàn bộ đơn hàng/giỏ hàng liên quan?')"
                                   href="../xu-ly-admin/xu-ly-xoa-khach-hang.php?id=<?php echo $row['MaTaiKhoan']; ?>">Xóa</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 25px;">Không tìm thấy khách hàng nào.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</body>
</html>

