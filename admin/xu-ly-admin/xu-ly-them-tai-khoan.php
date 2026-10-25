<?php
include("../../config/ket-noi.php");

if (!isset($_SESSION['MaTaiKhoan']) || $_SESSION['VaiTro'] !== 'admin') {
    header("Location: ../../nguoi-dung/dang-nhap.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../quan-ly-tai-khoan-admin/danh-sach.php");
    exit();
}

$hoTen = clean_input($conn, $_POST['HoTen'] ?? '');
$email = clean_input($conn, $_POST['Email'] ?? '');
$soDienThoai = clean_input($conn, $_POST['SoDienThoai'] ?? '');
$matKhau = $_POST['MatKhau'] ?? '';

if (empty($hoTen) || empty($email) || empty($matKhau)) {
    echo "<script>alert('Vui lòng nhập đầy đủ thông tin bắt buộc!'); window.history.back();</script>";
    exit();
}

if (strlen($matKhau) < 6) {
    echo "<script>alert('Mật khẩu phải từ 6 ký tự trở lên!'); window.history.back();</script>";
    exit();
}

// Kiểm tra email trùng
$checkEmail = mysqli_query($conn, "SELECT MaTaiKhoan FROM TaiKhoan WHERE Email = '$email'");
if ($checkEmail && mysqli_num_rows($checkEmail) > 0) {
    echo "<script>alert('Email này đã tồn tại trong hệ thống!'); window.history.back();</script>";
    exit();
}

$hashedPass = password_hash($matKhau, PASSWORD_DEFAULT);
$sql = "INSERT INTO TaiKhoan (HoTen, Email, SoDienThoai, MatKhau, VaiTro) 
        VALUES ('$hoTen', '$email', '$soDienThoai', '$hashedPass', 'admin')";

if (mysqli_query($conn, $sql)) {
    echo "<script>alert('Thêm tài khoản Admin thành công!'); window.location='../quan-ly-tai-khoan-admin/danh-sach.php';</script>";
    exit();
} else {
    echo "<script>alert('Lỗi tạo tài khoản: " . addslashes(mysqli_error($conn)) . "'); window.history.back();</script>";
    exit();
}
