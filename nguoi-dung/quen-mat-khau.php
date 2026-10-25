<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Quên mật khẩu</title>
    <link rel="stylesheet" href="../assets/css/dang-nhap.css">
</head>

<body>

    <div class="form-container">

        <h2>QUÊN MẬT KHẨU</h2>

        <form action="../xu-ly/xu-ly-quen-mat-khau.php" method="POST" autocomplete="off">

            <input type="email" name="Email" placeholder="Nhập Email đã đăng ký" required autocomplete="nope">

            <input type="password" name="MatKhauMoi" placeholder="Nhập mật khẩu mới" required
                autocomplete="new-password">

            <input type="password" name="NhapLaiMatKhau" placeholder="Nhập lại mật khẩu mới" required
                autocomplete="new-password">

            <button type="submit" name="btnDoiMatKhau">
                Đổi mật khẩu
            </button>

        </form>

        <div class="link-box">
            <a href="dang-nhap.php">
                Quay lại đăng nhập
            </a>
        </div>

    </div>

</body>

</html>