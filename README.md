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
├── src/
│   ├── mu-plugins/
│   │   └── digital-store.php   ← MU-Plugin: PayPal, download email, security
│   └── themes/
│       └── digital-store/      ← Child theme của Storefront
│           ├── style.css
│           └── functions.php
```

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

### Bước 2 — Vào WordPress Admin, cài Plugins & Theme

```
https://your-domain.com/wp-admin
```

1. **Plugins → Add New** → Cài & kích hoạt **WooCommerce**
2. **Plugins → Add New** → Cài & kích hoạt **WooCommerce PayPal Payments**
3. **Appearance → Themes → Add New** → Cài **Storefront** (theme cha, không cần activate)

---

### Bước 3 — Upload Theme & MU-Plugin

#### Option A: Script tự động (SSH)

Mở `deploy-cyberpanel.sh`, sửa các biến cấu hình:

```bash
SERVER_IP="your.server.ip"
SERVER_USER="root"
DOMAIN="your-domain.com"
```

Sau đó chạy:

```bash
chmod +x deploy-cyberpanel.sh
./deploy-cyberpanel.sh
```

#### Option B: Thủ công qua CyberPanel File Manager

Vào **File Manager → /home/your-domain.com/public_html/**:

- Upload `src/themes/digital-store/` → vào `wp-content/themes/`
- Tạo thư mục `wp-content/mu-plugins/` (nếu chưa có)
- Upload `src/mu-plugins/digital-store.php` → vào `wp-content/mu-plugins/`

#### Option C: SCP trực tiếp

```bash
# Theme
scp -r src/themes/digital-store root@YOUR_IP:/home/your-domain.com/public_html/wp-content/themes/

# MU-Plugin
ssh root@YOUR_IP "mkdir -p /home/your-domain.com/public_html/wp-content/mu-plugins"
scp src/mu-plugins/digital-store.php root@YOUR_IP:/home/your-domain.com/public_html/wp-content/mu-plugins/
```

---

### Bước 4 — Kích hoạt Theme

**Appearance → Themes** → Kích hoạt **Digital Store** (Storefront child)

---

### Bước 5 — Cấu hình WooCommerce

**WooCommerce → Settings**:

| Tab | Setting | Giá trị |
|-----|---------|---------|
| Products | Downloadable products | ✅ Grant access after payment |
| Products | File download method | **Force Download** |
| Payments | PayPal Payments | Cấu hình Sandbox / Live API keys |

---

### Bước 6 — Cấu hình PayPal

**WooCommerce → Settings → Payments → PayPal Payments**:

1. Lấy API keys tại [developer.paypal.com](https://developer.paypal.com) → My Apps & Credentials
2. Sandbox: bật Sandbox mode, điền Sandbox Client ID & Secret
3. Live: tắt Sandbox, điền Live Client ID & Secret

---

## ✨ Tính năng tích hợp sẵn (MU-Plugin)

Plugin `digital-store.php` load tự động, không cần kích hoạt:

| Tính năng | Mô tả |
|---|---|
| 🚫 Ẩn Shipping | Bỏ tab/bước shipping cho sản phẩm số |
| 💳 PayPal Only | Chỉ hiện PayPal ở checkout |
| 📧 Download Email | Gửi email link download khi đơn hoàn thành |
| 🔒 Force Download | Serve file qua PHP, không expose URL thẳng |
| ⚠️ Admin Notice | Nhắc cài PayPal Payments plugin nếu chưa có |

---

## 🔧 Các lệnh quản lý thường dùng (WP-CLI)

> Chạy trên server qua SSH hoặc CyberPanel Terminal.

```bash
# Vào thư mục WordPress
cd /home/your-domain.com/public_html

# Cập nhật tất cả plugin
wp plugin update --all

# Xóa cache
wp cache flush

# Kiểm tra trạng thái site
wp core version
wp plugin list
```

---

## 🐛 Xử lý sự cố

| Vấn đề | Nguyên nhân | Giải pháp |
|---|---|---|
| Theme không hiện | Storefront chưa cài | Cài Storefront (không cần activate) |
| MU-Plugin không chạy | Sai thư mục | Phải ở `wp-content/mu-plugins/` (không trong subfolder) |
| PayPal không hiện | Plugin chưa cài | Cài WooCommerce PayPal Payments |
| Upload lỗi | PHP upload limit | CyberPanel → PHP → tăng `upload_max_filesize` |
| Download link hỏng | Permalink chưa set | Settings → Permalinks → Save |
