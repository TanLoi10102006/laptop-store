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
    $whereSql = "WHERE hl.TenHang LIKE '%$keyword%'";
}

$sql = "SELECT hl.*, COUNT(sp.MaSanPham) AS SoLuongSanPham 
        FROM HangLaptop hl 
        LEFT JOIN SanPham sp ON hl.MaHang = sp.MaHang 
        $whereSql 
        GROUP BY hl.MaHang 
        ORDER BY hl.MaHang ASC";
$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Quản lý thương hiệu - Admin</title>
    <link rel="stylesheet" href="../assets-admin/css-admin/header-admin.css?v=<?=time()?>">
    <link rel="stylesheet" href="../assets-admin/css-admin/sidebar-admin.css?v=<?=time()?>">
    <link rel="stylesheet" href="../assets-admin/css-admin/san-pham-admin.css?v=<?=time()?>">
</head>

<body>

    <?php include("../includes-admin/header-admin.php"); ?>
    <?php include("../includes-admin/sidebar-admin.php"); ?>

    <div class="main-content">
        <div class="page-title">
            <h1>QUẢN LÝ THƯƠNG HIỆU (HÃNG LAPTOP)</h1>
            <a href="them.php" class="btn-add">+ Thêm thương hiệu mới</a>
        </div>

        <form method="GET" class="search-box">
            <input type="text" name="keyword" placeholder="Nhập tên hãng cần tìm..." value="<?php echo htmlspecialchars($keyword); ?>">
            <button type="submit">Tìm kiếm</button>
        </form>

        <table>
            <thead>
                <tr>
                    <th width="10%">Mã hãng</th>
                    <th width="50%">Tên thương hiệu</th>
                    <th width="20%">Số lượng laptop</th>
                    <th width="20%">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result && mysqli_num_rows($result) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td>#<?php echo $row['MaHang']; ?></td>
                            <td><strong><?php echo htmlspecialchars($row['TenHang']); ?></strong></td>
                            <td><span style="color: #2ed573; font-weight: bold;"><?php echo $row['SoLuongSanPham']; ?> máy</span></td>
                            <td>
                                <a class="btn-edit" href="sua.php?id=<?php echo $row['MaHang']; ?>">Sửa</a>
                                <a class="btn-delete" onclick="return confirm('Bạn có chắc muốn xóa thương hiệu này?')"
                                    href="../xu-ly-admin/xu-ly-xoa-thuong-hieu.php?id=<?php echo $row['MaHang']; ?>">Xóa</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" style="text-align:center; padding: 20px;">Không tìm thấy thương hiệu nào.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</body>

</html>
