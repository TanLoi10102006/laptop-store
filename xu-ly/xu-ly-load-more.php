<?php
include("../config/ket-noi.php");

header('Content-Type: application/json; charset=utf-8');

$offset = isset($_GET['offset']) ? max(0, (int)$_GET['offset']) : 0;
$limit = isset($_GET['limit']) ? max(1, (int)$_GET['limit']) : 8;

// Các bộ lọc
$whereClauses = ["1=1"];

// Lọc theo thương hiệu (hãng)
if (isset($_GET['hang']) && (int)$_GET['hang'] > 0) {
    $maHang = (int)$_GET['hang'];
    $whereClauses[] = "sp.MaHang = $maHang";
}

// Lọc theo từ khóa tìm kiếm
if (isset($_GET['keyword']) && trim($_GET['keyword']) !== '') {
    $safeKeyword = clean_input($conn, $_GET['keyword']);
    $whereClauses[] = "(sp.TenSanPham LIKE '%$safeKeyword%' OR sp.CPU LIKE '%$safeKeyword%' OR sp.CardDoHoa LIKE '%$safeKeyword%' OR hl.TenHang LIKE '%$safeKeyword%')";
}

// Lọc theo tầm giá
if (isset($_GET['mucgia']) && !empty($_GET['mucgia'])) {
    if ($_GET['mucgia'] == 'duoi-15') {
        $whereClauses[] = "sp.Gia < 15000000";
    } elseif ($_GET['mucgia'] == '15-25') {
        $whereClauses[] = "sp.Gia >= 15000000 AND sp.Gia <= 25000000";
    } elseif ($_GET['mucgia'] == 'tren-25') {
        $whereClauses[] = "sp.Gia > 25000000";
    }
}

// Xử lý sắp xếp
$order = "ORDER BY sp.MaSanPham DESC";
if (isset($_GET['sapxep'])) {
    if ($_GET['sapxep'] == 'gia-tang') {
        $order = "ORDER BY sp.Gia ASC";
    } elseif ($_GET['sapxep'] == 'gia-giam') {
        $order = "ORDER BY sp.Gia DESC";
    }
}

$whereSql = implode(" AND ", $whereClauses);

// Tính tổng số sản phẩm thỏa điều kiện
$countSql = "SELECT COUNT(*) AS total 
             FROM SanPham sp 
             LEFT JOIN HangLaptop hl ON sp.MaHang = hl.MaHang 
             WHERE $whereSql";
$countRes = mysqli_query($conn, $countSql);
$totalRows = 0;
if ($countRes && $rowC = mysqli_fetch_assoc($countRes)) {
    $totalRows = (int)$rowC['total'];
}

// Lấy sản phẩm theo offset và limit
$sql = "SELECT sp.*, hl.TenHang 
        FROM SanPham sp 
        LEFT JOIN HangLaptop hl ON sp.MaHang = hl.MaHang 
        WHERE $whereSql 
        $order 
        LIMIT $limit OFFSET $offset";
$result = mysqli_query($conn, $sql);

if (!$result) {
    echo json_encode([
        'success' => false,
        'message' => 'Lỗi truy vấn cơ sở dữ liệu: ' . mysqli_error($conn)
    ]);
    exit();
}

$baseUrl = defined('BASE_URL') ? BASE_URL : '/web-Ban-LapTop/';
$html = '';
$countFetched = 0;

while ($row = mysqli_fetch_assoc($result)) {
    $countFetched++;
    $imgFile = !empty($row['HinhAnh']) ? $row['HinhAnh'] : 'default.jpg';
    $imgSrc = $baseUrl . 'uploads/san-pham/' . htmlspecialchars($imgFile);

    $badgeHtml = '';
    if ($row['SoLuong'] <= 0) {
        $badgeHtml = '<span class="badge out-of-stock">Tạm hết hàng</span>';
    } elseif ($row['SoLuong'] < 5) {
        $badgeHtml = '<span class="badge low-stock">Còn ' . (int)$row['SoLuong'] . ' máy</span>';
    } else {
        $badgeHtml = '<span class="badge in-stock">Còn hàng</span>';
    }

    $actionBtnHtml = '';
    if ($row['SoLuong'] > 0) {
        $actionBtnHtml = '<a href="' . $baseUrl . 'xu-ly/xu-ly-them-gio-hang.php?id=' . (int)$row['MaSanPham'] . '" class="btn-add-cart">Thêm vào giỏ</a>';
    } else {
        $actionBtnHtml = '<button class="btn-disabled" disabled>Hết hàng</button>';
    }

    $tenSanPham = htmlspecialchars($row['TenSanPham']);
    $tenHang = htmlspecialchars($row['TenHang'] ?? 'Laptop');
    $cpu = htmlspecialchars($row['CPU']);
    $ram = htmlspecialchars($row['RAM']);
    $oCung = htmlspecialchars($row['OCung']);
    $manHinh = htmlspecialchars($row['ManHinh']);
    $giaFmt = format_price($row['Gia']);
    $detailUrl = $baseUrl . 'pages/chi-tiet.php?id=' . (int)$row['MaSanPham'];

    $html .= '
    <div class="product" data-product-id="' . (int)$row['MaSanPham'] . '">
        <div class="product-thumb">
            <a href="' . $detailUrl . '">
                <img src="' . $imgSrc . '" alt="' . $tenSanPham . '" loading="lazy">
            </a>
            ' . $badgeHtml . '
        </div>
        <div class="product-brand">' . $tenHang . '</div>
        <h3 class="product-title">
            <a href="' . $detailUrl . '">' . $tenSanPham . '</a>
        </h3>
        <div class="product-specs">
            <span><strong>CPU:</strong> ' . $cpu . '</span>
            <span><strong>RAM:</strong> ' . $ram . '</span>
            <span><strong>Ổ cứng:</strong> ' . $oCung . '</span>
            <span><strong>Màn hình:</strong> ' . $manHinh . '</span>
        </div>
        <div class="price-box">
            <h4>' . $giaFmt . '</h4>
        </div>
        <div class="action">
            <a href="' . $detailUrl . '" class="btn-detail">Chi tiết</a>
            ' . $actionBtnHtml . '
        </div>
    </div>';
}

$nextOffset = $offset + $countFetched;
$hasMore = $nextOffset < $totalRows;
$remaining = max(0, $totalRows - $nextOffset);

echo json_encode([
    'success' => true,
    'html' => $html,
    'count' => $countFetched,
    'total' => $totalRows,
    'nextOffset' => $nextOffset,
    'hasMore' => $hasMore,
    'remaining' => $remaining
]);
