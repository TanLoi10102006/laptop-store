<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Đăng nhập Admin</title>
    <link rel="stylesheet" href="../assets/css/admin-login.css">
</head>

<body>

    <div class="login-box">

        <h2>ADMIN PANEL</h2>

        <form action="../xu-ly/xu-ly-admin-dang-nhap.php" method="POST">

            <input type="email" name="Email" placeholder="Email Admin" required>

            <input type="password" name="MatKhau" placeholder="Mật khẩu" required>

            <button type="submit">
                Đăng nhập Admin
            </button>

        </form>

        <a href="../index.php">
            ← Quay về trang chủ
        </a>

    </div>

</body>

</html>