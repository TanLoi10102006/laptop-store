<?php
include("../../config/ket-noi.php");

if (!isset($_SESSION['MaTaiKhoan']) || $_SESSION['VaiTro'] !== 'admin') {
    header("Location: ../../nguoi-dung/dang-nhap.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Thêm thương hiệu mới - Admin</title>
    <link rel="stylesheet" href="../assets-admin/css-admin/them-san-pham-admin.css?v=<?=time()?>">
</head>

<body>

    <div class="overlay">
        <div class="popup" style="max-width: 500px;">
            <h2>THÊM THƯƠNG HIỆU MỚI</h2>

            <form action="../xu-ly-admin/xu-ly-them-thuong-hieu.php" method="POST">
                <div class="row">
                    <div class="col" style="width: 100%;">
                        <label for="TenHang">Tên thương hiệu (VD: MSI, Gigabyte, LG):</label>
                        <input type="text" id="TenHang" name="TenHang" required placeholder="Nhập tên hãng...">
                    </div>
                </div>

                <div class="button" style="margin-top: 25px;">
                    <a href="danh-sach.php" class="btn-cancel">HỦY</a>
                    <button type="submit" class="btn-save">LƯU HÃNG</button>
                </div>
            </form>
        </div>
    </div>

</body>

</html>
