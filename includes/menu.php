<?php
$baseUrl = defined('BASE_URL') ? BASE_URL : '/web-Ban-LapTop/';

// Lấy danh sách hãng từ CSDL nếu có kết nối
$brands = [];
if (isset($conn) && $conn) {
    $resBrands = mysqli_query($conn, "SELECT * FROM HangLaptop ORDER BY MaHang ASC");
    if ($resBrands) {
        while ($b = mysqli_fetch_assoc($resBrands)) {
            $brands[] = $b;
        }
    }
}

// Nếu chưa có thì dùng danh sách mặc định
if (empty($brands)) {
    $brands = [
        ['MaHang' => 1, 'TenHang' => 'MacBook'],
        ['MaHang' => 2, 'TenHang' => 'Dell'],
        ['MaHang' => 3, 'TenHang' => 'HP'],
        ['MaHang' => 4, 'TenHang' => 'Asus'],
        ['MaHang' => 5, 'TenHang' => 'Acer'],
        ['MaHang' => 6, 'TenHang' => 'Lenovo'],
    ];
}

$currentScript = basename($_SERVER['PHP_SELF']);
$currentBrandId = isset($_GET['hang']) ? (int)$_GET['hang'] : 0;
?>

<nav>
    <ul>
        <li>
            <a href="<?php echo $baseUrl; ?>index.php" class="<?php echo ($currentScript == 'index.php') ? 'active' : ''; ?>">
                Trang chủ
            </a>
        </li>

        <?php foreach ($brands as $brand): 
            $tenKhongDau = strtolower(str_replace(' ', '', $brand['TenHang']));
            // Bản đồ tên trang cụ thể nếu có file riêng
            $pageFile = "thuong-hieu.php?hang=" . $brand['MaHang'];
            if (file_exists(__DIR__ . "/../pages/{$tenKhongDau}.php")) {
                $pageFile = "{$tenKhongDau}.php";
            }
            $isActive = ($currentBrandId === (int)$brand['MaHang'] || $currentScript === "{$tenKhongDau}.php");
        ?>
            <li>
                <a href="<?php echo $baseUrl; ?>pages/<?php echo $pageFile; ?>" class="<?php echo $isActive ? 'active' : ''; ?>">
                    <?php echo htmlspecialchars($brand['TenHang']); ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</nav>
