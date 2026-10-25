<?php
session_start();
include("../config/ket-noi.php");
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Đăng ký tài khoản</title>
    <link rel="stylesheet" href="../assets/css/dang-ky.css">
</head>

<body>

    <div class="form-container">

        <h2>ĐĂNG KÝ TÀI KHOẢN</h2>

        <form action="../xu-ly/xu-ly-dang-ky.php" method="POST" autocomplete="off" novalidate>

            <input type="text" name="fake_email_remembered" style="display:none;" autocomplete="false" />
            <input type="text" name="HoTen" placeholder="Họ và tên" required autocomplete="off">

            <input type="email" name="Email" placeholder="Email" required autocomplete="nope">

            <input type="tel" name="SoDienThoai" placeholder="Số điện thoại" required autocomplete="off">

            <input type="password" name="MatKhau" placeholder="Mật khẩu" required autocomplete="new-password">

            <input type="password" name="NhapLaiMatKhau" placeholder="Nhập lại mật khẩu" required
                autocomplete="new-password">

            <button type="submit">
                Đăng ký
            </button>
        </form>

        <div class="link-box">
            <a href="dang-nhap.php">
                Đã có tài khoản? Đăng nhập
            </a>
        </div>

    </div>

</body>

</html>