# SHOP LAPTOP - Website Ban Laptop Chinh Hang

Website thuong mai dien tu ban laptop chinh hang, xay dung bang PHP thuan va MySQL.

## Gioi thieu

Day la du an website ban laptop voi day du chuc nang cho ca nguoi dung va quan tri vien. Website duoc thiet ke voi giao dien dark theme hien dai, ho tro day du cac tinh nang thuong mai dien tu co ban.

## Cong nghe su dung

- **Backend:** PHP thuan (khong framework)
- **Database:** MySQL / MariaDB
- **Frontend:** HTML5, CSS3, JavaScript (Vanilla)
- **Server:** Apache (XAMPP)
- **Bao mat:** MySQLi Prepared Statements, chong SQL Injection, chong XSS

## Chuc nang chinh

### Nguoi dung (User)

- Dang ky tai khoan
- Dang nhap / Dang xuat
- Quen mat khau
- Quan ly ho so ca nhan
- Doi mat khau
- Xem danh sach san pham
- Tim kiem san pham
- Loc san pham theo thuong hieu
- Sap xep san pham theo gia (tang / giam)
- Loc theo muc gia
- Xem chi tiet san pham voi bo chuyen anh (Gallery)
- Them san pham vao gio hang
- Quan ly gio hang
- Dat hang / Thanh toan
- Xem lich su don hang
- Chi tiet don hang
- Huy don hang
- Danh gia / Binh luan san pham
- Xem them san pham (Load More - AJAX khong tai lai trang)

### Quan tri vien (Admin)

- Dashboard thong ke tong quan (san pham, khach hang, don hang, doanh thu)
- Thong ke don hang theo trang thai
- Quan ly san pham (CRUD)
- Upload nhieu hinh anh cho san pham (Gallery nhieu goc chup)
- Quan ly danh muc thuong hieu
- Quan ly khach hang
- Quan ly don hang (xem, cap nhat trang thai, xoa)
- Quan ly binh luan / danh gia
- Quan ly tai khoan admin
- Tim kiem trong tung trang quan ly

## Cau truc thu muc

```
web-Ban-LapTop/
|
|-- admin/                          # Khu vuc quan tri
|   |-- assets-admin/css-admin/     # CSS rieng cho admin (Full Dark Theme)
|   |-- includes-admin/             # Header, sidebar, footer admin
|   |-- quan-ly-san-pham-admin/     # CRUD san pham
|   |-- quan-ly-don-hang-admin/     # Quan ly don hang
|   |-- quan-ly-khach-hang-admin/   # Quan ly khach hang
|   |-- quan-ly-tai-khoan-admin/    # Quan ly tai khoan admin
|   |-- quan-ly-thuong-hieu-admin/  # Quan ly thuong hieu
|   |-- quan-ly-binh-luan-admin/    # Quan ly binh luan
|   |-- xu-ly-admin/                # Xu ly logic admin (them, sua, xoa)
|   +-- dashboard.php               # Trang Dashboard
|
|-- assets/                         # Tai nguyen frontend
|   |-- css/                        # File CSS (header, menu, index, footer, ...)
|   |-- js/                         # File JS (gallery.js, load-more.js)
|   +-- images/                     # Hinh anh giao dien (banner)
|
|-- config/
|   +-- ket-noi.php                 # Cau hinh ket noi CSDL + ham tien ich
|
|-- database/
|   +-- webbanlaptop.sql            # File SQL tao CSDL va du lieu mau
|
|-- includes/                       # Thanh phan dung chung
|   |-- header.php
|   |-- menu.php
|   |-- footer.php
|   +-- bo-loc.php                  # Bo loc san pham (thuong hieu, gia, sap xep)
|
|-- nguoi-dung/                     # Trang nguoi dung
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
|-- pages/                          # Trang san pham
|   |-- chi-tiet.php                # Chi tiet san pham + Gallery anh + Danh gia
|   |-- thuong-hieu.php             # Loc theo thuong hieu
|   +-- tim-kiem.php                # Ket qua tim kiem
|
|-- uploads/
|   +-- san-pham/                   # Thu muc luu hinh san pham upload
|
|-- xu-ly/                          # Xu ly logic nguoi dung
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
|-- index.php                       # Trang chu
+-- .gitignore
```

## Cai dat

### Yeu cau he thong

- PHP >= 7.4
- MySQL >= 5.7 hoac MariaDB >= 10.4
- Apache Web Server
- XAMPP (khuyen nghi su dung)

### Cac buoc cai dat

1. **Clone repository:**

