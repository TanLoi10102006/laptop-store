<?php
$baseUrl = defined('BASE_URL') ? BASE_URL : '/web-Ban-LapTop/';
?>
<footer>
    <div class="footer-container">
        <div class="footer-col">
            <h3>SHOP LAPTOP CHÍNH HÃNG</h3>
            <p>Hệ thống bán lẻ laptop Gaming, văn phòng, đồ họa chính hãng từ các thương hiệu hàng đầu với chính sách bảo hành và giá tốt nhất.</p>
            <p><strong>Hotline:</strong> 1900 6868 - 0393.924.965</p>
            <p><strong>Email:</strong> hotro@shoplaptop.vn</p>
            <p><strong>Địa chỉ:</strong> 123 Đường Công Nghệ, Quận 1, TP. Hồ Chí Minh</p>
        </div>

        <div class="footer-col">
            <h3>CHÍNH SÁCH BÁN HÀNG</h3>
            <ul>
                <li><a href="#">Chính sách bảo hành chính hãng</a></li>
                <li><a href="#">Chính sách đổi trả trong 30 ngày</a></li>
                <li><a href="#">Chính sách giao hàng toàn quốc</a></li>
                <li><a href="#">Hướng dẫn trả góp 0% lãi suất</a></li>
                <li><a href="#">Chính sách bảo mật thông tin</a></li>
            </ul>
        </div>

        <div class="footer-col">
            <h3>DANH MỤC SẢN PHẨM</h3>
            <ul>
                <li><a href="<?php echo $baseUrl; ?>pages/macbook.php">Laptop Apple MacBook</a></li>
                <li><a href="<?php echo $baseUrl; ?>pages/dell.php">Laptop Dell</a></li>
                <li><a href="<?php echo $baseUrl; ?>pages/hp.php">Laptop HP</a></li>
                <li><a href="<?php echo $baseUrl; ?>pages/asus.php">Laptop Asus</a></li>
                <li><a href="<?php echo $baseUrl; ?>pages/acer.php">Laptop Acer</a></li>
                <li><a href="<?php echo $baseUrl; ?>pages/lenovo.php">Laptop Lenovo</a></li>
            </ul>
        </div>

        <div class="footer-col">
            <h3>PHƯƠNG THỨC THANH TOÁN</h3>
            <p>Hỗ trợ thanh toán tiện lợi và an toàn:</p>
            <div class="payment-methods">
                <span class="pay-badge">Tiền mặt (COD)</span>
                <span class="pay-badge">Thẻ ATM / Visa / MasterCard</span>
                <span class="pay-badge">Chuyển khoản QR Code</span>
            </div>
            <div class="social-links" style="margin-top: 15px;">
                <a href="#">Facebook</a>
                <a href="#">YouTube</a>
                <a href="#">Zalo</a>
            </div>
        </div>
    </div>

    <div class="copyright">
        <p>&copy; <?php echo date("Y"); ?> SHOP LAPTOP. Bản quyền thuộc về hệ thống bán Laptop Chính Hãng. All Rights Reserved.</p>
    </div>
</footer>
