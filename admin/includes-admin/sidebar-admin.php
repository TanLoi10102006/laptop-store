<?php
$baseUrl = defined('BASE_URL') ? BASE_URL : '/web-Ban-LapTop/';
$currentUri = $_SERVER['REQUEST_URI'];
?>

<div class="sidebar-admin">
    <div class="logo-admin">
        <h2>ADMIN PANEL</h2>
    </div>

    <ul>
        <li>
            <a href="<?php echo $baseUrl; ?>admin/dashboard.php"
                class="<?php echo (strpos($currentUri, 'dashboard.php') !== false) ? 'active' : ''; ?>">
                Bảng điều khiển
            </a>
        </li>

        <li>
            <a href="<?php echo $baseUrl; ?>admin/quan-ly-san-pham-admin/danh-sach.php"
                class="<?php echo (strpos($currentUri, 'quan-ly-san-pham-admin') !== false) ? 'active' : ''; ?>">
                Quản lý sản phẩm
            </a>
        </li>

        <li>
            <a href="<?php echo $baseUrl; ?>admin/quan-ly-thuong-hieu-admin/danh-sach.php"
                class="<?php echo (strpos($currentUri, 'quan-ly-thuong-hieu-admin') !== false) ? 'active' : ''; ?>">
                Quản lý thương hiệu
            </a>
        </li>

        <li>
            <a href="<?php echo $baseUrl; ?>admin/quan-ly-don-hang-admin/danh-sach.php"
                class="<?php echo (strpos($currentUri, 'quan-ly-don-hang-admin') !== false) ? 'active' : ''; ?>">
                Quản lý đơn hàng
            </a>
        </li>

        <li>
            <a href="<?php echo $baseUrl; ?>admin/quan-ly-khach-hang-admin/danh-sach.php"
                class="<?php echo (strpos($currentUri, 'quan-ly-khach-hang-admin') !== false) ? 'active' : ''; ?>">
                Quản lý khách hàng
            </a>
        </li>

        <li>
            <a href="<?php echo $baseUrl; ?>admin/quan-ly-binh-luan-admin/danh-sach.php"
                class="<?php echo (strpos($currentUri, 'quan-ly-binh-luan-admin') !== false) ? 'active' : ''; ?>">
                Quản lý bình luận
            </a>
        </li>

        <li>
            <a href="<?php echo $baseUrl; ?>admin/quan-ly-tai-khoan-admin/danh-sach.php"
                class="<?php echo (strpos($currentUri, 'quan-ly-tai-khoan-admin') !== false) ? 'active' : ''; ?>">
                Quản lý tài khoản Admin
            </a>
        </li>
    </ul>
</div>