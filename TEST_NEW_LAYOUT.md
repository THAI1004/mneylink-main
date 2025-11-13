# ✅ KIỂM TRA GIAO DIỆN MỚI

## 🎯 BƯỚC 1: RESTART LARAGON (BẮT BUỘC!)

### Cách 1: Qua Laragon UI

1. Mở **Laragon**
2. Click **Stop All** (nút Stop màu đỏ)
3. Đợi 5 giây
4. Click **Start All** (nút Start màu xanh)

### Cách 2: Qua Command

```cmd
net stop Apache
timeout /t 3
net start Apache
```

---

## 🎯 BƯỚC 2: XÓA CACHE TRÌNH DUYỆT

### Chrome/Edge:

1. Nhấn `F12` (mở DevTools)
2. Click chuột phải vào nút **Refresh** (↻)
3. Chọn **"Empty Cache and Hard Reload"**

### Hoặc:

-   Nhấn `Ctrl + Shift + Delete`
-   Chọn "Cached images and files"
-   Click "Clear data"

### Hoặc đơn giản:

-   Mở **Incognito/Private Window**:
    -   Chrome: `Ctrl + Shift + N`
    -   Firefox: `Ctrl + Shift + P`

---

## 🎯 BƯỚC 3: MỞ WEBSITE VÀ KIỂM TRA

Mở: `http://localhost/mneylink-main/`

### Cách kiểm tra nhanh:

1. Nhấn `F12` (DevTools)
2. Tab **Elements** → Xem source code
3. Tìm trong `<body>` tag:

**Nếu thấy:**

```html
<!-- NEW LAYOUT LOADED - V2.0 -->
```

hoặc

```html
<!-- NEW LAYOUT LOADED - V2.0 - CloudTheme -->
```

hoặc

```html
<!-- NEW LAYOUT LOADED - V2.0 - ModernTheme -->
```

➡️ **THÀNH CÔNG!** ✅ Giao diện mới đã load!

---

## 🔍 KIỂM TRA THÊM:

### Tìm các element mới trong giao diện:

#### Header mới:

```html
<header id="header" class="header d-flex align-items-center fixed-top"></header>
```

#### Footer mới:

```html
<footer id="footer" class="footer position-relative light-background"></footer>
```

#### Scroll Top Button:

```html
<a href="#" id="scroll-top" class="scroll-top"></a>
```

---

## ❌ NẾU VẪN KHÔNG THẤY THAY ĐỔI:

### Kiểm tra Console (F12):

-   Có lỗi **404** cho file CSS/JS không?
-   Có lỗi JavaScript không?

### Kiểm tra Network (F12 → Network):

-   File `main.css` có load không?
-   File `main.js` có load không?
-   Status code là **200** hay **404**?

### Các file cần load thành công:

-   ✅ `/vendors/bootstrap-icons.css`
-   ✅ `/vendors/glightbox.min.css`
-   ✅ `/vendors/swiper-bundle.min.css`
-   ✅ `/assets/css/main.css`
-   ✅ `/assets/js/main.js`

---

## 📝 GHI CHÚ:

**Tất cả file đã được cập nhật:**

1. ✅ src/Template/Layout/front.ctp
2. ✅ plugins/CloudTheme/src/Template/Layout/front.ctp
3. ✅ plugins/ModernTheme/src/Template/Layout/front.ctp

**Tất cả file vendor đã tồn tại:**

-   ✅ webroot/vendors/ (tất cả file CSS/JS)
-   ✅ webroot/assets/ (main.css & main.js)

---

## 🆘 HỖ TRỢ:

Nếu sau khi làm tất cả các bước trên mà vẫn không thấy thay đổi:

1. Chụp màn hình Console (F12) và gửi cho tôi
2. Check xem theme nào đang active trong database:
    ```sql
    SELECT * FROM options WHERE option_name = 'theme';
    ```
3. View Page Source và tìm comment `<!-- NEW LAYOUT LOADED`

---

**Chúc may mắn!** 🚀
