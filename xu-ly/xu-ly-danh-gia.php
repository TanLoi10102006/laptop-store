<?php
include("../config/ket-noi.php");

if (!isset($_SESSION['MaTaiKhoan'])) {
    echo "<script>alert('Vui lòng đăng nhập để gửi đánh giá!'); window.location='../nguoi-dung/dang-nhap.php';</script>";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../index.php");
    exit();
}

$maTK = (int)$_SESSION['MaTaiKhoan'];
$maSP = isset($_POST['MaSanPham']) ? (int)$_POST['MaSanPham'] : 0;
$soSao = isset($_POST['SoSao']) ? (int)$_POST['SoSao'] : 5;
$noiDung = clean_input($conn, $_POST['NoiDung'] ?? '');

if ($soSao < 1) $soSao = 1;
if ($soSao > 5) $soSao = 5;

if ($maSP <= 0 || empty($noiDung)) {
    echo "<script>alert('Vui lòng nhập nội dung đánh giá!'); window.history.back();</script>";
    exit();
}

$sql = "INSERT INTO danhgia (MaTaiKhoan, MaSanPham, SoSao, NoiDung) 
        VALUES ($maTK, $maSP, $soSao, '$noiDung')";

if (mysqli_query($conn, $sql)) {
    echo "<script>alert('Cảm ơn bạn đã gửi đánh giá!'); window.location='../pages/chi-tiet.php?id=$maSP';</script>";
    exit();
} else {
    echo "<script>alert('Gửi đánh giá thất bại. Vui lòng thử lại!'); window.history.back();</script>";
    exit();
}
