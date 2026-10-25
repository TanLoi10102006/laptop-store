<?php
$currentPage = htmlspecialchars(basename($_SERVER['PHP_SELF']));
$getParams = $_GET;
$currentSapXep = isset($_GET['sapxep']) ? $_GET['sapxep'] : '';
$currentMucGia = isset($_GET['mucgia']) ? $_GET['mucgia'] : '';

function buildFilterUrl($addParams) {
    global $currentPage, $getParams;
    $new = array_merge($getParams, $addParams);
    foreach ($new as $k => $v) {
        if ($v === '' || $v === null) unset($new[$k]);
    }
    return $currentPage . '?' . http_build_query($new);
}
?>

<div class="filter-container">
    <div class="filter-row">
        <div class="sort-product">
            <span class="filter-label">Sắp xếp theo giá:</span>
            <a href="<?php echo buildFilterUrl(['sapxep' => 'gia-tang']); ?>" class="<?php echo ($currentSapXep == 'gia-tang') ? 'active-filter' : ''; ?>">
                Giá thấp - cao
            </a>
            <a href="<?php echo buildFilterUrl(['sapxep' => 'gia-giam']); ?>" class="<?php echo ($currentSapXep == 'gia-giam') ? 'active-filter' : ''; ?>">
                Giá cao - thấp
            </a>
            <a href="<?php echo buildFilterUrl(['sapxep' => '']); ?>" class="<?php echo ($currentSapXep == '') ? 'active-filter' : ''; ?>">
                Mặc định
            </a>
        </div>

        <div class="price-filter">
            <span class="filter-label">Lọc theo tầm giá:</span>
            <a href="<?php echo buildFilterUrl(['mucgia' => 'duoi-15']); ?>" class="<?php echo ($currentMucGia == 'duoi-15') ? 'active-filter' : ''; ?>">
                Dưới 15 triệu
            </a>
            <a href="<?php echo buildFilterUrl(['mucgia' => '15-25']); ?>" class="<?php echo ($currentMucGia == '15-25') ? 'active-filter' : ''; ?>">
                15 - 25 triệu
            </a>
            <a href="<?php echo buildFilterUrl(['mucgia' => 'tren-25']); ?>" class="<?php echo ($currentMucGia == 'tren-25') ? 'active-filter' : ''; ?>">
                Trên 25 triệu
            </a>
            <?php if (!empty($currentMucGia) || !empty($currentSapXep)): ?>
                <a href="<?php echo $currentPage; ?>" class="clear-filter">Bỏ lọc</a>
            <?php endif; ?>
        </div>
    </div>
</div>
