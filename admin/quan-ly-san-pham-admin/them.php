<?php
session_start();

include("../../config/ket-noi.php");

/* Kiểm tra đăng nhập */

if (!isset($_SESSION['MaTaiKhoan'])) {
    header("Location: ../../nguoi-dung/dang-nhap.php");
    exit();
}

/* Kiểm tra quyền */

if ($_SESSION['VaiTro'] != "admin") {
    header("Location: ../../index.php");
    exit();
}

/* Lấy danh sách hãng */

$sqlHang = "SELECT * FROM HangLaptop";
$resultHang = mysqli_query($conn, $sqlHang);

?>

<!DOCTYPE html>
<html lang="vi">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Thêm sản phẩm</title>
    <link rel="stylesheet" href="../assets-admin/css-admin/them-san-pham-admin.css?v=<?=time()?>">
</head>

<body>

    <div class="overlay">

        <div class="popup">

            <h2>THÊM SẢN PHẨM MỚI (ADD LAPTOP)</h2>

            <form action="../xu-ly-admin/xu-ly-them-san-pham.php" method="POST" enctype="multipart/form-data">

                <div class="row">

                    <div class="col">

                        <label>Tên Laptop</label>

                        <input type="text" name="TenSanPham" required>

                    </div>

                    <div class="col">

                        <label>Thương hiệu</label>

                        <select name="MaHang" required>

                            <?php while ($hang = mysqli_fetch_assoc($resultHang)) { ?>

                            <option value="<?php echo $hang['MaHang']; ?>">

                                <?php echo $hang['TenHang']; ?>

                            </option>

                            <?php } ?>

                        </select>

                    </div>

                </div>

                <div class="row">

                    <div class="col">

                        <label>CPU</label>

                        <input type="text" name="CPU" required>

                    </div>

                    <div class="col">

                        <label>RAM</label>

                        <input type="text" name="RAM" required>

                    </div>

                </div>

                <div class="row">

                    <div class="col">

                        <label>Nâng cấp RAM</label>

                        <select name="NangCapRAM">

                            <option value="Có">Có</option>

                            <option value="Không">Không</option>

                        </select>

                    </div>

                    <div class="col">

                        <label>Ổ cứng</label>

                        <input type="text" name="OCung" required>

                    </div>

                </div>

                <div class="row">

                    <div class="col">

                        <label>Màn hình</label>

                        <input type="text" name="ManHinh" required>

                    </div>

                    <div class="col">

                        <label>Card đồ họa</label>

                        <input type="text" name="CardDoHoa" required>

                    </div>

                </div>

                <div class="row">

                    <div class="col">

                        <label>Giá</label>

                        <input type="number" name="Gia" required>

                    </div>

                    <div class="col">

                        <label>Số lượng</label>

                        <input type="number" name="SoLuong" required>

                    </div>

                </div>

                <label>Mô tả</label>

                <textarea name="MoTa" rows="5"></textarea>

                <label>Hình ảnh đại diện chính (bắt buộc)</label>
                <input type="file" name="HinhAnh" accept="image/*" required>
                <span class="note-hint">Ảnh này dùng làm ảnh đại diện hiển thị ở danh sách sản phẩm.</span>

                <br><br>
                <label>Các hình ảnh chi tiết khác (tải lên nhiều ảnh cùng lúc)</label>
                <input type="file" name="HinhAnhPhu[]" accept="image/*" multiple>
                <span class="note-hint">Có thể chọn cùng lúc 2 - 6 ảnh để hiển thị trong bộ sưu tập (Gallery) trang chi tiết.</span>

                <div class="button">

                    <a href="danh-sach.php" class="btn-cancel">

                        HỦY

                    </a>

                    <button type="submit" class="btn-save">

                        LƯU

                    </button>

                    <button type="submit" name="luudang" class="btn-save-add">

                        LƯU & ĐĂNG

                    </button>

                </div>

            </form>

        </div>

    </div>

</body>

</html>
