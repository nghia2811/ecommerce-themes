# 🛒 WooCommerce Digital Store — CyberPanel Deployment

> WordPress + WooCommerce child theme tối ưu cho sản phẩm số (digital downloads).
> Triển khai trên CyberPanel với OpenLiteSpeed + SSL tự động.

---

## 📋 Yêu cầu hệ thống

| Phần mềm | Phiên bản tối thiểu |
|---|---|
| CyberPanel | 2.x+ |
| PHP | 8.1+ |
| WordPress | 6.4+ |
| WooCommerce | 8.x+ |
| Theme cha | Storefront (bắt buộc) |

---

## 🗂 Cấu trúc thư mục

```
ecommerce-themes/
├── deploy-cyberpanel.sh         ← Script deploy tự động qua SSH
├── .env.example                 ← Template biến môi trường
├── scripts/
│   └── woo-digital-setup.sh    ← WP-CLI script cấu hình WooCommerce
└── src/
    ├── style.css               ← Theme stylesheet (Storefront child)
    ├── functions.php           ← Theme functions
    ├── screenshot-readme.php   ← Theme screenshot placeholder
    └── digital-store.php       ← MU-Plugin: PayPal, download email, security
```

**Khi deploy lên server:**
- `style.css`, `functions.php`, `screenshot-readme.php` → `wp-content/themes/digital-store/`
- `digital-store.php` → `wp-content/mu-plugins/`

---

## 🚀 Hướng dẫn triển khai lên CyberPanel

### Bước 1 — Cài WordPress qua CyberPanel

Trong **CyberPanel → Websites → your-domain.com → WordPress** → **Install WordPress**:

| Field | Giá trị |
|-------|---------|
| Blog Title | Tên store của bạn |
| Login User | admin username |
| Password | mật khẩu mạnh |
| Email | email admin |

> ✅ CyberPanel tự động cấp SSL Let's Encrypt.

---

### Bước 2 — Vào WordPress Admin, cài Plugins & Theme cha

```
https://your-domain.com/wp-admin
```

1. **Plugins → Add New** → Cài & kích hoạt **WooCommerce**
2. **Plugins → Add New** → Cài & kích hoạt **WooCommerce PayPal Payments**
3. **Appearance → Themes → Add New** → Cài **Storefront** (theme cha, không cần activate)

---

### Bước 3 — Upload Theme & MU-Plugin

#### Option A: Script tự động (SSH)

Sửa các biến cấu hình trong `deploy-cyberpanel.sh`:

```bash
SERVER_IP="your.server.ip"
SERVER_USER="root"
DOMAIN="your-domain.com"
```

Chạy:

```bash
chmod +x deploy-cyberpanel.sh
./deploy-cyberpanel.sh
```

#### Option B: Upload thủ công qua CyberPanel File Manager

1. Vào **File Manager → /home/your-domain.com/public_html/wp-content/**
2. Tạo thư mục `themes/digital-store/`
3. Upload `src/style.css`, `src/functions.php`, `src/screenshot-readme.php` vào `themes/digital-store/`
4. Tạo thư mục `mu-plugins/` (nếu chưa có)
5. Upload `src/digital-store.php` vào `mu-plugins/`

#### Option C: SCP trực tiếp

```bash
# Tạo thư mục theme
ssh root@YOUR_IP "mkdir -p /home/your-domain.com/public_html/wp-content/themes/digital-store"

# Upload theme files
scp src/style.css src/functions.php src/screenshot-readme.php \
    root@YOUR_IP:/home/your-domain.com/public_html/wp-content/themes/digital-store/

# Upload MU-Plugin
ssh root@YOUR_IP "mkdir -p /home/your-domain.com/public_html/wp-content/mu-plugins"
scp src/digital-store.php \
    root@YOUR_IP:/home/your-domain.com/public_html/wp-content/mu-plugins/
```

---

### Bước 4 — Kích hoạt Theme

**Appearance → Themes** → Kích hoạt **Digital Store**

---

### Bước 5 — Cấu hình WooCommerce (tùy chọn: dùng script)

SSH vào server và chạy WP-CLI script:

```bash
cd /home/your-domain.com/public_html
bash /path/to/scripts/woo-digital-setup.sh
```

Hoặc cấu hình thủ công trong **WooCommerce → Settings**:

| Tab | Setting | Giá trị |
|-----|---------|---------|
| Products | Grant access after payment | ✅ |
| Products | File download method | **Force Download** |
| Payments | PayPal Payments | Cấu hình API keys |

---

## ✨ Tính năng tích hợp sẵn (MU-Plugin)

Plugin `digital-store.php` load tự động, không cần kích hoạt:

- 🚫 Ẩn tab/bước **Shipping** cho sản phẩm số
- 💳 Chỉ hiện **PayPal** ở checkout
- 📧 Gửi email download links khi đơn hoàn thành
- 🔒 Force download (không expose URL file trực tiếp)
- ⚠️ Nhắc admin cài PayPal Payments plugin nếu chưa có

---

## 🐛 Xử lý sự cố

| Vấn đề | Nguyên nhân | Giải pháp |
|---|---|---|
| Theme không hiện | Storefront chưa cài | Cài Storefront (không cần activate) |
| Missing stylesheet | File chưa đúng thư mục | `style.css` phải ở `themes/digital-store/` |
| MU-Plugin không chạy | Sai thư mục | Phải ở `wp-content/mu-plugins/` (không trong subfolder) |
| PayPal không hiện | Plugin chưa cài | Cài WooCommerce PayPal Payments |
| Upload lỗi | PHP upload limit | CyberPanel → PHP → tăng `upload_max_filesize` |
