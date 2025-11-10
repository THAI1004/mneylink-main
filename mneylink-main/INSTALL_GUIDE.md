# 🚀 Hướng dẫn cài đặt MneyLink trên Laragon

## ⚠️ YÊU CẦU QUAN TRỌNG

**PHP 7.4 là bắt buộc!** CakePHP 3.10 không hỗ trợ PHP 8.x

## 📝 CÁC BƯỚC CÀI ĐẶT

### Bước 1: Chuyển PHP 7.4 trong Laragon

#### Nếu đã có PHP 7.4:

1. **Click chuột phải** vào icon **Laragon** ở System Tray
2. Chọn **Menu** → **PHP** → **php-7.4.x**
3. **Chọn "Preferences"** → **Restart** Laragon

#### Nếu chưa có PHP 7.4:

**Cách 1: Download từ Laragon (Dễ nhất)**

1. Click chuột phải **Laragon** → **PHP** → **Quick Add** → **php-7.4**
2. Laragon sẽ tự động download
3. Sau khi download xong, chọn PHP 7.4 như trên

**Cách 2: Download thủ công**

1. Download PHP 7.4.33 Thread Safe x64:
   https://windows.php.net/downloads/releases/archives/php-7.4.33-Win32-vc15-x64.zip

2. Giải nén vào: `C:\laragon\bin\php\php-7.4.33`

3. Copy file cấu hình:

    ```
    Copy: C:\laragon\bin\php\php-7.4.33\php.ini-development
    To:   C:\laragon\bin\php\php-7.4.33\php.ini
    ```

4. Enable extensions cần thiết trong `php.ini`:

    ```ini
    extension=curl
    extension=fileinfo
    extension=gd2
    extension=intl
    extension=mbstring
    extension=mysqli
    extension=pdo_mysql
    extension=openssl
    ```

5. **Restart Laragon** và chọn PHP 7.4

### Bước 2: Kiểm tra PHP version

Mở **Terminal** trong Laragon hoặc CMD:

```bash
cd c:\laragon\www\mneylink-main\mneylink-main
php -v
```

**Kết quả phải là:**

```
PHP 7.4.x (cli) ...
```

### Bước 3: Chạy script tự động

```bash
run.bat
```

Script sẽ tự động:

-   ✅ Kiểm tra PHP version
-   ✅ Tạo thư mục tmp & logs
-   ✅ Tạo database
-   ✅ Chạy migrations
-   ✅ Khởi động server

### Bước 4: Truy cập ứng dụng

Mở trình duyệt:

-   http://localhost:8080

**Hoặc cấu hình Laragon VirtualHost:**

-   http://mneylink-main.test

---

## 🔧 SETUP THỦ CÔNG (Nếu script lỗi)

### 1. Tạo database

```bash
mysql -u root -e "CREATE DATABASE mneylink CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

### 2. Cấu hình database (nếu cần)

Mở file `config/app_local.php` và chỉnh sửa:

```php
'Datasources' => [
    'default' => [
        'username' => 'root',
        'password' => '',  // Thay đổi nếu có mật khẩu
        'database' => 'mneylink',
    ],
],
```

### 3. Tạo thư mục

```bash
mkdir tmp\cache\models
mkdir tmp\cache\persistent
mkdir tmp\cache\views
mkdir tmp\sessions
mkdir tmp\tests
mkdir logs
```

### 4. Phân quyền (Windows)

```bash
icacls tmp /grant Everyone:(OI)(CI)F /T
icacls logs /grant Everyone:(OI)(CI)F /T
```

### 5. Chạy migrations

```bash
bin\cake migrations migrate
```

### 6. Khởi động server

```bash
bin\cake server -p 8080
```

---

## ❌ KHẮC PHỤC LỖI

### Lỗi: "Your requirements could not be resolved"

**Nguyên nhân:** Đang dùng PHP 8.x thay vì PHP 7.4

**Giải pháp:**

-   Chuyển sang PHP 7.4 trong Laragon (xem Bước 1)
-   Kiểm tra: `php -v`

### Lỗi: "Class 'Cake\...' not found"

**Giải pháp:**

```bash
composer dump-autoload
```

### Lỗi: "Unable to connect to database"

**Giải pháp:**

1. Kiểm tra MySQL đã chạy trong Laragon
2. Kiểm tra username/password trong `config/app_local.php`
3. Tạo database: `CREATE DATABASE mneylink;`

### Lỗi: "Permission denied: tmp/ or logs/"

**Giải pháp:**

```bash
icacls tmp /grant Everyone:(OI)(CI)F /T
icacls logs /grant Everyone:(OI)(CI)F /T
```

### Lỗi: "Call to undefined function intl_get_error_code()"

**Giải pháp:**

1. Mở file: `C:\laragon\bin\php\php-7.4.33\php.ini`
2. Tìm và uncomment: `extension=intl`
3. Restart Laragon

---

## 📦 KHÔNG CẦN COMPOSER INSTALL

**Lưu ý:** Thư mục `vendor/` đã có sẵn tất cả dependencies.

❌ **KHÔNG CẦN** chạy `composer install`

✅ Chỉ cần chuyển PHP 7.4 và chạy `run.bat`

---

## 🎯 TÓM TẮT NHANH

```bash
# 1. Chuyển PHP 7.4 trong Laragon (quan trọng nhất!)
# 2. Mở Terminal:
cd c:\laragon\www\mneylink-main\mneylink-main
php -v  # Phải là 7.4.x

# 3. Chạy script:
run.bat

# 4. Truy cập:
http://localhost:8080
```

---

## 📞 SUPPORT

Nếu vẫn gặp lỗi:

1. Kiểm tra `logs/error.log`
2. Kiểm tra `logs/debug.log`
3. Đảm bảo PHP 7.4 đang được sử dụng: `php -v`


