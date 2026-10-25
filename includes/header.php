<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$cartCount = function_exists('get_cart_count') && isset($conn) ? get_cart_count($conn) : 0;
$baseUrl = defined('BASE_URL') ? BASE_URL : '/web-Ban-LapTop/';
?>

<header>
    <div class="container">

        <div class="logo">
            <a href="<?php echo $baseUrl; ?>index.php" style="text-decoration:none;">
                <h1>SHOP LAPTOP</h1>
            </a>
        </div>

        <div class="search">
            <form action="<?php echo $baseUrl; ?>pages/tim-kiem.php" method="GET">
                <input type="text" name="keyword" placeholder="Tìm tên laptop, hãng, cấu hình..." value="<?php echo isset($_GET['keyword']) ? htmlspecialchars($_GET['keyword']) : ''; ?>" required>
                <button type="submit">Tìm kiếm</button>
            </form>
        </div>

        <div class="account">
            <a href="<?php echo $baseUrl; ?>nguoi-dung/gio-hang.php" class="cart-btn" title="Xem giỏ hàng">
                Giỏ hàng
                <?php if ($cartCount > 0): ?>
                    <span class="cart-badge"><?php echo $cartCount; ?></span>
                <?php endif; ?>
            </a>

            <?php if (isset($_SESSION['MaTaiKhoan'])) { ?>

                <div class="user-dropdown">
                    <span class="user-name">
                        Xin chào, <strong><?php echo htmlspecialchars($_SESSION['HoTen']); ?></strong>
                    </span>

                    <div class="account-links">
                        <?php if (isset($_SESSION['VaiTro']) && $_SESSION['VaiTro'] === 'admin'): ?>
                            <a href="<?php echo $baseUrl; ?>admin/dashboard.php" class="btn-admin-link">Quản trị</a>
                        <?php endif; ?>
                        <a href="<?php echo $baseUrl; ?>nguoi-dung/don-hang-cua-toi.php">Đơn hàng</a>
                        <a href="<?php echo $baseUrl; ?>nguoi-dung/ho-so.php">Hồ sơ</a>
                        <a href="<?php echo $baseUrl; ?>xu-ly/xu-ly-dang-xuat.php" class="btn-logout">Đăng xuất</a>
                    </div>
                </div>

            <?php } else { ?>

                <div class="account-links">
                    <a href="<?php echo $baseUrl; ?>nguoi-dung/dang-nhap.php">Đăng nhập</a>
                    <a href="<?php echo $baseUrl; ?>nguoi-dung/dang-ky.php">Đăng ký</a>
                </div>

            <?php } ?>
        </div>

    </div>
</header>
