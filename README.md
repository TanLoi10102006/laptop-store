# SHOP LAPTOP - Website Bán Laptop Chính Hãng

Website thương mại điện tử bán laptop chính hãng, được xây dựng bằng PHP thuần và MySQL.

## Giới thiệu

Đây là dự án website bán laptop với đầy đủ chức năng dành cho cả người dùng và quản trị viên. Website được thiết kế với giao diện Dark Theme hiện đại, hỗ trợ đầy đủ các tính năng thương mại điện tử cơ bản.

## Công nghệ sử dụng

- **Backend:** PHP thuần (không sử dụng framework)
- **Database:** MySQL / MariaDB
- **Frontend:** HTML5, CSS3, JavaScript (Vanilla)
- **Server:** Apache (XAMPP)
- **Bảo mật:** MySQLi Prepared Statements, chống SQL Injection, chống XSS

## Chức năng chính

### Người dùng (User)

- Đăng ký tài khoản
- Đăng nhập / Đăng xuất
- Quên mật khẩu
- Quản lý hồ sơ cá nhân
- Đổi mật khẩu
- Xem danh sách sản phẩm
- Tìm kiếm sản phẩm
- Lọc sản phẩm theo thương hiệu
- Sắp xếp sản phẩm theo giá (tăng dần / giảm dần)
- Lọc theo mức giá
- Xem chi tiết sản phẩm với bộ chuyển ảnh (Gallery)
- Thêm sản phẩm vào giỏ hàng
- Quản lý giỏ hàng
- Đặt hàng / Thanh toán
- Xem lịch sử đơn hàng
- Xem chi tiết đơn hàng
- Hủy đơn hàng
- Đánh giá / Bình luận sản phẩm
- Xem thêm sản phẩm (Load More - AJAX không tải lại trang)

### Quản trị viên (Admin)

- Dashboard thống kê tổng quan (sản phẩm, khách hàng, đơn hàng, doanh thu)
- Thống kê đơn hàng theo trạng thái
- Quản lý sản phẩm (CRUD)
- Tải lên nhiều hình ảnh cho sản phẩm (Gallery nhiều góc chụp)
- Quản lý danh mục thương hiệu
- Quản lý khách hàng
- Quản lý đơn hàng (xem, cập nhật trạng thái, xóa)
- Quản lý bình luận / đánh giá
- Quản lý tài khoản admin
- Tìm kiếm trong từng trang quản lý

## Cấu trúc thư mục

