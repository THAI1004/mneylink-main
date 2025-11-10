# MneyLink - URL Shortener & Monetization Platform

Hệ thống rút gọn URL và kiếm tiền được xây dựng trên CakePHP 3.10.1

## 📋 Yêu cầu hệ thống

- PHP >= 7.2 (khuyến nghị 7.4)
- MySQL >= 5.7
- Apache/Nginx với mod_rewrite
- Composer (tùy chọn - dependencies đã có sẵn)
- Redis (tùy chọn - dùng cho production)

## 🚀 Cài đặt nhanh trên Laragon

### Bước 1: Chuẩn bị

```bash
# Di chuyển vào thư mục dự án
cd c:\laragon\www\mneylink-main\mneylink-main
```

### Bước 2: Chạy script setup (Windows)

```bash
setup.bat
```

**Hoặc setup thủ công:**

### Bước 2a: Tạo database

```sql
CREATE DATABASE mneylink CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### Bước 2b: Tạo thư mục tmp và logs

```bash
mkdir tmp\cache\models
mkdir tmp\cache\persistent
mkdir tmp\cache\views
mkdir tmp\sessions
mkdir tmp\tests
mkdir logs
```

### Bước 2c: Phân quyền (Windows)

```bash
icacls tmp /grant Everyone:(OI)(CI)F /T
icacls logs /grant Everyone:(OI)(CI)F /T
```

### Bước 3: Chỉnh sửa cấu hình (nếu cần)

Mở file `config/app_local.php` và chỉnh sửa:

```php
'Datasources' => [
    'default' => [
        'host' => 'localhost',
        'port' => '3306',
        'username' => 'root',      // Thay đổi username
        'password' => '',           // Thay đổi password
        'database' => 'mneylink',   // Thay đổi tên database
    ],
],
```

### Bước 4: Chạy migrations

```bash
bin\cake migrations migrate
```

### Bước 5: Khởi động web server

**Với Laragon:**
- Khởi động Laragon
- Truy cập: http://mneylink-main.test

**Với PHP built-in server:**
```bash
bin\cake server -p 8080
# Truy cập: http://localhost:8080
```

**Với Apache/Nginx:**
- Cấu hình VirtualHost trỏ về thư mục `webroot`
- Enable mod_rewrite

## 📁 Cấu trúc dự án

```
mneylink-main/
├── bin/              # CLI commands (cake)
├── config/           # Configuration files
│   ├── app.php      # Main config
│   ├── app_local.php # Local config (database, cache)
│   ├── routes.php   # Routes definition
│   └── Migrations/  # Database migrations
├── plugins/         # CakePHP plugins & themes
├── src/
│   ├── Controller/  # Controllers
│   │   ├── Admin/   # Admin controllers
│   │   ├── Auth/    # Authentication
│   │   ├── Member/  # Member area
│   │   └── Buyer/   # Buyer area
│   ├── Model/       # Models (Entity & Table)
│   ├── Template/    # View templates (.ctp)
│   ├── Middleware/  # Custom middlewares
│   └── Application.php
├── tmp/             # Temporary files & cache
├── logs/            # Log files
├── vendor/          # Dependencies
└── webroot/         # Public web root
    └── index.php    # Entry point
```

## 🌐 Routes chính

### Public
- `/` - Trang chủ
- `/auth/signin` - Đăng nhập
- `/auth/signup` - Đăng ký
- `/{alias}` - Link rút gọn (redirect)

### Member (Publisher)
- `/member/dashboard` - Dashboard
- `/member/links` - Quản lý links
- `/member/campaigns` - Quản lý campaigns
- `/member/withdraws` - Rút tiền

### Buyer (Advertiser)
- `/buyer/dashboard` - Dashboard
- `/buyer/traffic-campaigns` - Quản lý campaigns

### Admin
- `/admin/dashboard` - Admin panel
- `/admin/users` - Quản lý users
- `/admin/links` - Quản lý links
- `/admin/options` - Cấu hình

## 🔧 Lệnh hữu ích

```bash
# Chạy server development
bin\cake server -p 8080

# Chạy migrations
bin\cake migrations migrate

# Rollback migration
bin\cake migrations rollback

# Xóa cache
bin\cake cache clear_all

# Chạy scheduled tasks
bin\cake schedule

# Xem routes
bin\cake routes
```

## 🐛 Khắc phục lỗi

### Lỗi: Unable to connect to database

**Giải pháp:**
1. Kiểm tra MySQL đã chạy: `mysql -u root`
2. Kiểm tra cấu hình trong `config/app_local.php`
3. Tạo database: `CREATE DATABASE mneylink;`

### Lỗi: Permission denied on tmp/ or logs/

**Giải pháp Windows:**
```bash
icacls tmp /grant Everyone:(OI)(CI)F /T
icacls logs /grant Everyone:(OI)(CI)F /T
```

### Lỗi: Class not found

**Giải pháp:**
```bash
composer dump-autoload
```

### Lỗi: Redis connection failed

**Giải pháp:**
- Sử dụng File cache thay vì Redis (đã cấu hình sẵn trong app_local.php)
- Hoặc cài đặt Redis và khởi động service

## 📦 Dependencies chính

- **cakephp/cakephp** ^3.10 - Framework chính
- **cakephp/migrations** - Database migrations
- **admad/cakephp-social-auth** - Social login
- **dereuromark/cakephp-queue** - Queue system
- **phpmailer/phpmailer** - Email sending
- **geoip2/geoip2** - GeoIP detection
- **mobiledetect/mobiledetectlib** - Mobile detection

## 🔐 Bảo mật

1. Thay đổi `Security.salt` trong `config/app_local.php`
2. Không commit file `app_local.php` vào Git
3. Tắt debug mode trong production: `'debug' => false`
4. Sử dụng HTTPS trong production

## 📝 License

MIT License

## 👥 Hỗ trợ

Nếu gặp vấn đề, kiểm tra:
1. PHP version: `php -v`
2. MySQL connection: `mysql -u root`
3. Permissions: `tmp/` và `logs/` phải writable
4. Logs: Xem file trong `logs/error.log`

---

**Version:** 6.5.3  
**CakePHP:** 3.10.1



