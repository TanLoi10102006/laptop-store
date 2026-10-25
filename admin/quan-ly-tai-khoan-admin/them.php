<?php
include("../../config/ket-noi.php");

if (!isset($_SESSION['MaTaiKhoan']) || $_SESSION['VaiTro'] !== 'admin') {
    header("Location: ../../nguoi-dung/dang-nhap.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Thêm quản trị viên mới - Admin</title>
    <link rel="stylesheet" href="../assets-admin/css-admin/them-san-pham-admin.css?v=<?=time()?>">
</head>

<body>

    <div class="overlay">
        <div class="popup" style="max-width: 550px;">
            <h2>THÊM TÀI KHOẢN ADMIN MỚI</h2>

            <form action="../xu-ly-admin/xu-ly-them-tai-khoan.php" method="POST">
                <div class="row">
                    <div class="col" style="width: 100%;">
                        <label for="HoTen">Họ và tên Admin:</label>
                        <input type="text" id="HoTen" name="HoTen" placeholder="Ví dụ: Nguyễn Quản Trị" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <label for="Email">Email đăng nhập:</label>
                        <input type="email" id="Email" name="Email" placeholder="admin@example.com" required>
                    </div>
                    <div class="col">
                        <label for="SoDienThoai">Số điện thoại:</label>
                        <input type="tel" id="SoDienThoai" name="SoDienThoai" placeholder="0987654321" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col" style="width: 100%;">
                        <label for="MatKhau">Mật khẩu:</label>
                        <input type="password" id="MatKhau" name="MatKhau" placeholder="Tối thiểu 6 ký tự" required>
                    </div>
                </div>

                <div class="button" style="margin-top: 25px;">
                    <a href="danh-sach.php" class="btn-cancel">HỦY</a>
                    <button type="submit" class="btn-save">TẠO TÀI KHOẢN</button>
                </div>
            </form>
        </div>
    </div>

</body>

</html>
