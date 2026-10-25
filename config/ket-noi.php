<?php
// Bắt đầu session an toàn nếu chưa có
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Đường dẫn gốc của website
if (!defined('BASE_URL')) {
    define('BASE_URL', '/web-Ban-LapTop/');
}

// Thông tin kết nối CSDL
$servername = "localhost";
$username   = "root";
$password   = "";
$database   = "webbanlaptop";

// Tạo kết nối
$conn = mysqli_connect($servername, $username, $password, $database);

// Kiểm tra kết nối
if (!$conn) {
    die("Kết nối CSDL thất bại: " . mysqli_connect_error());
}

// Thiết lập bảng mã UTF-8 đầy đủ cho tiếng Việt
mysqli_set_charset($conn, "utf8mb4");

// Helper: Làm sạch dữ liệu đầu vào chống SQL Injection
if (!function_exists('clean_input')) {
    function clean_input($conn, $data)
    {
        if ($data === null) return '';
        $data = trim((string)$data);
        return mysqli_real_escape_string($conn, $data);
    }
}

// Helper: Chống XSS khi hiển thị ra HTML
if (!function_exists('xss')) {
    function xss($data)
    {
        if ($data === null) return '';
        return htmlspecialchars((string)$data, ENT_QUOTES, 'UTF-8');
    }
}

// Helper: Định dạng tiền tệ VNĐ
if (!function_exists('format_price')) {
    function format_price($price)
    {
        return number_format((float)$price, 0, ',', '.') . ' VNĐ';
    }
}

// Helper: Đếm số lượng sản phẩm trong giỏ hàng
if (!function_exists('get_cart_count')) {
    function get_cart_count($conn)
    {
        $count = 0;
        if (isset($_SESSION['MaTaiKhoan'])) {
            $maTK = (int)$_SESSION['MaTaiKhoan'];
            // Lấy từ CSDL giohang & chitietgiohang
            $sql = "SELECT SUM(ctg.SoLuong) AS Tong
                    FROM giohang gh
                    JOIN chitietgiohang ctg ON gh.MaGioHang = ctg.MaGioHang
                    WHERE gh.MaTaiKhoan = $maTK";
            $res = mysqli_query($conn, $sql);
            if ($res && $row = mysqli_fetch_assoc($res)) {
                $count = (int)$row['Tong'];
            }
        } else {
            // Giỏ hàng lưu trong session cho khách chưa đăng nhập
            if (isset($_SESSION['gio_hang']) && is_array($_SESSION['gio_hang'])) {
                foreach ($_SESSION['gio_hang'] as $item) {
                    $count += (int)$item['so_luong'];
                }
            }
        }
        return $count;
    }
}
