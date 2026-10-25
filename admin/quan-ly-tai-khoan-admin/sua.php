<?php
include("../../config/ket-noi.php");

if (!isset($_SESSION['MaTaiKhoan']) || $_SESSION['VaiTro'] !== 'admin') {
    header("Location: ../../nguoi-dung/dang-nhap.php");
    exit();
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    header("Location: danh-sach.php");
    exit();
}

$sql = "SELECT * FROM TaiKhoan WHERE MaTaiKhoan = $id AND VaiTro = 'admin'";
$res = mysqli_query($conn, $sql);
if (!$res || mysqli_num_rows($res) == 0) {
    echo "<script>alert('Không tìm thấy tài khoản quản trị!'); window.location='danh-sach.php';</script>";
    exit();
}
$admin = mysqli_fetch_assoc($res);
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Sửa tài khoản Admin - Admin</title>
    <link rel="stylesheet" href="../assets-admin/css-admin/them-san-pham-admin.css?v=<?=time()?>">
</head>

<body>

    <div class="overlay">
        <div class="popup" style="max-width: 550px;">
            <h2>SỬA TÀI KHOẢN ADMIN</h2>

            <form action="../xu-ly-admin/xu-ly-sua-tai-khoan.php" method="POST">
                <input type="hidden" name="MaTaiKhoan" value="<?php echo $admin['MaTaiKhoan']; ?>">

                <div class="row">
                    <div class="col" style="width: 100%;">
                        <label for="HoTen">Họ và tên Admin:</label>
                        <input type="text" id="HoTen" name="HoTen" value="<?php echo htmlspecialchars($admin['HoTen']); ?>" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <label for="Email">Email đăng nhập:</label>
                        <input type="email" id="Email" name="Email" value="<?php echo htmlspecialchars($admin['Email']); ?>" required>
                    </div>
                    <div class="col">
                        <label for="SoDienThoai">Số điện thoại:</label>
                        <input type="tel" id="SoDienThoai" name="SoDienThoai" value="<?php echo htmlspecialchars($admin['SoDienThoai'] ?? ''); ?>" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col" style="width: 100%;">
                        <label for="MatKhauMoi">Mật khẩu mới (để trống nếu không muốn đổi):</label>
                        <input type="password" id="MatKhauMoi" name="MatKhauMoi" placeholder="Nhập mật khẩu mới nếu muốn thay đổi">
                    </div>
                </div>

                <div class="button" style="margin-top: 25px;">
                    <a href="danh-sach.php" class="btn-cancel">HỦY</a>
                    <button type="submit" class="btn-save">LƯU THAY ĐỔI</button>
                </div>
            </form>
        </div>
    </div>

</body>

</html>
