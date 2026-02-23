# 🛒 WooCommerce Digital Store — Docker Stack

> WordPress + WooCommerce chạy trên Nginx · PHP-FPM 8.2 · MariaDB 10.11 · Redis 7

---

## 📋 Yêu cầu hệ thống

| Phần mềm | Phiên bản tối thiểu |
|---|---|
| Docker | 24.x+ |
| Docker Compose | v2.x+ |
| RAM | 2 GB (khuyến nghị 4 GB) |
| Disk | 10 GB trống |

---

## 🗂 Cấu trúc thư mục

```
ecommerce-themes/
├── docker-compose.yml
├── .env.example                 ← Template biến môi trường
├── .gitignore
├── nginx/
│   ├── nginx.conf               ← HTTP core (gzip, cache, rate-limit)
│   ├── conf.d/
│   │   └── wordpress.conf       ← Server block: SEO URLs + bảo mật
│   └── ssl/                     ← Đặt cert SSL vào đây (tự tạo)
└── docker/
    ├── php/
    │   ├── php.ini              ← Upload 256M · Memory 512M · OPcache
    │   └── www.conf             ← PHP-FPM pool (dynamic, 20 workers)
    ├── mariadb/
    │   └── conf.d/wordpress.cnf ← InnoDB tuning · utf8mb4
    └── wordpress/
        ├── Dockerfile           ← PHP 8.2-FPM + imagick, redis, gd, intl…
        └── docker-entrypoint-wpcli.sh ← Auto-installer WP + WooCommerce
```

---

## 🚀 Hướng dẫn chạy lần đầu (Local / Development)

### Bước 1 — Tạo file `.env`

```bash
cp .env.example .env
```

Mở `.env` và chỉnh các giá trị:

```dotenv
# Database
DB_ROOT_PASSWORD=your_strong_root_password
DB_NAME=wordpress
DB_USER=wp_user
DB_PASSWORD=your_strong_db_password

# Redis
REDIS_PASSWORD=your_strong_redis_password

# WordPress
WP_SITEURL=http://localhost          # Đổi thành domain thật khi production
WP_TITLE=My Digital Store
WP_ADMIN_USER=admin
WP_ADMIN_PASSWORD=your_admin_password
WP_ADMIN_EMAIL=admin@example.com
```

