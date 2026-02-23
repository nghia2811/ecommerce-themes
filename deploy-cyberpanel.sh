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

THEME_LOCAL="./src/themes/digital-store"
MUPLUGIN_LOCAL="./src/mu-plugins/digital-store.php"

echo "🚀 Bắt đầu deploy lên ${SERVER_IP} (${DOMAIN})..."

# 1. Upload child theme
echo ""
echo "📦 Upload theme digital-store..."
scp -r "${THEME_LOCAL}" "${SERVER_USER}@${SERVER_IP}:${WP_PATH}/wp-content/themes/"

# 2. Upload MU-Plugin
echo ""
echo "🔌 Upload MU-Plugin digital-store.php..."
ssh "${SERVER_USER}@${SERVER_IP}" "mkdir -p ${WP_PATH}/wp-content/mu-plugins"
scp "${MUPLUGIN_LOCAL}" "${SERVER_USER}@${SERVER_IP}:${WP_PATH}/wp-content/mu-plugins/"

# 3. Fix quyền thư mục
echo ""
echo "🔒 Cấp quyền đúng cho files..."
ssh "${SERVER_USER}@${SERVER_IP}" bash <<EOF
  chown -R nobody:nogroup ${WP_PATH}/wp-content/themes/digital-store 2>/dev/null || \
  chown -R www-data:www-data ${WP_PATH}/wp-content/themes/digital-store 2>/dev/null || true

  chown -R nobody:nogroup ${WP_PATH}/wp-content/mu-plugins/ 2>/dev/null || \
  chown -R www-data:www-data ${WP_PATH}/wp-content/mu-plugins/ 2>/dev/null || true

  find ${WP_PATH}/wp-content/themes/digital-store -type f -exec chmod 644 {} \;
  find ${WP_PATH}/wp-content/themes/digital-store -type d -exec chmod 755 {} \;
  find ${WP_PATH}/wp-content/mu-plugins -type f -exec chmod 644 {} \;

  echo "✅ Quyền đã được cấp."
EOF

echo ""
echo "✅ Deploy hoàn tất!"
echo ""
echo "Bước tiếp theo trong WordPress Admin:"
echo "  1. Appearance → Themes → Kích hoạt 'Digital Store'"
echo "  2. Plugins → Cài WooCommerce → Kích hoạt"
echo "  3. WooCommerce → Settings → Payments → Cấu hình PayPal"
echo "  4. URL: https://${DOMAIN}/wp-admin"
