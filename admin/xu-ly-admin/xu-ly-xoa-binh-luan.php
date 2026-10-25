<?php

session_start();

include("../../config/ket-noi.php");

if (!isset($_GET["id"])) {

    header("Location: ../quan-ly-binh-luan-admin/danh-sach.php");

    exit();
}

$id = intval($_GET["id"]);

mysqli_query($conn, "DELETE FROM DanhGia WHERE MaDanhGia='$id'");

header("Location: ../quan-ly-binh-luan-admin/danh-sach.php");

exit();