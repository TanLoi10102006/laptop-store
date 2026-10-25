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
$currentPass = $_POST['MatKhauHienTai'] ?? '';
$newPass = $_POST['MatKhauMoi'] ?? '';
$confirmPass = $_POST['NhapLaiMatKhau'] ?? '';

if (empty($currentPass) || empty($newPass) || empty($confirmPass)) {
    echo "<script>alert('Vui lòng nhập đầy đủ các trường mật khẩu!'); window.history.back();</script>";
    exit();
}

if (strlen($newPass) < 6) {
    echo "<script>alert('Mật khẩu mới phải có ít nhất 6 ký tự!'); window.history.back();</script>";
    exit();
}

if ($newPass !== $confirmPass) {
    echo "<script>alert('Mật khẩu xác nhận không khớp!'); window.history.back();</script>";
    exit();
}

// Kiểm tra mật khẩu hiện tại trong CSDL
$sql = "SELECT MatKhau FROM TaiKhoan WHERE MaTaiKhoan = $maTK";
$res = mysqli_query($conn, $sql);
$user = mysqli_fetch_assoc($res);

$isMatch = false;
if (password_verify($currentPass, $user['MatKhau']) || $currentPass === $user['MatKhau']) {
    $isMatch = true;
}

if (!$isMatch) {
    echo "<script>alert('Mật khẩu hiện tại không chính xác!'); window.history.back();</script>";
    exit();
}

// Cập nhật mật khẩu mới đã mã hóa
$hashedNew = password_hash($newPass, PASSWORD_DEFAULT);
$sqlUp = "UPDATE TaiKhoan SET MatKhau = '$hashedNew' WHERE MaTaiKhoan = $maTK";

if (mysqli_query($conn, $sqlUp)) {
    echo "<script>alert('Đổi mật khẩu thành công!'); window.location='../nguoi-dung/ho-so.php';</script>";
    exit();
} else {
    echo "<script>alert('Đổi mật khẩu thất bại. Vui lòng thử lại!'); window.history.back();</script>";
    exit();
}
