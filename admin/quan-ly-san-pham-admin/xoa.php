<?php
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
header("Location: ../xu-ly-admin/xu-ly-xoa-san-pham.php?id=$id");
exit();
