<?php
include("../config/ket-noi.php");

$keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
$safeKeyword = clean_input($conn, $keyword);

// Xây dựng điều kiện tìm kiếm
$whereClauses = ["(sp.TenSanPham LIKE '%$safeKeyword%' OR sp.CPU LIKE '%$safeKeyword%' OR sp.CardDoHoa LIKE '%$safeKeyword%' OR hl.TenHang LIKE '%$safeKeyword%')"];

// Lọc tầm giá
$currentMucGia = isset($_GET['mucgia']) ? clean_input($conn, $_GET['mucgia']) : '';
if (!empty($currentMucGia)) {
    if ($currentMucGia == 'duoi-15') {
        $whereClauses[] = "sp.Gia < 15000000";
    } elseif ($currentMucGia == '15-25') {
        $whereClauses[] = "sp.Gia >= 15000000 AND sp.Gia <= 25000000";
    } elseif ($currentMucGia == 'tren-25') {
        $whereClauses[] = "sp.Gia > 25000000";
    }
}

// Xử lý sắp xếp
$currentSapXep = isset($_GET['sapxep']) ? clean_input($conn, $_GET['sapxep']) : '';
$order = "ORDER BY sp.MaSanPham DESC";
if ($currentSapXep == 'gia-tang') {
    $order = "ORDER BY sp.Gia ASC";
} elseif ($currentSapXep == 'gia-giam') {
    $order = "ORDER BY sp.Gia DESC";
}

$whereSql = implode(" AND ", $whereClauses);

// Đếm tổng số
$countSql = "SELECT COUNT(*) AS total 
             FROM SanPham sp 
             LEFT JOIN HangLaptop hl ON sp.MaHang = hl.MaHang 
             WHERE $whereSql";
$countRes = mysqli_query($conn, $countSql);
$totalFound = 0;
if ($countRes && $rC = mysqli_fetch_assoc($countRes)) {
    $totalFound = (int)$rC['total'];
}

// Giới hạn 8 sản phẩm ban đầu
$initialLimit = 8;
$sql = "SELECT sp.*, hl.TenHang 
        FROM SanPham sp 
        LEFT JOIN HangLaptop hl ON sp.MaHang = hl.MaHang 
        WHERE $whereSql 
        $order 
        LIMIT $initialLimit";
$result = mysqli_query($conn, $sql);
$displayedCount = mysqli_num_rows($result);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tìm kiếm: <?php echo htmlspecialchars($keyword); ?> - SHOP LAPTOP</title>

    <link rel="stylesheet" href="../assets/css/header.css">
    <link rel="stylesheet" href="../assets/css/menu.css">
    <link rel="stylesheet" href="../assets/css/index.css">
    <link rel="stylesheet" href="../assets/css/footer.css">
    <link rel="stylesheet" href="../assets/css/bo-loc.css">
