<?php
include("../../config/ket-noi.php");

if (!isset($_SESSION['MaTaiKhoan']) || $_SESSION['VaiTro'] !== 'admin') {
    header("Location: ../../nguoi-dung/dang-nhap.php");
    exit();
}

$keyword = "";
$whereSql = "WHERE VaiTro = 'admin'";
if (isset($_GET['keyword']) && !empty($_GET['keyword'])) {
    $keyword = clean_input($conn, $_GET['keyword']);
    $whereSql .= " AND (HoTen LIKE '%$keyword%' OR Email LIKE '%$keyword%' OR SoDienThoai LIKE '%$keyword%')";
}

$sql = "SELECT * FROM TaiKhoan $whereSql ORDER BY MaTaiKhoan ASC";
$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý tài khoản Admin - Shop Laptop</title>
    <link rel="stylesheet" href="../assets-admin/css-admin/header-admin.css?v=<?=time()?>">
    <link rel="stylesheet" href="../assets-admin/css-admin/sidebar-admin.css?v=<?=time()?>">
    <link rel="stylesheet" href="../assets-admin/css-admin/san-pham-admin.css?v=<?=time()?>">
</head>
<body>

    <?php include("../includes-admin/header-admin.php"); ?>
    <?php include("../includes-admin/sidebar-admin.php"); ?>

    <div class="main-content">
        <div class="page-title">
            <h1>QUẢN LÝ TÀI KHOẢN ADMIN</h1>
            <a href="them.php" class="btn-add">+ Thêm quản trị viên mới</a>
        </div>

        <form method="GET" class="search-box">
            <input type="text" name="keyword" placeholder="Tìm theo tên, email, sđt..." value="<?php echo htmlspecialchars($keyword); ?>">
            <button type="submit">Tìm kiếm</button>
        </form>

        <table>
            <thead>
                <tr>
                    <th>Mã</th>
                    <th>Họ và tên</th>
                    <th>Email đăng nhập</th>
                    <th>Số điện thoại</th>
                    <th>Vai trò</th>
                    <th>Ngày tạo</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result && mysqli_num_rows($result) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($result)): 
                        $isCurrent = ($row['MaTaiKhoan'] == $_SESSION['MaTaiKhoan']);
                    ?>
                        <tr>
                            <td>#<?php echo $row['MaTaiKhoan']; ?></td>
                            <td>
                                <strong><?php echo htmlspecialchars($row['HoTen']); ?></strong>
                                <?php if ($isCurrent): ?>
                                    <span style="color: #2ed573; font-size: 12px;">(Bạn đang đăng nhập)</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo htmlspecialchars($row['Email']); ?></td>
                            <td><?php echo htmlspecialchars($row['SoDienThoai'] ?: 'Chưa cập nhật'); ?></td>
                            <td><span style="background: #e74c3c; color: #fff; padding: 3px 8px; border-radius: 4px; font-size: 12px; font-weight: bold;">ADMIN</span></td>
                            <td><?php echo date("d/m/Y H:i", strtotime($row['NgayTao'])); ?></td>
                            <td>
                                <a class="btn-edit" href="sua.php?id=<?php echo $row['MaTaiKhoan']; ?>">Sửa</a>
                                <?php if (!$isCurrent): ?>
                                    <a class="btn-delete" onclick="return confirm('Bạn có chắc muốn xóa tài khoản Admin này?')"
                                       href="../xu-ly-admin/xu-ly-xoa-tai-khoan.php?id=<?php echo $row['MaTaiKhoan']; ?>">Xóa</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 20px;">Không tìm thấy tài khoản quản trị nào.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</body>
</html>


