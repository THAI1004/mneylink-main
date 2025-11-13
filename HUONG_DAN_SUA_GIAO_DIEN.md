# 🎨 HƯỚNG DẪN SỬA GIAO DIỆN - ĐÃ CẬP NHẬT

## ✅ Đã thực hiện:

1. ✅ Cập nhật file `src/Template/Layout/front.ctp` (250 dòng)
2. ✅ Cập nhật file `plugins/CloudTheme/src/Template/Layout/front.ctp` (250 dòng)
3. ✅ Cập nhật file `plugins/ModernTheme/src/Template/Layout/front.ctp` (250 dòng)
4. ✅ Xóa cache CakePHP

---

## 🔧 BƯỚC THỰC HIỆN NGAY:

### Bước 1: RESTART LARAGON/APACHE

**⚠️ QUAN TRỌNG:** Phải restart server!

1. Mở **Laragon**
2. Click nút **Stop All**
3. Đợi 3 giây
4. Click nút **Start All**

**Hoặc** chạy file: `restart_apache.bat`

---

### Bước 2: XÓA CACHE TRÌNH DUYỆT

Mở trình duyệt và:

-   **Chrome/Edge:** Nhấn `Ctrl + Shift + Delete`
    -   Chọn "Cached images and files"
    -   Click "Clear data"
-   **Hoặc** nhấn `Ctrl + F5` để hard refresh

---

### Bước 3: MỞ WEBSITE

```
http://localhost/mneylink-main/
```

---

## 🔍 KIỂM TRA THEME ĐANG DÙNG:

Mở DevTools (F12) và xem source HTML:

-   Tìm `<header id="header"` → Giao diện MỚI ✅
-   Tìm `<nav id="mainNav"` → Giao diện CŨ ❌

---

## 📁 FILE CẦN KIỂM TRA:

Giao diện mới cần các file vendor sau:

### CSS:

-   [ ] `webroot/vendors/bootstrap.min.css`
-   [ ] `webroot/vendors/bootstrap-icons.css`
-   [ ] `webroot/vendors/glightbox.min.css`
-   [ ] `webroot/vendors/swiper-bundle.min.css`
-   [ ] `webroot/assets/css/main.css`

### JS:

-   [ ] `webroot/vendors/glightbox.min.js`
-   [ ] `webroot/vendors/swiper-bundle.min.js`
-   [ ] `webroot/vendors/purecounter_vanilla.js`
-   [ ] `webroot/vendors/imagesloaded.pkgd.min.js`
-   [ ] `webroot/vendors/isotope.pkgd.min.js`
-   [ ] `webroot/assets/js/main.js`

---

## ⚠️ NẾU VẪN KHÔNG THẤY THAY ĐỔI:

### Giải pháp 1: Kiểm tra theme trong database

```sql
SELECT * FROM options WHERE option_name = 'theme';
```

### Giải pháp 2: Xóa cache thủ công

Xóa toàn bộ thư mục:

```
tmp/cache/views/
tmp/cache/persistent/
tmp/cache/models/
```

### Giải pháp 3: Sử dụng Incognito Mode

Mở trình duyệt ở chế độ ẩn danh:

-   Chrome: `Ctrl + Shift + N`
-   Firefox: `Ctrl + Shift + P`

---

## 📞 HỖ TRỢ:

Nếu vẫn không được, check:

1. Console của trình duyệt (F12) → Có lỗi 404 không?
2. File vendor CSS/JS có tồn tại không?
3. Apache/Laragon đã restart chưa?

---

**Ngày cập nhật:** 2024
**Phiên bản:** 1.0
