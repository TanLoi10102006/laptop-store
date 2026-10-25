<?php
include("../../config/ket-noi.php");

if (!isset($_SESSION['MaTaiKhoan']) || $_SESSION['VaiTro'] !== 'admin') {
    header("Location: ../../nguoi-dung/dang-nhap.php");
    exit();
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    header("Location: danh-sach.php");
    exit();
}

$sql = "SELECT * FROM HangLaptop WHERE MaHang = $id";
$res = mysqli_query($conn, $sql);
if (!$res || mysqli_num_rows($res) == 0) {
    header("Location: danh-sach.php");
    exit();
}
$hang = mysqli_fetch_assoc($res);
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Sửa thương hiệu - Admin</title>
    <link rel="stylesheet" href="../assets-admin/css-admin/them-san-pham-admin.css?v=<?=time()?>">
</head>

<body>

    <div class="overlay">
        <div class="popup" style="max-width: 500px;">
            <h2>SỬA THƯƠNG HIỆU</h2>

            <form action="../xu-ly-admin/xu-ly-sua-thuong-hieu.php" method="POST">
                <input type="hidden" name="MaHang" value="<?php echo $hang['MaHang']; ?>">

                <div class="row">
                    <div class="col" style="width: 100%;">
                        <label for="TenHang">Tên thương hiệu:</label>
                        <input type="text" id="TenHang" name="TenHang" value="<?php echo htmlspecialchars($hang['TenHang']); ?>" required>
                    </div>
                </div>

                <div class="button" style="margin-top: 25px;">
                    <a href="danh-sach.php" class="btn-cancel">HỦY</a>
                    <button type="submit" class="btn-save">CẬP NHẬT</button>
                </div>
            </form>
        </div>
    </div>

</body>

</html>
