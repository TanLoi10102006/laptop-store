<?php
session_start();
include("../config/ket-noi.php");
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Đăng nhập</title>

    <link rel="stylesheet" href="../assets/css/dang-nhap.css">
</head>

<body>

    <div class="form-container">

        <h2>ĐĂNG NHẬP</h2>

        <form action="../xu-ly/xu-ly-dang-nhap.php" method="POST" autocomplete="off" novalidate>

            <input type="text" name="Email" placeholder="Nhập Email" autocomplete="off" required>

            <input type="password" name="MatKhau" placeholder="Nhập mật khẩu" autocomplete="new-password" required>

            <button type="submit">
                Đăng nhập
            </button>

        </form>

        <div class="link-box">
            <a href="quen-mat-khau.php">
                Quên mật khẩu?
            </a>
        </div>

        <div class="link-box">
            <a href="dang-ky.php">
                Đăng ký ngay
            </a>
        </div>

        <hr>

        <div class="admin-box">
            <a href="../admin/dang-nhap.php">
                Đăng nhập Admin
            </a>
        </div>

    </div>

</body>

</html>