> ⚠️ **Sinh Security Keys** tại [https://api.wordpress.org/secret-key/1.1/salt/](https://api.wordpress.org/secret-key/1.1/salt/) và điền vào các trường `WP_AUTH_KEY`, `WP_SECURE_AUTH_KEY`, … trong `.env`.

---

### Bước 2 — Build và khởi động stack

```bash
docker compose up -d --build
```

Kiểm tra các container đang chạy:

```bash
docker compose ps
```

Kết quả mong đợi:

```
NAME          STATUS          PORTS
woo_db        Up (healthy)    3306/tcp
woo_redis     Up (healthy)    6379/tcp
woo_php       Up (healthy)    9000/tcp
woo_nginx     Up              0.0.0.0:80->80/tcp
```

---

### Bước 3 — Cài đặt WordPress & WooCommerce tự động

```bash
docker compose run --rm wpcli
```

Script sẽ tự động thực hiện:
- ✅ Cài WordPress core
- ✅ Cài & kích hoạt **WooCommerce**
- ✅ Cài & kích hoạt **Redis Object Cache**
- ✅ Cấu hình permalink SEO-friendly (`/%postname%/`)
- ✅ Cấu hình WooCommerce cho sản phẩm số (digital downloads)
- ✅ Bảo mật thư mục `woocommerce_uploads/`

Sau khi hoàn thành, truy cập:
- **Trang chủ:** `http://localhost`
- **Admin:** `http://localhost/wp-admin`

---

## 🌐 Triển khai Production

### 1. Cập nhật `.env`

```dotenv
WP_SITEURL=https://yourdomain.com
WP_DEBUG=0
```

### 2. Thêm SSL certificate

Đặt file cert vào `nginx/ssl/`:

```
nginx/ssl/
├── fullchain.pem
└── privkey.pem
```

Thêm server block HTTPS vào `nginx/conf.d/wordpress.conf`:

```nginx
server {
    listen 443 ssl http2;
    server_name yourdomain.com;

    ssl_certificate     /etc/nginx/ssl/fullchain.pem;
    ssl_certificate_key /etc/nginx/ssl/privkey.pem;
    ssl_protocols       TLSv1.2 TLSv1.3;
    ssl_ciphers         HIGH:!aNULL:!MD5;

    # ... (copy toàn bộ nội dung server block HTTP hiện tại vào đây)
}

# Redirect HTTP → HTTPS
server {
    listen 80;
    server_name yourdomain.com;
    return 301 https://$host$request_uri;
}
```

> 💡 **Khuyến nghị:** Dùng [Certbot](https://certbot.eff.org/) hoặc [Traefik](https://traefik.io/) để tự động gia hạn Let's Encrypt.

### 3. Cập nhật HTTPS_PORT trong `.env`

```dotenv
HTTP_PORT=80
HTTPS_PORT=443
```

### 4. Khởi động lại

```bash
docker compose down && docker compose up -d --build
docker compose run --rm wpcli
```

---

## 🔧 Các lệnh quản lý thường dùng

### Xem log

```bash
# Tất cả services
docker compose logs -f

# Chỉ Nginx
docker compose logs -f nginx

# Chỉ PHP-FPM
docker compose logs -f php
```

### Vào shell container

```bash
# PHP container
docker compose exec php bash

# MariaDB shell
docker compose exec db mariadb -u wp_user -p wordpress

# Redis CLI
docker compose exec redis redis-cli -a $REDIS_PASSWORD
```

### Backup database

```bash
docker compose exec db mariadb-dump \
  -u wp_user -p"$DB_PASSWORD" wordpress \
  > backup_$(date +%Y%m%d_%H%M%S).sql
```

### Restore database

```bash
docker compose exec -T db mariadb \
  -u wp_user -p"$DB_PASSWORD" wordpress \
  < backup_20240101_120000.sql
```

### WP-CLI thủ công

```bash
# Cập nhật tất cả plugin
docker compose exec php wp plugin update --all --allow-root

# Xóa cache Redis
docker compose exec php wp redis flush --allow-root

# Kiểm tra trạng thái site
docker compose exec php wp core version --allow-root
```

### Dừng và xóa stack (giữ nguyên data)

```bash
docker compose down
```

### Xóa toàn bộ (bao gồm volumes/data)

```bash
docker compose down -v
```

---

## ⚙️ Tùy chỉnh PHP

Chỉnh sửa `docker/php/php.ini` và khởi động lại:

```bash
docker compose restart php
```

Các giá trị mặc định đã cấu hình:

| Tham số | Giá trị | Mục đích |
|---|---|---|
| `upload_max_filesize` | `256M` | Upload sản phẩm số lớn |
| `post_max_size` | `256M` | POST body size |
| `memory_limit` | `512M` | Xử lý ảnh, PDF... |
| `max_execution_time` | `300s` | Upload file lớn |
| `opcache.memory_consumption` | `256MB` | PHP bytecode cache |

---

## 🔒 Bảo mật đã tích hợp

- ✅ Block thực thi PHP trong `/uploads/` (ngăn upload malware)
- ✅ Block trực tiếp `wp-config.php`, `xmlrpc.php`, `/wp-includes/*.php`
- ✅ Rate-limit WP-Login: **5 request/phút**
- ✅ Rate-limit REST API: **30 request/giây**
- ✅ WooCommerce uploads dùng `internal` (chỉ serve qua WordPress, không thể truy cập thẳng)
- ✅ Security headers: `X-Frame-Options`, `X-Content-Type-Options`, `Referrer-Policy`
- ✅ Hidden files (`.env`, `.git`) bị block
- ✅ Redis password-protected

---

## 🐛 Xử lý sự cố

| Vấn đề | Nguyên nhân | Giải pháp |
|---|---|---|
| Container không start | Port 80 bị chiếm | `HTTP_PORT=8080` trong `.env` |
| Upload lỗi 413 | `client_max_body_size` Nginx | Đã set `256M` trong `nginx.conf` |
| PHP timeout | `max_execution_time` thấp | Tăng trong `php.ini` |
| Redis kết nối thất bại | Sai password | Kiểm tra `REDIS_PASSWORD` trong `.env` |
| WooCommerce downloads lỗi | Quyền thư mục | `docker compose exec php chown -R www-data:www-data /var/www/html/wp-content/uploads` |
