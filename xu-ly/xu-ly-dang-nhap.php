<?php
include("../config/ket-noi.php");

// Kiểm tra nếu dữ liệu được submit qua POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $Email = clean_input($conn, $_POST['Email'] ?? '');
    $MatKhau = $_POST['MatKhau'] ?? '';

    if (empty($Email) || empty($MatKhau)) {
        echo "<script>alert('Vui lòng nhập email và mật khẩu!'); window.history.back();</script>";
        exit();
    }

    $sql = "SELECT * FROM TaiKhoan WHERE Email='$Email'";
    $result = mysqli_query($conn, $sql);

    // Không tìm thấy Email trong hệ thống
    if (!$result || mysqli_num_rows($result) == 0) {
        echo "<script>alert('Email hoặc mật khẩu không đúng! Vui lòng kiểm tra lại.'); window.history.back();</script>";
        exit();
    }

    $user = mysqli_fetch_assoc($result);

    // Kiểm tra mật khẩu (hỗ trợ password_verify và plain-text fallback)
    $passwordValid = false;
    if (password_verify($MatKhau, $user['MatKhau'])) {
        $passwordValid = true;
    } elseif ($MatKhau === $user['MatKhau']) {
        $passwordValid = true;
        // Tự động nâng cấp lên mã hóa Bcrypt
        $rehash = password_hash($MatKhau, PASSWORD_DEFAULT);
        $uId = (int)$user['MaTaiKhoan'];
        mysqli_query($conn, "UPDATE TaiKhoan SET MatKhau = '$rehash' WHERE MaTaiKhoan = $uId");
    }

    if ($passwordValid) {
        // Đăng nhập thành công -> Lưu Session
        $_SESSION['MaTaiKhoan'] = (int)$user['MaTaiKhoan'];
        $_SESSION['HoTen'] = $user['HoTen'];
        $_SESSION['Email'] = $user['Email'];
        $_SESSION['VaiTro'] = $user['VaiTro'];

        // Đồng bộ giỏ hàng từ Session vào CSDL
        $maTK = (int)$user['MaTaiKhoan'];
        if (isset($_SESSION['gio_hang']) && !empty($_SESSION['gio_hang'])) {
            // Tìm hoặc tạo giỏ hàng của user
            $sqlGH = "SELECT MaGioHang FROM giohang WHERE MaTaiKhoan = $maTK LIMIT 1";
            $resGH = mysqli_query($conn, $sqlGH);
            if ($resGH && mysqli_num_rows($resGH) > 0) {
                $rowGH = mysqli_fetch_assoc($resGH);
                $maGH = (int)$rowGH['MaGioHang'];
            } else {
                mysqli_query($conn, "INSERT INTO giohang (MaTaiKhoan) VALUES ($maTK)");
                $maGH = mysqli_insert_id($conn);
            }

            foreach ($_SESSION['gio_hang'] as $item) {
                $pId = (int)$item['id'];
                $qty = (int)$item['so_luong'];

                $checkCT = mysqli_query($conn, "SELECT MaChiTiet, SoLuong FROM chitietgiohang WHERE MaGioHang = $maGH AND MaSanPham = $pId");
                if ($checkCT && mysqli_num_rows($checkCT) > 0) {
                    $rowCT = mysqli_fetch_assoc($checkCT);
                    $newQty = $rowCT['SoLuong'] + $qty;
                    $cId = (int)$rowCT['MaChiTiet'];
                    mysqli_query($conn, "UPDATE chitietgiohang SET SoLuong = $newQty WHERE MaChiTiet = $cId");
                } else {
                    mysqli_query($conn, "INSERT INTO chitietgiohang (MaGioHang, MaSanPham, SoLuong) VALUES ($maGH, $pId, $qty)");
                }
            }
            // Xóa session giỏ hàng sau khi đã đồng bộ
            $_SESSION['gio_hang'] = [];
        }

        // Chuyển hướng
        if ($user['VaiTro'] === 'admin') {
            header('Location: ../admin/dashboard.php');
        } else {
            header('Location: ../index.php');
        }
        exit();
    } else {
        echo "<script>alert('Email hoặc mật khẩu không đúng! Vui lòng kiểm tra lại.'); window.history.back();</script>";
        exit();
    }
} else {
    header('Location: ../nguoi-dung/dang-nhap.php');
    exit();
}