```text
web-Ban-LapTop/
|
|-- admin/                          # Khu vực quản trị
|   |-- assets-admin/css-admin/     # CSS riêng cho admin (Full Dark Theme)
|   |-- includes-admin/             # Header, sidebar, footer admin
|   |-- quan-ly-san-pham-admin/     # CRUD sản phẩm
|   |-- quan-ly-don-hang-admin/     # Quản lý đơn hàng
|   |-- quan-ly-khach-hang-admin/   # Quản lý khách hàng
|   |-- quan-ly-tai-khoan-admin/    # Quản lý tài khoản admin
|   |-- quan-ly-thuong-hieu-admin/  # Quản lý thương hiệu
|   |-- quan-ly-binh-luan-admin/    # Quản lý bình luận
|   |-- xu-ly-admin/                # Xử lý logic admin (thêm, sửa, xóa)
|   +-- dashboard.php               # Trang Dashboard
|
|-- assets/                         # Tài nguyên frontend
|   |-- css/                        # File CSS (header, menu, index, footer, ...)
|   |-- js/                         # File JavaScript (gallery.js, load-more.js)
|   +-- images/                     # Hình ảnh giao diện (banner)
|
|-- config/
|   +-- ket-noi.php                 # Cấu hình kết nối CSDL + hàm tiện ích
|
|-- database/
|   +-- webbanlaptop.sql            # File SQL tạo CSDL và dữ liệu mẫu
|
|-- includes/                       # Thành phần dùng chung
|   |-- header.php
|   |-- menu.php
|   |-- footer.php
|   +-- bo-loc.php                  # Bộ lọc sản phẩm (thương hiệu, giá, sắp xếp)
|
|-- nguoi-dung/                     # Trang người dùng
|   |-- dang-nhap.php
|   |-- dang-ky.php
|   |-- quen-mat-khau.php
|   |-- ho-so.php
|   |-- gio-hang.php
|   |-- thanh-toan.php
|   |-- dat-hang-thanh-cong.php
|   |-- don-hang-cua-toi.php
|   +-- chi-tiet-don-hang.php
|
|-- pages/                          # Trang sản phẩm
|   |-- chi-tiet.php                # Chi tiết sản phẩm + Gallery ảnh + Đánh giá
|   |-- thuong-hieu.php             # Lọc theo thương hiệu
|   +-- tim-kiem.php                # Kết quả tìm kiếm
|
|-- uploads/
|   +-- san-pham/                   # Thư mục lưu hình ảnh sản phẩm tải lên
|
|-- xu-ly/                          # Xử lý logic người dùng
|   |-- xu-ly-dang-nhap.php
|   |-- xu-ly-dang-ky.php
|   |-- xu-ly-dang-xuat.php
|   |-- xu-ly-them-gio-hang.php
|   |-- xu-ly-xoa-gio-hang.php
|   |-- xu-ly-thanh-toan.php
|   |-- xu-ly-danh-gia.php
|   |-- xu-ly-load-more.php         # API Load More (AJAX)
|   +-- ...
|
|-- index.php                       # Trang chủ
+-- .gitignore
```

## Cài đặt

### Yêu cầu hệ thống

- PHP >= 7.4
- MySQL >= 5.7 hoặc MariaDB >= 10.4
- Apache Web Server
- XAMPP (khuyến nghị sử dụng)

### Các bước cài đặt

**1. Clone repository**

```bash
git clone https://github.com/your-username/web-Ban-LapTop.git
```

**2. Sao chép vào thư mục web server**

Sao chép toàn bộ thư mục `web-Ban-LapTop` vào thư mục `htdocs` của XAMPP:

```text
C:\xampp\htdocs\web-Ban-LapTop\
```

**3. Tạo database**

- Mở phpMyAdmin tại `http://localhost/phpmyadmin`.
- Tạo database mới tên `webbanlaptop` với charset `utf8mb4_general_ci`.
- Import file `database/webbanlaptop.sql`.

Hoặc sử dụng dòng lệnh:

```bash
mysql -u root -e "CREATE DATABASE IF NOT EXISTS webbanlaptop CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;"
mysql -u root webbanlaptop < database/webbanlaptop.sql
```

**4. Cấu hình kết nối database**

Mở file `config/ket-noi.php` và chỉnh sửa thông tin kết nối nếu cần:

```php
$servername = "localhost";
$username   = "root";
$password   = "";
$database   = "webbanlaptop";
```

**5. Khởi động XAMPP**

- Bật Apache và MySQL trong XAMPP Control Panel.
- Truy cập website tại: `http://localhost/web-Ban-LapTop/`.

### Tài khoản mặc định

| Vai trò | Email | Mật khẩu |
|---------|-------|----------|
| Admin | admin@gmail.com | 123456 |
| User | tanloi10102006@gmail.com | 123456 |

> **Lưu ý:** Đây là tài khoản mẫu phục vụ việc chạy thử dự án. Không sử dụng mật khẩu mặc định này trên website thực tế.

## Cơ sở dữ liệu

### Các bảng dữ liệu

