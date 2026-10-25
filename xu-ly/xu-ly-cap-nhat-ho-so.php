<?php
include("../config/ket-noi.php");

if (!isset($_SESSION['MaTaiKhoan'])) {
    header("Location: ../nguoi-dung/dang-nhap.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../nguoi-dung/ho-so.php");
    exit();
}

$maTK = (int)$_SESSION['MaTaiKhoan'];
$hoTen = clean_input($conn, $_POST['HoTen'] ?? '');
$soDienThoai = clean_input($conn, $_POST['SoDienThoai'] ?? '');

if (empty($hoTen) || empty($soDienThoai)) {
    echo "<script>alert('Vui lòng nhập đầy đủ họ tên và số điện thoại!'); window.history.back();</script>";
    exit();
}

$sql = "UPDATE TaiKhoan SET HoTen = '$hoTen', SoDienThoai = '$soDienThoai' WHERE MaTaiKhoan = $maTK";

if (mysqli_query($conn, $sql)) {
    $_SESSION['HoTen'] = $hoTen;
    echo "<script>alert('Cập nhật thông tin thành công!'); window.location='../nguoi-dung/ho-so.php';</script>";
    exit();
} else {
    echo "<script>alert('Cập nhật thất bại. Vui lòng thử lại!'); window.history.back();</script>";
    exit();
}