```bash
git clone https://github.com/your-username/web-Ban-LapTop.git
```

2. **Copy vao thu muc web server:**

Copy toan bo thu muc `web-Ban-LapTop` vao `htdocs` cua XAMPP:

```
C:\xampp\htdocs\web-Ban-LapTop\
```

3. **Tao database:**

- Mo phpMyAdmin tai `http://localhost/phpmyadmin`
- Tao database moi ten `webbanlaptop` voi charset `utf8mb4_general_ci`
- Import file `database/webbanlaptop.sql`

Hoac dung command line:

```bash
mysql -u root -e "CREATE DATABASE IF NOT EXISTS webbanlaptop CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;"
mysql -u root webbanlaptop < database/webbanlaptop.sql
```

4. **Cau hinh ket noi database:**

Mo file `config/ket-noi.php` va chinh sua thong tin neu can:

```php
$servername = "localhost";
$username   = "root";
$password   = "";
$database   = "webbanlaptop";
```

5. **Khoi dong XAMPP:**

- Bat Apache va MySQL trong XAMPP Control Panel
- Truy cap: `http://localhost/web-Ban-LapTop/`

### Tai khoan mac dinh

| Vai tro | Email | Mat khau |
|---------|-------|----------|
| Admin | admin@gmail.com | 123456 |
| User | tanloi10102006@gmail.com | 123456 |

## Co so du lieu

### Bang du lieu

| Bang | Mo ta |
|------|-------|
| `TaiKhoan` | Luu thong tin tai khoan nguoi dung va admin |
| `HangLaptop` | Danh muc thuong hieu laptop (Apple, Dell, HP, Asus, Acer, Lenovo) |
| `SanPham` | Thong tin san pham laptop |
| `HinhAnhSanPham` | Bo suu tap hinh anh san pham (Gallery nhieu goc chup) |
| `GioHang` | Gio hang cua nguoi dung |
| `ChiTietGioHang` | Chi tiet san pham trong gio hang |
| `DonHang` | Thong tin don dat hang |
| `ChiTietDonHang` | Chi tiet san pham trong don hang |
| `DanhGia` | Danh gia va binh luan san pham |

### So do quan he

```
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

## Tinh nang noi bat

### Load More Products (AJAX)
- Hien thi 8 san pham ban dau
- Bam nut "Xem them san pham" de tai them ma khong tai lai trang
- Tu dong an nut khi da hien thi het san pham
- Giu nguyen bo loc thuong hieu, tim kiem va sap xep gia

### Product Image Gallery
- Ho tro nhieu hinh anh cho moi san pham (chup nhieu goc)
- Admin co the upload nhieu anh cung luc
- Nut chuyen anh trai/phai va so dem (1/4, 2/4, ...)
- Click anh thumbnail de chuyen nhanh
- Ho tro phim mui ten trai/phai tren ban phim

### Giao dien Admin Full Dark Theme
- Toan bo giao dien admin su dung tong mau toi dong nhat
- Nen chinh: #12141a | Card: #1a1d26 | Vien: #2b3040
- Giao dien chuyen nghiep, de doc va responsive

## Hinh anh minh hoa

### Trang chu
- Hien thi banner khuyen mai
- Danh sach san pham moi nhat
- Bo loc thuong hieu, gia, sap xep

### Trang chi tiet san pham
- Gallery anh voi nut chuyen trai/phai
- Thong so ky thuat chi tiet
- Nut them gio hang va mua ngay
- Khu vuc danh gia binh luan

### Trang quan tri
- Dashboard thong ke tong quan
- Quan ly san pham voi upload nhieu anh
- Quan ly don hang, khach hang, binh luan

## Bao mat

- Chong SQL Injection bang `mysqli_real_escape_string()`
- Chong XSS bang `htmlspecialchars()`
- Kiem tra quyen truy cap admin bang Session
- Mat khau duoc ma hoa bang `md5()` (khuyen nghi nang cap len `password_hash()`)
- Kiem tra dinh dang file upload (chi cho phep jpg, png, webp, gif)
- Lam sach du lieu dau vao truoc khi xu ly

## Dong gop

1. Fork repository
2. Tao branch moi: `git checkout -b feature/ten-tinh-nang`
3. Commit thay doi: `git commit -m "Them tinh nang moi"`
4. Push len branch: `git push origin feature/ten-tinh-nang`
5. Tao Pull Request

## Tac gia

- **Ho va ten:** Tan Loi
- **Lien he:** tanloi10102006@gmail.com

## Giay phep

Du an nay duoc phat trien cho muc dich hoc tap va nghien cuu.

