<?php
include("../config/ket-noi.php");

// Kiểm tra ID sản phẩm
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: ../index.php");
    exit();
}

$id = (int)$_GET['id'];

// Lấy thông tin sản phẩm và thương hiệu
$sql = "SELECT sp.*, hl.TenHang 
        FROM SanPham sp 
        LEFT JOIN HangLaptop hl ON sp.MaHang = hl.MaHang 
        WHERE sp.MaSanPham = $id";
$res = mysqli_query($conn, $sql);

if (!$res || mysqli_num_rows($res) == 0) {
    echo "<script>alert('Sản phẩm không tồn tại hoặc đã ngừng kinh doanh!'); window.location='../index.php';</script>";
    exit();
}

$sp = mysqli_fetch_assoc($res);

// Lấy danh sách ảnh thật từ bảng hinhanhsanpham (hoặc fallback ảnh chính)
$galleryImages = [];
$sqlImg = "SELECT DuongDan FROM hinhanhsanpham WHERE MaSanPham = $id ORDER BY ThuTu ASC, MaHinhAnh ASC";
$resImg = mysqli_query($conn, $sqlImg);

if ($resImg && mysqli_num_rows($resImg) > 0) {
    while ($rImg = mysqli_fetch_assoc($resImg)) {
        if (!empty($rImg['DuongDan']) && file_exists(__DIR__ . "/../uploads/san-pham/" . $rImg['DuongDan'])) {
            $galleryImages[] = $rImg['DuongDan'];
        }
    }
}

// Nếu bảng hinhanhsanpham chưa có ảnh nào thì dùng ảnh từ cột HinhAnh của sản phẩm
if (empty($galleryImages)) {
    if (!empty($sp['HinhAnh']) && file_exists(__DIR__ . "/../uploads/san-pham/" . $sp['HinhAnh'])) {
        $galleryImages[] = $sp['HinhAnh'];
    } else {
        $galleryImages[] = 'default.jpg';
    }
}

// Lấy danh sách đánh giá của sản phẩm này
$sqlReviews = "SELECT dg.*, tk.HoTen 
               FROM DanhGia dg 
               LEFT JOIN TaiKhoan tk ON dg.MaTaiKhoan = tk.MaTaiKhoan 
               WHERE dg.MaSanPham = $id 
               ORDER BY dg.MaDanhGia DESC";
$resReviews = mysqli_query($conn, $sqlReviews);

// Tính điểm đánh giá trung bình
$sqlAvg = "SELECT AVG(SoSao) AS DiemTB, COUNT(*) AS TongDG 
           FROM DanhGia 
           WHERE MaSanPham = $id";
$rowAvg = mysqli_fetch_assoc(mysqli_query($conn, $sqlAvg));
$diemTB = $rowAvg['DiemTB'] ? round($rowAvg['DiemTB'], 1) : 5.0;
$tongDG = (int)$rowAvg['TongDG'];

// Lấy sản phẩm tương tự cùng hãng
$maHang = (int)$sp['MaHang'];
$sqlLienQuan = "SELECT * FROM SanPham 
                WHERE MaHang = $maHang AND MaSanPham != $id 
                ORDER BY MaSanPham DESC LIMIT 4";
$resLienQuan = mysqli_query($conn, $sqlLienQuan);

$mainImage = "../uploads/san-pham/" . htmlspecialchars($galleryImages[0]);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($sp['TenSanPham']); ?> - SHOP LAPTOP</title>

    <link rel="stylesheet" href="../assets/css/header.css">
    <link rel="stylesheet" href="../assets/css/menu.css">
    <link rel="stylesheet" href="../assets/css/index.css">
    <link rel="stylesheet" href="../assets/css/footer.css">
    <link rel="stylesheet" href="../assets/css/chi-tiet.css?v=<?php echo time(); ?>">
