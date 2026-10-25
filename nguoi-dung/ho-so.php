<?php
include("../config/ket-noi.php");

// Kiểm tra đăng nhập
if (!isset($_SESSION['MaTaiKhoan'])) {
    header("Location: dang-nhap.php");
    exit();
}

$maTK = (int)$_SESSION['MaTaiKhoan'];
$sql = "SELECT * FROM TaiKhoan WHERE MaTaiKhoan = $maTK";
$res = mysqli_query($conn, $sql);
$user = mysqli_fetch_assoc($res);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hồ sơ cá nhân - SHOP LAPTOP</title>

    <link rel="stylesheet" href="../assets/css/header.css">
    <link rel="stylesheet" href="../assets/css/menu.css">
    <link rel="stylesheet" href="../assets/css/index.css">
    <link rel="stylesheet" href="../assets/css/footer.css">
    <link rel="stylesheet" href="../assets/css/gio-hang.css">
</head>
<body>

    <?php include("../includes/header.php"); ?>
    <?php include("../includes/menu.php"); ?>

    <main class="cart-page-container">
        <div class="cart-header">
            <h1>HỒ SƠ TÀI KHOẢN</h1>
            <p>Quản lý thông tin cá nhân và bảo mật tài khoản</p>
        </div>

        <div class="checkout-grid">
            <!-- Form cập nhật thông tin -->
            <div class="checkout-form-box">
                <h2>1. Thông tin tài khoản</h2>
                <form action="../xu-ly/xu-ly-cap-nhat-ho-so.php" method="POST">
                    <div class="form-group">
                        <label>Địa chỉ Email (Tên đăng nhập):</label>
                        <input type="text" value="<?php echo htmlspecialchars($user['Email']); ?>" disabled style="background:#222; color:#888;">
                    </div>

                    <div class="form-group">
                        <label for="HoTen">Họ và tên:</label>
                        <input type="text" id="HoTen" name="HoTen" value="<?php echo htmlspecialchars($user['HoTen']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="SoDienThoai">Số điện thoại:</label>
                        <input type="tel" id="SoDienThoai" name="SoDienThoai" value="<?php echo htmlspecialchars($user['SoDienThoai'] ?? ''); ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Ngày tham gia:</label>
                        <input type="text" value="<?php echo date("d/m/Y H:i", strtotime($user['NgayTao'])); ?>" disabled style="background:#222; color:#888;">
                    </div>

                    <button type="submit" class="btn-submit-order" style="margin-top: 15px;">
                        Cập nhật thông tin
                    </button>
                </form>
            </div>

            <!-- Form đổi mật khẩu -->
            <div class="checkout-form-box">
                <h2>2. Đổi mật khẩu</h2>
                <form action="../xu-ly/xu-ly-doi-mat-khau.php" method="POST">
                    <div class="form-group">
                        <label for="MatKhauHienTai">Mật khẩu hiện tại:</label>
                        <input type="password" id="MatKhauHienTai" name="MatKhauHienTai" placeholder="Nhập mật khẩu hiện tại" required>
                    </div>

                    <div class="form-group">
                        <label for="MatKhauMoi">Mật khẩu mới:</label>
                        <input type="password" id="MatKhauMoi" name="MatKhauMoi" placeholder="Tối thiểu 6 ký tự" required>
                    </div>

                    <div class="form-group">
                        <label for="NhapLaiMatKhau">Xác nhận mật khẩu mới:</label>
                        <input type="password" id="NhapLaiMatKhau" name="NhapLaiMatKhau" placeholder="Nhập lại mật khẩu mới" required>
                    </div>

                    <button type="submit" class="btn-submit-order" style="margin-top: 15px; background: #e67e22;">
                        Đổi mật khẩu
                    </button>
                </form>
            </div>
        </div>
    </main>

    <?php include("../includes/footer.php"); ?>

</body>
</html>
