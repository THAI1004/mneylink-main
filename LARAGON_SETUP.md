# Setup mneylink với Laragon Apache

## Cách 1: Tự động (Khuyến nghị)

1. Copy toàn bộ folder `mneylink-main` vào thư mục `C:\laragon\www\`
2. Mở Laragon
3. Click chuột phải vào Laragon tray icon → **Apache** → **Virtual Hosts** → **Auto create virtual hosts**
4. Laragon sẽ tự động tạo virtual host: `http://mneylink-main.test`
5. Restart Apache trong Laragon

## Cách 2: Thủ công

Nếu cách 1 không hoạt động:

1. Mở Laragon → Menu → Apache → Virtual Hosts → **mneylink-main.conf**

2. Thêm cấu hình sau:

```apache
<VirtualHost *:80>
    DocumentRoot "C:/laragon/www/mneylink-main/mneylink-main/webroot"
    ServerName mneylink.test
    ServerAlias *.mneylink.test
    
    <Directory "C:/laragon/www/mneylink-main/mneylink-main/webroot">
        Options Indexes FollowSymLinks MultiViews
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

3. Mở file `C:\Windows\System32\drivers\etc\hosts` (as Administrator)

4. Thêm dòng:
```
127.0.0.1    mneylink.test
```

5. Restart Apache trong Laragon

## Truy cập

- Website: `http://mneylink.test` hoặc `http://mneylink-main.test`
- Debug tool: `http://mneylink.test/debug/auth`

## Lưu ý

- Đảm bảo Apache đang chạy trong Laragon
- Nếu dùng port khác (không phải 80), cần thêm port vào URL: `http://mneylink.test:8080`