</head>
<body>

    <?php include("../includes/header.php"); ?>
    <?php include("../includes/menu.php"); ?>

    <main class="products" style="margin-top: 30px;">
        <div class="section-header">
            <h2>KẾT QUẢ TÌM KIẾM: "<?php echo htmlspecialchars($keyword); ?>"</h2>
            <p class="section-subtitle">Tìm thấy <strong><?php echo $totalFound; ?></strong> laptop phù hợp</p>
        </div>

        <?php include("../includes/bo-loc.php"); ?>

        <div class="product-list">
            <?php 
            if ($displayedCount > 0) {
                while ($row = mysqli_fetch_assoc($result)) { 
                    $hinhAnh = !empty($row['HinhAnh']) && file_exists(__DIR__ . "/../uploads/san-pham/" . $row['HinhAnh'])
                        ? "../uploads/san-pham/" . htmlspecialchars($row['HinhAnh'])
                        : "../uploads/san-pham/default.jpg";
            ?>
                <div class="product" data-product-id="<?php echo (int)$row['MaSanPham']; ?>">
                    <div class="product-thumb">
                        <a href="chi-tiet.php?id=<?php echo (int)$row['MaSanPham']; ?>">
                            <img src="<?php echo $hinhAnh; ?>" alt="<?php echo htmlspecialchars($row['TenSanPham']); ?>">
                        </a>
                        <?php if ($row['SoLuong'] <= 0): ?>
                            <span class="badge out-of-stock">Tạm hết</span>
                        <?php else: ?>
                            <span class="badge in-stock">Còn <?php echo (int)$row['SoLuong']; ?> máy</span>
                        <?php endif; ?>
                    </div>

                    <div class="product-brand"><?php echo htmlspecialchars($row['TenHang'] ?? 'Laptop'); ?></div>

                    <h3 class="product-title">
                        <a href="chi-tiet.php?id=<?php echo (int)$row['MaSanPham']; ?>">
                            <?php echo htmlspecialchars($row['TenSanPham']); ?>
                        </a>
                    </h3>

                    <div class="product-specs">
                        <span><strong>CPU:</strong> <?php echo htmlspecialchars($row['CPU']); ?></span>
                        <span><strong>RAM:</strong> <?php echo htmlspecialchars($row['RAM']); ?></span>
                        <span><strong>Ổ cứng:</strong> <?php echo htmlspecialchars($row['OCung']); ?></span>
                        <span><strong>Màn hình:</strong> <?php echo htmlspecialchars($row['ManHinh']); ?></span>
                    </div>

                    <div class="price-box">
                        <h4><?php echo format_price($row['Gia']); ?></h4>
                    </div>

                    <div class="action">
                        <a href="chi-tiet.php?id=<?php echo (int)$row['MaSanPham']; ?>" class="btn-detail">Chi tiết</a>
                        <?php if ($row['SoLuong'] > 0): ?>
                            <a href="../xu-ly/xu-ly-them-gio-hang.php?id=<?php echo (int)$row['MaSanPham']; ?>" class="btn-add-cart">Thêm vào giỏ</a>
                        <?php else: ?>
                            <button class="btn-disabled" disabled>Hết hàng</button>
                        <?php endif; ?>
                    </div>
                </div>
            <?php 
                } 
            } else { 
            ?>
                <div class="no-products" style="grid-column: 1/-1; text-align: center; padding: 50px 20px; color: #fff;">
                    <p style="font-size: 20px; font-weight: bold; margin-bottom: 10px;">Không tìm thấy kết quả nào cho từ khóa "<?php echo htmlspecialchars($keyword); ?>"</p>
                    <p style="color: #aaa; margin-bottom: 20px;">Vui lòng thử lại với tên hãng (Dell, Asus, Apple, HP, Acer, Lenovo) hoặc từ khóa cấu hình khác.</p>
                    <a href="../index.php" style="display: inline-block; background: #2f80ed; color: #fff; padding: 10px 20px; border-radius: 8px; text-decoration: none;">Xem tất cả sản phẩm</a>
                </div>
            <?php } ?>
        </div>

        <!-- Khối Load More Products -->
        <?php if ($totalFound > $initialLimit): ?>
            <div id="load-more-container" class="load-more-wrapper">
                <div id="load-more-error" class="load-more-error" style="display: none;"></div>
                <div id="load-more-loading" class="load-more-loading" style="display: none;">Đang tải thêm laptop...</div>
                <button type="button" 
                        id="btn-load-more" 
                        class="btn-load-more"
                        data-offset="<?php echo $initialLimit; ?>"
                        data-limit="<?php echo $initialLimit; ?>"
                        data-endpoint="../xu-ly/xu-ly-load-more.php"
                        data-hang=""
                        data-keyword="<?php echo htmlspecialchars($keyword); ?>"
                        data-mucgia="<?php echo htmlspecialchars($currentMucGia); ?>"
                        data-sapxep="<?php echo htmlspecialchars($currentSapXep); ?>">
                    Xem thêm sản phẩm (còn <?php echo ($totalFound - $initialLimit); ?>)
                </button>
            </div>
        <?php endif; ?>
    </main>

    <?php include("../includes/footer.php"); ?>

    <script src="../assets/js/load-more.js"></script>
</body>
</html>
