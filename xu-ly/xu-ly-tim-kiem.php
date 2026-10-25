<?php
$keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
header("Location: ../pages/tim-kiem.php?keyword=" . urlencode($keyword));
exit();
