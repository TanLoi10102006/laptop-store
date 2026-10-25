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
    $whereSql = "WHERE TaiKhoan.HoTen LIKE '%$keyword%' OR SanPham.TenSanPham LIKE '%$keyword%' OR DanhGia.NoiDung LIKE '%$keyword%'";
}

$sql = "SELECT DanhGia.*, TaiKhoan.HoTen, SanPham.TenSanPham, SanPham.HinhAnh 
        FROM DanhGia 
        LEFT JOIN TaiKhoan ON DanhGia.MaTaiKhoan = TaiKhoan.MaTaiKhoan 
        LEFT JOIN SanPham ON DanhGia.MaSanPham = SanPham.MaSanPham 
        $whereSql 
        ORDER BY DanhGia.MaDanhGia DESC";

$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý bình luận & đánh giá - Admin</title>
    <link rel="stylesheet" href="../assets-admin/css-admin/header-admin.css?v=<?=time()?>">
    <link rel="stylesheet" href="../assets-admin/css-admin/sidebar-admin.css?v=<?=time()?>">
    <link rel="stylesheet" href="../assets-admin/css-admin/san-pham-admin.css?v=<?=time()?>">
</head>
<body>

    <?php include("../includes-admin/header-admin.php"); ?>
    <?php include("../includes-admin/sidebar-admin.php"); ?>

    <div class="main-content">
        <div class="page-title">
            <h1>QUẢN LÝ BÌNH LUẬN & ĐÁNH GIÁ</h1>
        </div>

        <form method="GET" class="search-box">
            <input type="text" name="keyword" placeholder="Tìm theo tên khách hàng, tên laptop hoặc nội dung..." value="<?php echo htmlspecialchars($keyword); ?>">
            <button type="submit">Tìm kiếm</button>
        </form>

        <table>
            <thead>
                <tr>
                    <th>Mã</th>
                    <th>Khách hàng</th>
                    <th>Sản phẩm</th>
                    <th>Số sao</th>
                    <th>Nội dung bình luận</th>
                    <th>Thời gian</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result && mysqli_num_rows($result) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td>#<?php echo $row['MaDanhGia']; ?></td>
                            <td><strong><?php echo htmlspecialchars($row['HoTen'] ?? 'Khách vãng lai'); ?></strong></td>
                            <td style="max-width: 200px;">
                                <a href="../../pages/chi-tiet.php?id=<?php echo $row['MaSanPham']; ?>" target="_blank" style="color: #3498db; text-decoration: underline;">
                                    <?php echo htmlspecialchars($row['TenSanPham'] ?? 'Laptop đã xóa'); ?>
                                </a>
                            </td>
                            <td>
                                <span style="color: #f1c40f; font-size: 16px;">
                                    <?php 
                                    for ($s = 1; $s <= 5; $s++) {
                                        echo ($s <= $row['SoSao']) ? '★' : '☆';
                                    }
                                    ?>
                                </span>
                            </td>
                            <td style="text-align: left; max-width: 350px;">
                                <?php echo nl2br(htmlspecialchars($row['NoiDung'])); ?>
                            </td>
                            <td><?php echo date("d/m/Y H:i", strtotime($row['NgayDanhGia'])); ?></td>
                            <td>
                                <a class="btn-delete" onclick="return confirm('Bạn có chắc muốn xóa bình luận này?')"
                                   href="../xu-ly-admin/xu-ly-xoa-binh-luan.php?id=<?php echo $row['MaDanhGia']; ?>">Xóa</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 25px;">Chưa có bình luận nào.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</body>
</html>

