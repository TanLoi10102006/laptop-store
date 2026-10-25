<?php
include("../config/ket-noi.php");

// Lấy mã hãng từ query
$maHang = isset($_GET['hang']) ? (int)$_GET['hang'] : 1;

// Lấy tên hãng
$sqlHang = "SELECT * FROM HangLaptop WHERE MaHang = $maHang";
$resHang = mysqli_query($conn, $sqlHang);
$hang = mysqli_fetch_assoc($resHang);
$tenHang = $hang ? $hang['TenHang'] : "Laptop";

// Xử lý bộ lọc giá
$whereClauses = ["sp.MaHang = $maHang"];

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
$countSql = "SELECT COUNT(*) AS total FROM SanPham sp WHERE $whereSql";
$countRes = mysqli_query($conn, $countSql);
$totalProducts = 0;
if ($countRes && $rC = mysqli_fetch_assoc($countRes)) {
    $totalProducts = (int)$rC['total'];
}

// Hiển thị ban đầu 8 sản phẩm
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
    <title>Laptop <?php echo htmlspecialchars($tenHang); ?> Chính Hãng - SHOP LAPTOP</title>

    <link rel="stylesheet" href="../assets/css/header.css">
    <link rel="stylesheet" href="../assets/css/menu.css">
    <link rel="stylesheet" href="../assets/css/index.css">
    <link rel="stylesheet" href="../assets/css/footer.css">
    <link rel="stylesheet" href="../assets/css/bo-loc.css">
</head>

<body>

    <?php include("../includes/header.php"); ?>
    <?php include("../includes/menu.php"); ?>

    <section class="banner">
        <img src="../assets/images/banner.jpg" alt="Shop Laptop Banner" width="100%">
    </section>

    <section class="products">
        <div class="section-header">
            <h2>LAPTOP <?php echo strtoupper(htmlspecialchars($tenHang)); ?> CHÍNH HÃNG</h2>
            <p class="section-subtitle">Danh sách laptop <?php echo htmlspecialchars($tenHang); ?> chính hãng, bảo hành
                toàn diện</p>
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

                        <div class="product-brand"><?php echo htmlspecialchars($row['TenHang'] ?? $tenHang); ?></div>

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
                                <a href="../xu-ly/xu-ly-them-gio-hang.php?id=<?php echo (int)$row['MaSanPham']; ?>"
                                    class="btn-add-cart">Thêm vào giỏ</a>
                            <?php else: ?>
                                <button class="btn-disabled" disabled>Hết hàng</button>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php
                }
            } else {
                ?>
                <div class="no-products" style="grid-column: 1/-1; text-align: center; padding: 40px; color: #fff;">
                    <p style="font-size: 18px;">Hiện chưa có sản phẩm nào thuộc hãng
                        <?php echo htmlspecialchars($tenHang); ?> phù hợp với bộ lọc.</p>
                    <a href="thuong-hieu.php?hang=<?php echo $maHang; ?>"
                        style="color: #2f80ed; text-decoration: underline; margin-top: 10px; display: inline-block;">Xem lại
                        tất cả</a>
                </div>
            <?php } ?>
        </div>

        <!-- Khối Load More Products -->
        <?php if ($totalProducts > $initialLimit): ?>
            <div id="load-more-container" class="load-more-wrapper">
                <div id="load-more-error" class="load-more-error" style="display: none;"></div>
                <div id="load-more-loading" class="load-more-loading" style="display: none;">Đang tải thêm laptop...</div>
                <button type="button" id="btn-load-more" class="btn-load-more" data-offset="<?php echo $initialLimit; ?>"
                    data-limit="<?php echo $initialLimit; ?>" data-endpoint="../xu-ly/xu-ly-load-more.php"
                    data-hang="<?php echo $maHang; ?>" data-keyword=""
                    data-mucgia="<?php echo htmlspecialchars($currentMucGia); ?>"
                    data-sapxep="<?php echo htmlspecialchars($currentSapXep); ?>">
                    Xem thêm sản phẩm (còn <?php echo ($totalProducts - $initialLimit); ?>)
                </button>
            </div>
        <?php endif; ?>
    </section>

    <?php include("../includes/footer.php"); ?>

    <script src="../assets/js/load-more.js"></script>
</body>

</html>