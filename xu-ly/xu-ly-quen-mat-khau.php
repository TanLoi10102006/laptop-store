<?php
include("../config/ket-noi.php");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['btnDoiMatKhau'])) {

    $Email = clean_input($conn, $_POST['Email'] ?? '');
    $MatKhauMoi = $_POST['MatKhauMoi'] ?? '';
    $NhapLaiMatKhau = $_POST['NhapLaiMatKhau'] ?? '';

    if (empty($Email) || empty($MatKhauMoi) || empty($NhapLaiMatKhau)) {
        echo "<script>alert('Vui lòng điền đầy đủ các thông tin!'); window.history.back();</script>";
        exit();
    }

    if (strlen($MatKhauMoi) < 6) {
        echo "<script>alert('Mật khẩu mới phải có ít nhất 6 ký tự!'); window.history.back();</script>";
        exit();
    }

    if ($MatKhauMoi !== $NhapLaiMatKhau) {
        echo "<script>alert('Mật khẩu nhập lại không khớp! Vui lòng kiểm tra lại.'); window.history.back();</script>";
        exit();
    }

    $sql = "SELECT * FROM TaiKhoan WHERE Email = '$Email'";
    $result = mysqli_query($conn, $sql);

    if (!$result || mysqli_num_rows($result) == 0) {
        echo "<script>alert('Email này chưa được đăng ký trong hệ thống!'); window.history.back();</script>";
        exit();
    }

    $MatKhauMaHoa = password_hash($MatKhauMoi, PASSWORD_DEFAULT);
    $sqlUpdate = "UPDATE TaiKhoan SET MatKhau = '$MatKhauMaHoa' WHERE Email = '$Email'";

    if (mysqli_query($conn, $sqlUpdate)) {
        echo "<script>alert('Đổi mật khẩu thành công! Hãy đăng nhập bằng mật khẩu mới.'); window.location='../nguoi-dung/dang-nhap.php';</script>";
        exit();
    } else {
        echo "<script>alert('Đã xảy ra lỗi hệ thống. Vui lòng thử lại sau!'); window.history.back();</script>";
        exit();
    }
} else {
    header("Location: ../nguoi-dung/quen-mat-khau.php");
    exit();
}
