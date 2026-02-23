#!/usr/bin/env bash
# ============================================================
# deploy-cyberpanel.sh
# Upload Digital Store theme + MU-Plugin lên CyberPanel server
#
# Cách dùng:
#   chmod +x deploy-cyberpanel.sh
#   ./deploy-cyberpanel.sh
# ============================================================

set -e

# ── CẤU HÌNH — Sửa các giá trị này ──────────────────────────
SERVER_IP="212.56.45.225"
SERVER_USER="root"          # Hoặc user SSH của bạn
DOMAIN="register-global.com"
WP_PATH="/home/${DOMAIN}/public_html"   # Đường dẫn WordPress trên CyberPanel
# ─────────────────────────────────────────────────────────────

THEME_NAME="digital-store"
SRC_DIR="./src"

echo "🚀 Bắt đầu deploy lên ${SERVER_IP} (${DOMAIN})..."

# 1. Tạo thư mục theme trên server
echo ""
echo "📦 Tạo thư mục theme và upload files..."
ssh "${SERVER_USER}@${SERVER_IP}" "mkdir -p ${WP_PATH}/wp-content/themes/${THEME_NAME}"
ssh "${SERVER_USER}@${SERVER_IP}" "mkdir -p ${WP_PATH}/wp-content/mu-plugins"

# 2. Upload theme files (style.css, functions.php, screenshot-readme.php)
scp "${SRC_DIR}/style.css" "${SERVER_USER}@${SERVER_IP}:${WP_PATH}/wp-content/themes/${THEME_NAME}/"
scp "${SRC_DIR}/functions.php" "${SERVER_USER}@${SERVER_IP}:${WP_PATH}/wp-content/themes/${THEME_NAME}/"
scp "${SRC_DIR}/screenshot-readme.php" "${SERVER_USER}@${SERVER_IP}:${WP_PATH}/wp-content/themes/${THEME_NAME}/"

echo "   ✅ Theme files uploaded."

# 3. Upload MU-Plugin
echo ""
echo "🔌 Upload MU-Plugin digital-store.php..."
scp "${SRC_DIR}/digital-store.php" "${SERVER_USER}@${SERVER_IP}:${WP_PATH}/wp-content/mu-plugins/"

echo "   ✅ MU-Plugin uploaded."

# 4. Fix quyền thư mục
echo ""
echo "🔒 Cấp quyền đúng cho files..."
ssh "${SERVER_USER}@${SERVER_IP}" bash <<EOF
  chown -R nobody:nogroup ${WP_PATH}/wp-content/themes/${THEME_NAME} 2>/dev/null || \
  chown -R www-data:www-data ${WP_PATH}/wp-content/themes/${THEME_NAME} 2>/dev/null || true

  chown -R nobody:nogroup ${WP_PATH}/wp-content/mu-plugins/ 2>/dev/null || \
  chown -R www-data:www-data ${WP_PATH}/wp-content/mu-plugins/ 2>/dev/null || true

  find ${WP_PATH}/wp-content/themes/${THEME_NAME} -type f -exec chmod 644 {} \;
  find ${WP_PATH}/wp-content/themes/${THEME_NAME} -type d -exec chmod 755 {} \;
  find ${WP_PATH}/wp-content/mu-plugins -type f -exec chmod 644 {} \;

  echo "✅ Quyền đã được cấp."
EOF

echo ""
echo "✅ Deploy hoàn tất!"
echo ""
echo "Bước tiếp theo trong WordPress Admin (${WP_PATH}):"
echo "  1. Appearance → Themes → Kích hoạt 'Digital Store'"
echo "  2. Plugins → Cài WooCommerce → Kích hoạt"
echo "  3. WooCommerce → Settings → Payments → Cấu hình PayPal"
echo "  4. URL: https://${DOMAIN}/wp-admin"
