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

if (!isset($_GET['id'])) {
    header("Location: danh-sach.php");
    exit();
}

$id = $_GET['id'];

/* Lấy sản phẩm */

$sql = "SELECT * FROM SanPham WHERE MaSanPham='$id'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) == 0) {
    die("Không tìm thấy sản phẩm!");
}

$sp = mysqli_fetch_assoc($result);

/* Lấy danh sách hãng */
$sqlHang = "SELECT * FROM HangLaptop";
$resultHang = mysqli_query($conn, $sqlHang);

/* Lấy danh sách ảnh trong gallery từ bảng hinhanhsanpham */
$sqlGallery = "SELECT * FROM hinhanhsanpham WHERE MaSanPham = $id ORDER BY ThuTu ASC, MaHinhAnh ASC";
$resultGallery = mysqli_query($conn, $sqlGallery);
?>

<!DOCTYPE html>
<html lang="vi">

<head>

    <meta charset="UTF-8">

    <title>Sửa sản phẩm</title>

    <link rel="stylesheet" href="../assets-admin/css-admin/them-san-pham-admin.css?v=<?=time()?>">

</head>

<body>

    <div class="overlay">

        <div class="popup">

            <h2>SỬA SẢN PHẨM</h2>

            <form action="../xu-ly-admin/xu-ly-sua-san-pham.php" method="POST" enctype="multipart/form-data">

                <input type="hidden" name="MaSanPham" value="<?php echo $sp['MaSanPham']; ?>">

                <input type="hidden" name="HinhCu" value="<?php echo $sp['HinhAnh']; ?>">

                <div class="row">

                    <div class="col">

                        <label>Tên Laptop</label>

                        <input type="text" name="TenSanPham" value="<?php echo $sp['TenSanPham']; ?>" required>

                    </div>

                    <div class="col">

                        <label>Thương hiệu</label>

                        <select name="MaHang">

                            <?php while ($hang = mysqli_fetch_assoc($resultHang)) { ?>

                            <option value="<?php echo $hang['MaHang']; ?>"
                                <?php if ($hang['MaHang'] == $sp['MaHang']) echo "selected"; ?>>

                                <?php echo $hang['TenHang']; ?>

                            </option>

                            <?php } ?>

                        </select>

                    </div>

                </div>

                <div class="row">

                    <div class="col">

                        <label>CPU</label>

                        <input type="text" name="CPU" value="<?php echo $sp['CPU']; ?>">

                    </div>

                    <div class="col">

                        <label>RAM</label>

                        <input type="text" name="RAM" value="<?php echo $sp['RAM']; ?>">

                    </div>

                </div>

                <div class="row">

                    <div class="col">

                        <label>Nâng cấp RAM</label>

                        <select name="NangCapRAM">

                            <option value="Có" <?php if ($sp['NangCapRAM'] == "Có") echo "selected"; ?>>
                                Có
                            </option>

                            <option value="Không" <?php if ($sp['NangCapRAM'] == "Không") echo "selected"; ?>>
                                Không
                            </option>

                        </select>

                    </div>

                    <div class="col">

                        <label>Ổ cứng</label>

                        <input type="text" name="OCung" value="<?php echo $sp['OCung']; ?>">

                    </div>

                </div>

                <div class="row">

                    <div class="col">

                        <label>Màn hình</label>

                        <input type="text" name="ManHinh" value="<?php echo $sp['ManHinh']; ?>">

                    </div>

                    <div class="col">

                        <label>Card đồ họa</label>

                        <input type="text" name="CardDoHoa" value="<?php echo $sp['CardDoHoa']; ?>">

                    </div>

                </div>

                <div class="row">

                    <div class="col">

                        <label>Giá</label>

                        <input type="number" name="Gia" value="<?php echo $sp['Gia']; ?>">

                    </div>

                    <div class="col">

                        <label>Số lượng</label>

                        <input type="number" name="SoLuong" value="<?php echo $sp['SoLuong']; ?>">

                    </div>

                </div>

                <label>Mô tả</label>

                <textarea name="MoTa" rows="5"><?php echo $sp['MoTa']; ?></textarea>

                <label>Hình đại diện chính hiện tại</label>
                <div style="margin: 10px 0;">
                    <?php 
                    $mainImgPath = !empty($sp['HinhAnh']) && file_exists("../../uploads/san-pham/" . $sp['HinhAnh'])
                        ? "../../uploads/san-pham/" . htmlspecialchars($sp['HinhAnh'])
                        : "../../uploads/san-pham/default.jpg";
                    ?>
                    <img src="<?php echo $mainImgPath; ?>" width="140" style="background:#fff; padding:6px; border-radius:8px; border:1px solid #3a3d44;">
                </div>

                <label>Đổi hình đại diện mới</label>
                <input type="file" name="HinhAnh" accept="image/*">
                <span class="note-hint">Chọn ảnh mới nếu bạn muốn thay đổi ảnh đại diện chính.</span>

                <!-- QUẢN LÝ BỘ SƯU TẬP ẢNH (GALLERY) -->
                <div class="admin-gallery-section">
                    <h3>Bộ sưu tập hình ảnh chi tiết (Gallery)</h3>
                    <p style="color: #94a3b8; font-size: 13px;">
                        Các hình ảnh bên dưới đang hiển thị trong bộ chuyển ảnh trang chi tiết sản phẩm. Tích chọn ô "Xóa" nếu muốn gỡ bỏ ảnh.
                    </p>

                    <div class="admin-gallery-grid">
                        <?php if ($resultGallery && mysqli_num_rows($resultGallery) > 0): ?>
                            <?php while ($imgRow = mysqli_fetch_assoc($resultGallery)): 
                                $subImgPath = file_exists("../../uploads/san-pham/" . $imgRow['DuongDan'])
                                    ? "../../uploads/san-pham/" . htmlspecialchars($imgRow['DuongDan'])
                                    : "../../uploads/san-pham/default.jpg";
                            ?>
                                <div class="admin-gallery-card">
                                    <span class="img-badge">#<?php echo $imgRow['ThuTu']; ?></span>
                                    <img src="<?php echo $subImgPath; ?>" alt="Gallery Image">
                                    <label class="delete-check">
                                        <input type="checkbox" name="XoaHinh[]" value="<?php echo $imgRow['MaHinhAnh']; ?>">
                                        Xóa ảnh
                                    </label>
                                </div>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <p style="color: #94a3b8; font-size: 13px; grid-column: 1 / -1;">Chưa có ảnh phụ nào trong bộ sưu tập.</p>
                        <?php endif; ?>
                    </div>

                    <div style="margin-top: 15px;">
                        <label>Thêm ảnh mới vào bộ sưu tập (chọn cùng lúc nhiều ảnh):</label>
                        <input type="file" name="HinhAnhPhu[]" accept="image/*" multiple>
                        <span class="note-hint">Bạn có thể chọn 1 hoặc nhiều ảnh (giữ phím Ctrl khi chọn) để bổ sung vào album chi tiết của laptop này.</span>
                    </div>
                </div>

                <div class="button">

                    <a href="danh-sach.php" class="btn-cancel">

                        HỦY

                    </a>

                    <button type="submit" class="btn-save">

                        CẬP NHẬT

                    </button>

                </div>

            </form>

        </div>

    </div>

</body>

</html>
