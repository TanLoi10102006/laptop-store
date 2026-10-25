<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$baseUrl = defined('BASE_URL') ? BASE_URL : '/web-Ban-LapTop/';
?>

<header class="admin-header">
    <div class="header-left">
        <h2> ADMIN</h2>
    </div>

    <div class="header-center">
        <form action="<?php echo $baseUrl; ?>admin/quan-ly-san-pham-admin/danh-sach.php" method="GET">
            <input type="text" name="keyword" placeholder="Tìm kiếm laptop..."
                value="<?php echo isset($_GET['keyword']) ? htmlspecialchars($_GET['keyword']) : ''; ?>">
            <button type="submit">Tìm kiếm</button>
        </form>
    </div>

    <div class="header-right">
        <span class="admin-name">
            Xin chào,
            <b><?php echo isset($_SESSION['HoTen']) ? htmlspecialchars($_SESSION['HoTen']) : 'Administrator'; ?></b>
        </span>

        <a href="<?php echo $baseUrl; ?>index.php" target="_blank" class="btn-home">
            Xem Website
        </a>
        <a href="<?php echo $baseUrl; ?>admin/xu-ly-admin/xu-ly-dang-xuat.php" class="btn-logout">
            Đăng xuất
        </a>
    </div>
</header>