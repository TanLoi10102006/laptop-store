<?php
include("../config/ket-noi.php");

$HoTen = clean_input($conn, $_POST['HoTen'] ?? '');
$Email = clean_input($conn, $_POST['Email'] ?? '');
$SoDienThoai = clean_input($conn, $_POST['SoDienThoai'] ?? '');
$MatKhau = $_POST['MatKhau'] ?? '';
$NhapLaiMatKhau = $_POST['NhapLaiMatKhau'] ?? '';

/* Kiểm tra rỗng */
if (empty($HoTen) || empty($Email) || empty($SoDienThoai) || empty($MatKhau) || empty($NhapLaiMatKhau)) {
    echo "<script>alert('Vui lòng nhập đầy đủ thông tin!'); window.history.back();</script>";
    exit();
}

/* Kiểm tra email hợp lệ */
if (!filter_var($Email, FILTER_VALIDATE_EMAIL)) {
    echo "<script>alert('Email không hợp lệ!'); window.history.back();</script>";
    exit();
}

/* Kiểm tra độ dài mật khẩu */
if (strlen($MatKhau) < 6) {
    echo "<script>alert('Mật khẩu phải có ít nhất 6 ký tự!'); window.history.back();</script>";
    exit();
}

/* Kiểm tra mật khẩu nhập lại */
if ($MatKhau !== $NhapLaiMatKhau) {
    echo "<script>alert('Mật khẩu nhập lại không khớp!'); window.history.back();</script>";
    exit();
}

/* Kiểm tra email tồn tại */
$sql = "SELECT * FROM TaiKhoan WHERE Email='$Email'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    echo "<script>alert('Email này đã được đăng ký trong hệ thống!'); window.history.back();</script>";
    exit();
}

/* Mã hóa mật khẩu */
$MatKhauHash = password_hash($MatKhau, PASSWORD_DEFAULT);

/* Thêm tài khoản */
$sql = "INSERT INTO TaiKhoan (HoTen, Email, SoDienThoai, MatKhau, VaiTro) 
        VALUES ('$HoTen', '$Email', '$SoDienThoai', '$MatKhauHash', 'user')";

if (mysqli_query($conn, $sql)) {
    echo "<script>alert('Đăng ký tài khoản thành công! Vui lòng đăng nhập.'); window.location='../nguoi-dung/dang-nhap.php';</script>";
    exit();
} else {
    echo "<script>alert('Đăng ký thất bại. Vui lòng thử lại!'); window.history.back();</script>";
    exit();
}
