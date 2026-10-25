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

if (empty($TenSanPham) || $MaHang <= 0 || $Gia <= 0) {
    echo "<script>alert('Vui lòng nhập tên sản phẩm, hãng và giá hợp lệ!'); window.history.back();</script>";
    exit();
}

/* Upload hình ảnh an toàn */
$HinhAnh = "default.jpg";

if (isset($_FILES['HinhAnh']) && $_FILES['HinhAnh']['error'] == 0) {
    $allowedExts = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
    $fileInfo = pathinfo($_FILES['HinhAnh']['name']);
    $ext = strtolower($fileInfo['extension'] ?? '');

    if (in_array($ext, $allowedExts)) {
        $HinhAnh = time() . "_" . preg_replace("/[^a-zA-Z0-9_\.-]/", "", basename($_FILES['HinhAnh']['name']));
        $targetDir = "../../uploads/san-pham/";
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }
        move_uploaded_file($_FILES['HinhAnh']['tmp_name'], $targetDir . $HinhAnh);
    } else {
        echo "<script>alert('Chỉ cho phép tải lên file ảnh (JPG, PNG, WEBP, GIF)!'); window.history.back();</script>";
        exit();
    }
}

/* Thêm sản phẩm */
$sql = "INSERT INTO SanPham 
        (TenSanPham, MaHang, CPU, RAM, NangCapRAM, OCung, ManHinh, CardDoHoa, MoTa, Gia, SoLuong, HinhAnh) 
        VALUES 
        ('$TenSanPham', $MaHang, '$CPU', '$RAM', '$NangCapRAM', '$OCung', '$ManHinh', '$CardDoHoa', '$MoTa', $Gia, $SoLuong, '$HinhAnh')";

if (mysqli_query($conn, $sql)) {
    $newProductId = mysqli_insert_id($conn);

    // Luôn lưu ảnh đại diện chính vào hinhanhsanpham với thứ tự 1
    if (!empty($HinhAnh) && $HinhAnh !== 'default.jpg') {
        $sqlMainGallery = "INSERT INTO hinhanhsanpham (MaSanPham, DuongDan, ThuTu) VALUES ($newProductId, '$HinhAnh', 1)";
        mysqli_query($conn, $sqlMainGallery);
    }

    // Xử lý các ảnh phụ tải lên kèm theo (nhiều ảnh)
    if (isset($_FILES['HinhAnhPhu']) && is_array($_FILES['HinhAnhPhu']['name'])) {
        $allowedExts = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
        $targetDir = "../../uploads/san-pham/";
        $order = 2;

        foreach ($_FILES['HinhAnhPhu']['name'] as $key => $name) {
            if ($_FILES['HinhAnhPhu']['error'][$key] == 0 && !empty($name)) {
                $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
                if (in_array($ext, $allowedExts)) {
                    $fileName = time() . "_" . uniqid() . "_" . preg_replace("/[^a-zA-Z0-9_\.-]/", "", basename($name));
                    if (move_uploaded_file($_FILES['HinhAnhPhu']['tmp_name'][$key], $targetDir . $fileName)) {
                        $sqlSub = "INSERT INTO hinhanhsanpham (MaSanPham, DuongDan, ThuTu) VALUES ($newProductId, '$fileName', $order)";
                        mysqli_query($conn, $sqlSub);
                        $order++;
                    }
                }
            }
        }
    }

    if (isset($_POST['luudang'])) {
        header("Location: ../quan-ly-san-pham-admin/them.php");
    } else {
        header("Location: ../quan-ly-san-pham-admin/danh-sach.php");
    }
    exit();
} else {
    echo "<script>alert('Lỗi thêm sản phẩm: " . addslashes(mysqli_error($conn)) . "'); window.history.back();</script>";
    exit();
}
