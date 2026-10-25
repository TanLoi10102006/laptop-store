<?php
include("../../config/ket-noi.php");

if (!isset($_SESSION['MaTaiKhoan']) || $_SESSION['VaiTro'] !== 'admin') {
    header("Location: ../../nguoi-dung/dang-nhap.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../quan-ly-san-pham-admin/danh-sach.php");
    exit();
}

/* Lấy dữ liệu an toàn */
$MaSanPham  = (int)($_POST['MaSanPham'] ?? 0);
$TenSanPham = clean_input($conn, $_POST['TenSanPham'] ?? '');
$MaHang     = (int)($_POST['MaHang'] ?? 0);
$CPU         = clean_input($conn, $_POST['CPU'] ?? '');
$RAM         = clean_input($conn, $_POST['RAM'] ?? '');
$NangCapRAM  = clean_input($conn, $_POST['NangCapRAM'] ?? 'Có');
$OCung       = clean_input($conn, $_POST['OCung'] ?? '');
$ManHinh     = clean_input($conn, $_POST['ManHinh'] ?? '');
$CardDoHoa   = clean_input($conn, $_POST['CardDoHoa'] ?? '');
$MoTa        = clean_input($conn, $_POST['MoTa'] ?? '');
$Gia         = (float)($_POST['Gia'] ?? 0);
$SoLuong     = (int)($_POST['SoLuong'] ?? 0);

$HinhAnh = clean_input($conn, $_POST['HinhCu'] ?? '');

if ($MaSanPham <= 0 || empty($TenSanPham) || $MaHang <= 0 || $Gia <= 0) {
    echo "<script>alert('Vui lòng nhập đầy đủ các trường bắt buộc!'); window.history.back();</script>";
    exit();
}

/* Upload hình mới nếu có */
$mainImageUpdated = false;
if (isset($_FILES['HinhAnh']) && $_FILES['HinhAnh']['error'] == 0) {
    $allowedExts = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
    $fileInfo = pathinfo($_FILES['HinhAnh']['name']);
    $ext = strtolower($fileInfo['extension'] ?? '');

    if (in_array($ext, $allowedExts)) {
        $HinhAnh = time() . "_" . preg_replace("/[^a-zA-Z0-9_\.-]/", "", basename($_FILES['HinhAnh']['name']));
        move_uploaded_file($_FILES['HinhAnh']['tmp_name'], "../../uploads/san-pham/" . $HinhAnh);
        $mainImageUpdated = true;
    } else {
        echo "<script>alert('Chỉ cho phép tải lên file ảnh (JPG, PNG, WEBP, GIF)!'); window.history.back();</script>";
        exit();
    }
}

/* Cập nhật dữ liệu sản phẩm */
$sql = "UPDATE SanPham SET 
        TenSanPham = '$TenSanPham', 
        MaHang = $MaHang, 
        CPU = '$CPU', 
        RAM = '$RAM', 
        NangCapRAM = '$NangCapRAM', 
        OCung = '$OCung', 
        ManHinh = '$ManHinh', 
        CardDoHoa = '$CardDoHoa', 
        MoTa = '$MoTa', 
        Gia = $Gia, 
        SoLuong = $SoLuong, 
        HinhAnh = '$HinhAnh' 
        WHERE MaSanPham = $MaSanPham";

if (mysqli_query($conn, $sql)) {
    // Nếu có đổi ảnh đại diện mới, cập nhật hoặc thêm vào hinhanhsanpham với thứ tự 1
    if ($mainImageUpdated) {
        $checkMain = mysqli_query($conn, "SELECT MaHinhAnh FROM hinhanhsanpham WHERE MaSanPham = $MaSanPham AND ThuTu = 1");
        if ($checkMain && mysqli_num_rows($checkMain) > 0) {
            mysqli_query($conn, "UPDATE hinhanhsanpham SET DuongDan = '$HinhAnh' WHERE MaSanPham = $MaSanPham AND ThuTu = 1");
        } else {
            mysqli_query($conn, "INSERT INTO hinhanhsanpham (MaSanPham, DuongDan, ThuTu) VALUES ($MaSanPham, '$HinhAnh', 1)");
        }
    }

    // Xử lý xóa các ảnh được tích chọn trong gallery
    if (isset($_POST['XoaHinh']) && is_array($_POST['XoaHinh'])) {
        foreach ($_POST['XoaHinh'] as $delId) {
            $delId = (int)$delId;
            if ($delId > 0) {
                $selImg = mysqli_query($conn, "SELECT DuongDan FROM hinhanhsanpham WHERE MaHinhAnh = $delId AND MaSanPham = $MaSanPham");
                if ($selImg && $rDel = mysqli_fetch_assoc($selImg)) {
                    // Không xóa file ảnh nếu nó trùng với HinhAnh chính của bảng SanPham
                    if ($rDel['DuongDan'] !== $HinhAnh && file_exists("../../uploads/san-pham/" . $rDel['DuongDan'])) {
                        @unlink("../../uploads/san-pham/" . $rDel['DuongDan']);
                    }
                    mysqli_query($conn, "DELETE FROM hinhanhsanpham WHERE MaHinhAnh = $delId AND MaSanPham = $MaSanPham");
                }
            }
        }
    }

    // Xử lý thêm nhiều ảnh mới vào Gallery
    if (isset($_FILES['HinhAnhPhu']) && is_array($_FILES['HinhAnhPhu']['name'])) {
        $allowedExts = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
        $targetDir = "../../uploads/san-pham/";

        // Tìm thứ tự lớn nhất hiện tại
        $resMax = mysqli_query($conn, "SELECT IFNULL(MAX(ThuTu), 0) AS maxOrder FROM hinhanhsanpham WHERE MaSanPham = $MaSanPham");
        $maxOrder = 1;
        if ($resMax && $rM = mysqli_fetch_assoc($resMax)) {
            $maxOrder = (int)$rM['maxOrder'];
        }

        foreach ($_FILES['HinhAnhPhu']['name'] as $key => $name) {
            if ($_FILES['HinhAnhPhu']['error'][$key] == 0 && !empty($name)) {
                $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
                if (in_array($ext, $allowedExts)) {
                    $maxOrder++;
                    $fileName = time() . "_" . uniqid() . "_" . preg_replace("/[^a-zA-Z0-9_\.-]/", "", basename($name));
                    if (move_uploaded_file($_FILES['HinhAnhPhu']['tmp_name'][$key], $targetDir . $fileName)) {
                        $sqlSub = "INSERT INTO hinhanhsanpham (MaSanPham, DuongDan, ThuTu) VALUES ($MaSanPham, '$fileName', $maxOrder)";
                        mysqli_query($conn, $sqlSub);
                    }
                }
            }
        }
    }

    echo "<script>alert('Cập nhật sản phẩm và bộ sưu tập ảnh thành công!'); window.location='../quan-ly-san-pham-admin/sua.php?id=$MaSanPham';</script>";
    exit();
} else {
    echo "<script>alert('Lỗi cập nhật: " . addslashes(mysqli_error($conn)) . "'); window.history.back();</script>";
    exit();
}
