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

$id = isset($_POST['MaTaiKhoan']) ? (int)$_POST['MaTaiKhoan'] : 0;
$hoTen = clean_input($conn, $_POST['HoTen'] ?? '');
$email = clean_input($conn, $_POST['Email'] ?? '');
$soDienThoai = clean_input($conn, $_POST['SoDienThoai'] ?? '');
$matKhauMoi = $_POST['MatKhauMoi'] ?? '';

if ($id <= 0 || empty($hoTen) || empty($email)) {
    echo "<script>alert('Vui lòng điền đầy đủ họ tên và email!'); window.history.back();</script>";
    exit();
}

// Kiểm tra email không được trùng với tài khoản khác
$checkEmail = mysqli_query($conn, "SELECT MaTaiKhoan FROM TaiKhoan WHERE Email = '$email' AND MaTaiKhoan != $id");
if ($checkEmail && mysqli_num_rows($checkEmail) > 0) {
    echo "<script>alert('Email này đã được sử dụng bởi tài khoản khác!'); window.history.back();</script>";
    exit();
}

$updatePassSql = "";
if (!empty($matKhauMoi)) {
    if (strlen($matKhauMoi) < 6) {
        echo "<script>alert('Mật khẩu mới phải có ít nhất 6 ký tự!'); window.history.back();</script>";
        exit();
    }
    $hashed = password_hash($matKhauMoi, PASSWORD_DEFAULT);
    $updatePassSql = ", MatKhau = '$hashed'";
}

$sql = "UPDATE TaiKhoan SET HoTen = '$hoTen', Email = '$email', SoDienThoai = '$soDienThoai' $updatePassSql WHERE MaTaiKhoan = $id";

if (mysqli_query($conn, $sql)) {
    // Nếu cập nhật chính tài khoản đang đăng nhập, cập nhật lại session
    if ($id == $_SESSION['MaTaiKhoan']) {
        $_SESSION['HoTen'] = $hoTen;
        $_SESSION['Email'] = $email;
    }
    echo "<script>alert('Cập nhật tài khoản Admin thành công!'); window.location='../quan-ly-tai-khoan-admin/danh-sach.php';</script>";
    exit();
} else {
    echo "<script>alert('Lỗi cập nhật: " . addslashes(mysqli_error($conn)) . "'); window.history.back();</script>";
    exit();
}
