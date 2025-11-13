# Hướng Dẫn Sửa Lỗi Database

## Vấn đề

Khi đăng nhập vào dashboard của member, gặp lỗi:

```
An Internal Error Has Occurred
Error: SQLSTATE[42S22]: Column not found: 1054 Unknown column 'Traffics.device' in 'field list'
```

## Nguyên nhân

Database thiếu:

1. Bảng `buyer_reports`
2. Bảng `member_reports`
3. Cột `device` trong bảng `traffics`

## Cách sửa

### Phương pháp 1: Sử dụng Batch Script (Khuyến nghị)

1. Đảm bảo MySQL/MariaDB đang chạy trong Laragon
2. Chạy file `fix_database.bat`
3. Script sẽ tự động:
    - Tạo bảng `buyer_reports`
    - Tạo bảng `member_reports`
    - Thêm cột `device` vào bảng `traffics`

### Phương pháp 2: Chạy SQL thủ công

#### Sử dụng phpMyAdmin:

1. Mở Laragon
2. Click vào "Database" để mở phpMyAdmin
3. Chọn database `mneylink`
4. Vào tab "SQL"
5. Copy và paste nội dung từ file `create_missing_tables.sql`
6. Click "Go" để chạy

#### Sử dụng MySQL Command Line:

```bash
cd C:\laragon\www\mneylink-main\mneylink-main
mysql -h localhost -P 3307 -u root mneylink < create_missing_tables.sql
```

### Phương pháp 3: Sử dụng CakePHP Migrations

```bash
cd C:\laragon\www\mneylink-main\mneylink-main
bin\cake migrations migrate
```

## Cấu trúc các bảng được tạo

### Bảng: buyer_reports

Lưu trữ các báo cáo từ người mua quảng cáo về việc chỉnh sửa traffic.

| Cột        | Kiểu             | Mô tả                                   |
| ---------- | ---------------- | --------------------------------------- |
| id         | int(10) unsigned | Primary key                             |
| traffic_id | int(10) unsigned | ID của traffic                          |
| user_id    | int(10) unsigned | ID của user                             |
| content    | text             | Nội dung báo cáo (JSON)                 |
| status     | tinyint(1)       | Trạng thái (0: chưa xử lý, 1: đã xử lý) |
| date       | datetime         | Ngày tạo báo cáo                        |
| created    | datetime         | Timestamp tạo                           |
| modified   | datetime         | Timestamp sửa đổi                       |

### Bảng: member_reports

Lưu trữ các báo cáo từ member về các vấn đề với traffic.

| Cột        | Kiểu             | Mô tả                                   |
| ---------- | ---------------- | --------------------------------------- |
| id         | int(10) unsigned | Primary key                             |
| traffic_id | int(10) unsigned | ID của traffic                          |
| ip         | varchar(100)     | IP của member                           |
| content    | text             | Nội dung báo cáo (JSON)                 |
| status     | tinyint(1)       | Trạng thái (0: chưa xử lý, 1: đã xử lý) |
| date       | datetime         | Ngày tạo báo cáo                        |
| created    | datetime         | Timestamp tạo                           |
| modified   | datetime         | Timestamp sửa đổi                       |

### Cột mới: traffics.device

Xác định loại thiết bị cho traffic.

| Giá trị | Mô tả                              |
| ------- | ---------------------------------- |
| 0       | Tất cả thiết bị (Desktop + Mobile) |
| 1       | Chỉ Desktop                        |
| 2       | Chỉ Mobile                         |

## Kiểm tra sau khi sửa

1. Đăng nhập lại vào member dashboard
2. Kiểm tra xem còn lỗi không
3. Nếu vẫn còn lỗi, xem log tại: `logs/error.log`

## Lưu ý

-   Backup database trước khi chạy script (khuyến nghị)
-   Script sử dụng `IF NOT EXISTS` và `ADD COLUMN IF NOT EXISTS` nên an toàn khi chạy nhiều lần
-   Nếu vẫn gặp lỗi, kiểm tra file `logs/error.log` để xem thông báo lỗi chi tiết

## Liên hệ

Nếu gặp vấn đề, kiểm tra:

1. MySQL/MariaDB có đang chạy không?
2. Thông tin kết nối database trong `config/app_local.php` có đúng không?
3. Database `mneylink` có tồn tại không?