| Bảng | Mô tả |
|------|-------|
| `TaiKhoan` | Lưu thông tin tài khoản người dùng và admin |
| `HangLaptop` | Danh mục thương hiệu laptop (Apple, Dell, HP, Asus, Acer, Lenovo) |
| `SanPham` | Thông tin sản phẩm laptop |
| `HinhAnhSanPham` | Bộ sưu tập hình ảnh sản phẩm (Gallery nhiều góc chụp) |
| `GioHang` | Giỏ hàng của người dùng |
| `ChiTietGioHang` | Chi tiết sản phẩm trong giỏ hàng |
| `DonHang` | Thông tin đơn đặt hàng |
| `ChiTietDonHang` | Chi tiết sản phẩm trong đơn hàng |
| `DanhGia` | Đánh giá và bình luận sản phẩm |

### Sơ đồ quan hệ

```text
TaiKhoan (1) --- (N) DonHang
TaiKhoan (1) --- (1) GioHang
TaiKhoan (1) --- (N) DanhGia
HangLaptop (1) --- (N) SanPham
SanPham (1) --- (N) HinhAnhSanPham
SanPham (1) --- (N) ChiTietGioHang
SanPham (1) --- (N) ChiTietDonHang
SanPham (1) --- (N) DanhGia
GioHang (1) --- (N) ChiTietGioHang
DonHang (1) --- (N) ChiTietDonHang
```

## Tính năng nổi bật

### 1. Load More Products (AJAX)

- Hiển thị 8 sản phẩm ban đầu.
- Nhấn nút **"Xem thêm sản phẩm"** để tải thêm sản phẩm mà không cần tải lại trang.
- Tự động ẩn nút khi đã hiển thị hết sản phẩm.
- Giữ nguyên bộ lọc thương hiệu, tìm kiếm và sắp xếp giá.

### 2. Product Image Gallery

- Hỗ trợ nhiều hình ảnh cho mỗi sản phẩm, chụp từ nhiều góc.
- Admin có thể tải lên nhiều ảnh cùng lúc.
- Có nút chuyển ảnh trái/phải và số thứ tự ảnh (1/4, 2/4, ...).
- Nhấn vào ảnh thu nhỏ (thumbnail) để chuyển ảnh nhanh.
- Hỗ trợ sử dụng phím mũi tên trái/phải trên bàn phím.

### 3. Giao diện Admin Full Dark Theme

- Toàn bộ giao diện admin sử dụng tông màu tối đồng nhất.
- Màu nền chính: `#12141a`.
- Màu nền thẻ: `#1a1d26`.
- Màu đường viền: `#2b3040`.
- Giao diện chuyên nghiệp, dễ đọc và tương thích với nhiều kích thước màn hình (responsive).


## Bảo mật

- Sử dụng `mysqli_real_escape_string()` để xử lý dữ liệu đầu vào. Nên ưu tiên **MySQLi Prepared Statements** cho các truy vấn SQL.
- Sử dụng `htmlspecialchars()` để hạn chế nguy cơ XSS khi hiển thị dữ liệu.
- Kiểm tra quyền truy cập admin bằng Session.
- Mật khẩu cần được mã hóa bằng `password_hash()` và kiểm tra bằng `password_verify()`. Không nên sử dụng `md5()` để lưu mật khẩu.
- Kiểm tra loại và nội dung tệp tải lên; chỉ cho phép các định dạng cần thiết như JPG, PNG, WebP, GIF.
- Kiểm tra và xác thực dữ liệu đầu vào trước khi xử lý.
- Kiểm tra quyền sở hữu đơn hàng, giỏ hàng và các dữ liệu riêng tư trước khi cho phép truy cập hoặc chỉnh sửa.

## Đóng góp

1. Fork repository.
2. Tạo branch mới:

   ```bash
   git checkout -b feature/ten-tinh-nang
   ```

3. Commit thay đổi:

   ```bash
   git commit -m "Thêm tính năng mới"
   ```

4. Push lên branch:

   ```bash
   git push origin feature/ten-tinh-nang
   ```

5. Tạo Pull Request.

## Tác giả

- **Họ và tên:** Tan Loi
- **Liên hệ:** tanloi10102006@gmail.com

## Giấy phép

Dự án được phát triển phục vụ mục đích học tập và nghiên cứu.
