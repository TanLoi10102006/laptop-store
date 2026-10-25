<?php
include("../config/ket-noi.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $Email = clean_input($conn, $_POST['Email'] ?? '');
    $MatKhau = $_POST['MatKhau'] ?? '';

    if (empty($Email) || empty($MatKhau)) {
        echo "<script>alert('Vui lòng nhập email và mật khẩu!'); window.history.back();</script>";
        exit();
    }

    $sql = "SELECT * FROM TaiKhoan WHERE Email = '$Email' AND VaiTro = 'admin'";
    $result = mysqli_query($conn, $sql);

    if (!$result || mysqli_num_rows($result) == 0) {
        echo "<script>alert('Tài khoản quản trị không tồn tại hoặc không có quyền truy cập!'); window.location='../admin/dang-nhap.php';</script>";
        exit();
    }

    $admin = mysqli_fetch_assoc($result);

    // Kiểm tra mật khẩu (hỗ trợ hash và plain text)
    $passwordValid = false;
    if (password_verify($MatKhau, $admin['MatKhau'])) {
        $passwordValid = true;
    } elseif ($MatKhau === $admin['MatKhau']) {
        $passwordValid = true;
    }

    if ($passwordValid) {
        $_SESSION['MaTaiKhoan'] = (int)$admin['MaTaiKhoan'];
        $_SESSION['HoTen'] = $admin['HoTen'];
        $_SESSION['Email'] = $admin['Email'];
        $_SESSION['VaiTro'] = $admin['VaiTro'];

        header("Location: ../admin/dashboard.php");
        exit();
    } else {
        echo "<script>alert('Mật khẩu quản trị không chính xác!'); window.location='../admin/dang-nhap.php';</script>";
        exit();
    }
} else {
    header("Location: ../admin/dang-nhap.php");
    exit();
}
