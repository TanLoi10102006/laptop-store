<?php
include("../../config/ket-noi.php");

if (!isset($_SESSION['MaTaiKhoan']) || $_SESSION['VaiTro'] !== 'admin') {
    header("Location: ../../nguoi-dung/dang-nhap.php");
    exit();
}

$keyword = "";
$whereSql = "";

if (isset($_GET['keyword']) && !empty($_GET['keyword'])) {
    $keyword = clean_input($conn, $_GET['keyword']);
    $whereSql = "WHERE sp.TenSanPham LIKE '%$keyword%' OR hl.TenHang LIKE '%$keyword%' OR sp.CPU LIKE '%$keyword%'";
}

$sql = "SELECT sp.*, hl.TenHang 
        FROM SanPham sp 
        LEFT JOIN HangLaptop hl ON sp.MaHang = hl.MaHang 
        $whereSql 
        ORDER BY sp.MaSanPham DESC";

$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý sản phẩm - Admin</title>
    <link rel="stylesheet" href="../assets-admin/css-admin/header-admin.css?v=<?=time()?>">
    <link rel="stylesheet" href="../assets-admin/css-admin/sidebar-admin.css?v=<?=time()?>">
    <link rel="stylesheet" href="../assets-admin/css-admin/san-pham-admin.css?v=<?=time()?>">
</head>
<body>

    <?php include("../includes-admin/header-admin.php"); ?>
    <?php include("../includes-admin/sidebar-admin.php"); ?>

    <div class="main-content">
        <div class="page-title">
            <h1>QUẢN LÝ SẢN PHẨM LAPTOP</h1>
            <a href="them.php" class="btn-add">+ Thêm sản phẩm mới</a>
        </div>

        <form method="GET" class="search-box">
            <input type="text" name="keyword" placeholder="Nhập tên laptop, CPU hoặc hãng..." value="<?php echo htmlspecialchars($keyword); ?>">
            <button type="submit">Tìm kiếm</button>
        </form>

        <table>
            <thead>
                <tr>
                    <th>Mã</th>
                    <th>Hình ảnh</th>
                    <th>Tên sản phẩm</th>
                    <th>Hãng</th>
                    <th>CPU</th>
                    <th>RAM</th>
                    <th>Ổ cứng</th>
                    <th>Màn hình</th>
                    <th>Giá bán</th>
                    <th>Kho</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result && mysqli_num_rows($result) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($result)): 
                        $img = !empty($row['HinhAnh']) && file_exists(__DIR__ . "/../../uploads/san-pham/" . $row['HinhAnh'])
                            ? "../../uploads/san-pham/" . htmlspecialchars($row['HinhAnh'])
                            : "../../uploads/san-pham/default.jpg";
                    ?>
                        <tr>
                            <td>#<?php echo $row['MaSanPham']; ?></td>
                            <td><img src="<?php echo $img; ?>" width="75" style="border-radius: 6px; object-fit: contain;"></td>
                            <td>
                                <strong><?php echo htmlspecialchars($row['TenSanPham']); ?></strong>
                            </td>
                            <td><span style="color: #3498db; font-weight: bold;"><?php echo htmlspecialchars($row['TenHang'] ?? 'N/A'); ?></span></td>
                            <td><?php echo htmlspecialchars($row['CPU']); ?></td>
                            <td><?php echo htmlspecialchars($row['RAM']); ?></td>
                            <td><?php echo htmlspecialchars($row['OCung']); ?></td>
                            <td><?php echo htmlspecialchars($row['ManHinh']); ?></td>
                            <td style="color: #2ed573; font-weight: bold;"><?php echo format_price($row['Gia']); ?></td>
                            <td>
                                <?php if ($row['SoLuong'] <= 0): ?>
                                    <span style="color: #ff4757; font-weight: bold;">Hết hàng</span>
                                <?php else: ?>
                                    <span><?php echo $row['SoLuong']; ?> máy</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a class="btn-edit" href="sua.php?id=<?php echo $row['MaSanPham']; ?>">Sửa</a>
                                <a class="btn-delete" onclick="return confirm('Bạn có chắc muốn xóa sản phẩm này?')"
                                   href="../xu-ly-admin/xu-ly-xoa-san-pham.php?id=<?php echo $row['MaSanPham']; ?>">Xóa</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="11" style="text-align: center; padding: 25px;">Không tìm thấy laptop nào.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</body>
</html>