</head>
<body>

    <?php include("../includes/header.php"); ?>
    <?php include("../includes/menu.php"); ?>

    <div class="breadcrumb-container">
        <ul class="breadcrumb">
            <li><a href="../index.php">Trang chủ</a></li>
            <li><a href="thuong-hieu.php?hang=<?php echo $sp['MaHang']; ?>"><?php echo htmlspecialchars($sp['TenHang'] ?? 'Laptop'); ?></a></li>
            <li class="active"><?php echo htmlspecialchars($sp['TenSanPham']); ?></li>
        </ul>
    </div>

    <main class="product-detail-page">
        <div class="detail-container">
            <!-- Cột trái: Gallery Hình ảnh -->
            <div class="detail-gallery">
                <div class="main-image-wrap">
                    <img id="main-product-image" src="<?php echo $mainImage; ?>" alt="<?php echo htmlspecialchars($sp['TenSanPham']); ?>">
                    
                    <?php if (count($galleryImages) > 1): ?>
                        <button type="button" id="gallery-prev-btn" class="gallery-nav-btn gallery-prev" aria-label="Ảnh trước">&#10094;</button>
                        <button type="button" id="gallery-next-btn" class="gallery-nav-btn gallery-next" aria-label="Ảnh tiếp theo">&#10095;</button>
                        <div class="gallery-counter"><span id="gallery-current-idx">1</span>/<?php echo count($galleryImages); ?></div>
                    <?php endif; ?>
                </div>

                <!-- Dải ảnh thu nhỏ (Thumbnails) -->
                <?php if (count($galleryImages) > 1): ?>
                    <div class="gallery-thumb-container">
                        <div id="gallery-thumb-track" class="gallery-thumb-track">
                            <?php foreach ($galleryImages as $index => $imgName): 
                                $thumbSrc = "../uploads/san-pham/" . htmlspecialchars($imgName);
                            ?>
                                <div class="gallery-thumb-item <?php echo ($index === 0) ? 'active' : ''; ?>" 
                                     data-full-img="<?php echo $thumbSrc; ?>">
                                    <img src="<?php echo $thumbSrc; ?>" alt="Ảnh thumbnail <?php echo $index + 1; ?>">
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="guarantee-box">
                    <div class="guarantee-item">Bảo hành chính hãng 12 - 24 tháng</div>
                    <div class="guarantee-item">1 đổi 1 trong 30 ngày nếu có lỗi phần cứng</div>
                    <div class="guarantee-item">Giao hàng và cài đặt miễn phí toàn quốc</div>
                    <div class="guarantee-item">Tặng kèm balo laptop và chuột quang chính hãng</div>
                </div>
            </div>

            <!-- Cột phải: Thông tin & Đặt hàng -->
            <div class="detail-info">
                <div class="brand-tag"><?php echo htmlspecialchars($sp['TenHang'] ?? 'Laptop'); ?></div>
                <h1 class="detail-title"><?php echo htmlspecialchars($sp['TenSanPham']); ?></h1>

                <div class="rating-summary">
                    <span class="stars">
                        <?php 
                        $fullStars = floor($diemTB);
                        for ($i = 1; $i <= 5; $i++) {
                            echo ($i <= $fullStars) ? '★' : '☆';
                        }
                        ?>
                    </span>
                    <span class="rating-score"><?php echo $diemTB; ?>/5</span>
                    <span class="review-count">(<?php echo $tongDG; ?> đánh giá từ khách hàng)</span>
                </div>

                <div class="detail-price-box">
                    <span class="price-current"><?php echo format_price($sp['Gia']); ?></span>
                    <?php if ($sp['SoLuong'] > 0): ?>
                        <span class="stock-badge in-stock">Còn <?php echo $sp['SoLuong']; ?> sản phẩm</span>
                    <?php else: ?>
                        <span class="stock-badge out-of-stock">Đã hết hàng</span>
                    <?php endif; ?>
                </div>

                <!-- Form mua hàng -->
                <?php if ($sp['SoLuong'] > 0): ?>
                    <form action="../xu-ly/xu-ly-them-gio-hang.php" method="POST" class="order-form">
                        <input type="hidden" name="id" value="<?php echo $sp['MaSanPham']; ?>">
                        
                        <div class="quantity-selector">
                            <label for="quantity">Số lượng:</label>
                            <div class="qty-control">
                                <button type="button" onclick="adjustQty(-1)">-</button>
                                <input type="number" id="quantity" name="so_luong" value="1" min="1" max="<?php echo $sp['SoLuong']; ?>">
                                <button type="button" onclick="adjustQty(1)">+</button>
                            </div>
                        </div>

                        <div class="order-actions">
                            <button type="submit" name="action" value="them" class="btn-detail-cart">
                                Thêm vào giỏ hàng
                            </button>
                            <button type="submit" name="action" value="muangay" class="btn-detail-buy">
                                Mua ngay
                            </button>
                        </div>
                    </form>
                <?php else: ?>
                    <div class="out-of-stock-alert">
                        <p>Thông báo: Sản phẩm hiện đang tạm hết hàng. Quý khách vui lòng liên hệ hotline để đặt hàng trước hoặc tham khảo các mẫu máy tương tự bên dưới.</p>
                    </div>
                <?php endif; ?>

                <!-- Tóm tắt cấu hình nổi bật -->
                <div class="quick-specs">
                    <h3>Cấu hình nổi bật</h3>
                    <ul>
                        <li><strong>CPU:</strong> <?php echo htmlspecialchars($sp['CPU']); ?></li>
                        <li><strong>RAM:</strong> <?php echo htmlspecialchars($sp['RAM']); ?> (Nâng cấp: <?php echo htmlspecialchars($sp['NangCapRAM']); ?>)</li>
                        <li><strong>Ổ cứng:</strong> <?php echo htmlspecialchars($sp['OCung']); ?></li>
                        <li><strong>Màn hình:</strong> <?php echo htmlspecialchars($sp['ManHinh']); ?></li>
                        <li><strong>Card đồ họa:</strong> <?php echo htmlspecialchars($sp['CardDoHoa']); ?></li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Bảng thông số kỹ thuật chi tiết & Mô tả -->
        <div class="detail-extra-section">
            <div class="extra-col">
                <h2 class="sub-heading">Thông số kỹ thuật chi tiết</h2>
                <table class="specs-table">
                    <tr>
                        <th>Hãng sản xuất</th>
                        <td><?php echo htmlspecialchars($sp['TenHang'] ?? 'N/A'); ?></td>
                    </tr>
                    <tr>
                        <th>Bộ vi xử lý (CPU)</th>
                        <td><?php echo htmlspecialchars($sp['CPU']); ?></td>
                    </tr>
                    <tr>
                        <th>Dung lượng RAM</th>
                        <td><?php echo htmlspecialchars($sp['RAM']); ?></td>
                    </tr>
                    <tr>
                        <th>Hỗ trợ nâng cấp RAM</th>
                        <td><?php echo htmlspecialchars($sp['NangCapRAM']); ?></td>
                    </tr>
                    <tr>
                        <th>Ổ cứng lưu trữ</th>
                        <td><?php echo htmlspecialchars($sp['OCung']); ?></td>
                    </tr>
                    <tr>
                        <th>Kích thước màn hình</th>
                        <td><?php echo htmlspecialchars($sp['ManHinh']); ?></td>
                    </tr>
                    <tr>
                        <th>Card đồ họa (VGA)</th>
                        <td><?php echo htmlspecialchars($sp['CardDoHoa']); ?></td>
                    </tr>
                    <tr>
                        <th>Tình trạng</th>
                        <td>Hàng mới 100% nguyên hộp phụ kiện</td>
                    </tr>
                </table>
            </div>

            <div class="extra-col">
                <h2 class="sub-heading">Mô tả sản phẩm</h2>
                <div class="product-description">
                    <?php if (!empty($sp['MoTa'])): ?>
                        <p><?php echo nl2br(htmlspecialchars($sp['MoTa'])); ?></p>
                    <?php else: ?>
                        <p>Sản phẩm <strong><?php echo htmlspecialchars($sp['TenSanPham']); ?></strong> mang lại hiệu năng ổn định, thiết kế sang trọng hiện đại và thời lượng pin ấn tượng, đáp ứng trọn vẹn nhu cầu học tập, làm việc và giải trí đa phương tiện của người dùng.</p>
                        <p>Trang bị cấu hình mạnh mẽ với vi xử lý <strong><?php echo htmlspecialchars($sp['CPU']); ?></strong> cùng bộ nhớ RAM <strong><?php echo htmlspecialchars($sp['RAM']); ?></strong>, đảm bảo khả năng xử lý mượt mà mọi tác vụ văn phòng và đa nhiệm cùng lúc.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Khu vực đánh giá & nhận xét -->
        <div class="review-section">
            <h2 class="sub-heading">Đánh giá và nhận xét từ khách hàng</h2>

            <div class="review-layout">
                <!-- Cột hiển thị đánh giá -->
                <div class="review-list">
                    <?php if (mysqli_num_rows($resReviews) > 0): ?>
                        <?php while ($rev = mysqli_fetch_assoc($resReviews)): ?>
                            <div class="review-item">
                                <div class="review-header">
                                    <span class="reviewer-name"><?php echo htmlspecialchars($rev['HoTen'] ?? 'Khách hàng'); ?></span>
                                    <span class="review-date"><?php echo date("d/m/Y H:i", strtotime($rev['NgayDanhGia'])); ?></span>
                                </div>
                                <div class="review-stars">
                                    <?php 
                                    for ($s = 1; $s <= 5; $s++) {
                                        echo ($s <= $rev['SoSao']) ? '★' : '☆';
                                    }
                                    ?>
                                </div>
                                <div class="review-content">
                                    <?php echo nl2br(htmlspecialchars($rev['NoiDung'])); ?>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p class="empty-reviews">Chưa có đánh giá nào cho sản phẩm này. Hãy là người đầu tiên để lại cảm nhận.</p>
                    <?php endif; ?>
                </div>

                <!-- Cột gửi đánh giá mới -->
                <div class="review-form-box">
                    <h3>Gửi đánh giá của bạn</h3>
                    <?php if (isset($_SESSION['MaTaiKhoan'])): ?>
                        <form action="../xu-ly/xu-ly-danh-gia.php" method="POST">
                            <input type="hidden" name="MaSanPham" value="<?php echo $sp['MaSanPham']; ?>">
                            
                            <label>Chọn mức độ hài lòng:</label>
                            <div class="star-rating-select">
                                <label><input type="radio" name="SoSao" value="5" checked> 5 sao (Rất hài lòng)</label>
                                <label><input type="radio" name="SoSao" value="4"> 4 sao (Hài lòng)</label>
                                <label><input type="radio" name="SoSao" value="3"> 3 sao (Bình thường)</label>
                                <label><input type="radio" name="SoSao" value="2"> 2 sao (Chưa hài lòng)</label>
                                <label><input type="radio" name="SoSao" value="1"> 1 sao (Không hài lòng)</label>
                            </div>

                            <label for="NoiDung">Nội dung đánh giá:</label>
                            <textarea id="NoiDung" name="NoiDung" rows="4" placeholder="Chia sẻ cảm nhận về thiết kế, hiệu năng, bàn phím, thời lượng pin..." required></textarea>

                            <button type="submit" class="btn-submit-review">Gửi đánh giá</button>
                        </form>
                    <?php else: ?>
                        <div class="login-prompt">
                            <p>Vui lòng đăng nhập để có thể để lại bình luận và đánh giá sản phẩm.</p>
                            <a href="../nguoi-dung/dang-nhap.php" class="btn-login-prompt">Đăng nhập tài khoản</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Sản phẩm tương tự -->
        <?php if (mysqli_num_rows($resLienQuan) > 0): ?>
            <div class="related-section">
                <h2 class="sub-heading">Sản phẩm cùng thương hiệu</h2>
                <div class="product-list">
                    <?php while ($lq = mysqli_fetch_assoc($resLienQuan)): 
                        $imgLq = !empty($lq['HinhAnh']) && file_exists(__DIR__ . "/../uploads/san-pham/" . $lq['HinhAnh'])
                            ? "../uploads/san-pham/" . htmlspecialchars($lq['HinhAnh'])
                            : "../uploads/san-pham/default.jpg";
                    ?>
                        <div class="product">
                            <div class="product-thumb">
                                <a href="chi-tiet.php?id=<?php echo $lq['MaSanPham']; ?>">
                                    <img src="<?php echo $imgLq; ?>" alt="<?php echo htmlspecialchars($lq['TenSanPham']); ?>">
                                </a>
                            </div>
                            <h3 class="product-title">
                                <a href="chi-tiet.php?id=<?php echo $lq['MaSanPham']; ?>">
                                    <?php echo htmlspecialchars($lq['TenSanPham']); ?>
                                </a>
                            </h3>
                            <div class="price-box">
                                <h4><?php echo format_price($lq['Gia']); ?></h4>
                            </div>
                            <div class="action">
                                <a href="chi-tiet.php?id=<?php echo $lq['MaSanPham']; ?>" class="btn-detail">Chi tiết</a>
                                <a href="../xu-ly/xu-ly-them-gio-hang.php?id=<?php echo $lq['MaSanPham']; ?>" class="btn-add-cart">Thêm vào giỏ</a>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
        <?php endif; ?>

    </main>

    <?php include("../includes/footer.php"); ?>

    <script src="../assets/js/gallery.js?v=<?php echo time(); ?>"></script>
    <script>
    function adjustQty(delta) {
        var input = document.getElementById('quantity');
        var max = parseInt(input.getAttribute('max')) || 99;
        var val = parseInt(input.value) || 1;
        val += delta;
        if (val < 1) val = 1;
        if (val > max) val = max;
        input.value = val;
    }
    </script>

</body>
</html>
