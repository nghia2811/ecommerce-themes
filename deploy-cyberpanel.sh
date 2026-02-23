#!/usr/bin/env bash
# ============================================================
# deploy-cyberpanel.sh
# Upload Digital Store theme + MU-Plugin lên CyberPanel server
#
# Cách dùng:
#   chmod +x deploy-cyberpanel.sh
#   ./deploy-cyberpanel.sh
#
# Yêu cầu: sshpass
#   Ubuntu/Debian : sudo apt install sshpass
#   macOS         : brew install hudochenkov/sshpass/sshpass
#   Windows (WSL) : sudo apt install sshpass
# ============================================================

set -e

# ── CẤU HÌNH ─────────────────────────────────────────────────
SERVER_IP="212.56.45.225"
SERVER_USER="admin"
SERVER_PASS="A1EOOtG6XnH5qoHq"
DOMAIN="register-global.com"
WP_PATH="/home/${DOMAIN}/public_html"
# ─────────────────────────────────────────────────────────────

THEME_LOCAL="./src/themes/digital-store"
MUPLUGIN_LOCAL="./src/mu-plugins/digital-store.php"

# SSH/SCP wrapper dùng sshpass + tắt host key prompt
SSH_OPTS="-o StrictHostKeyChecking=no -o UserKnownHostsFile=/dev/null"
SSHPASS="sshpass -p ${SERVER_PASS}"

# ── Kiểm tra sshpass đã cài chưa ─────────────────────────────
if ! command -v sshpass &>/dev/null; then
  echo "❌ Thiếu sshpass. Cài bằng:"
  echo "   Ubuntu/Debian : sudo apt install sshpass"
  echo "   macOS         : brew install hudochenkov/sshpass/sshpass"
  exit 1
fi

echo "🚀 Bắt đầu deploy lên ${SERVER_IP} (${DOMAIN})..."
echo "   User: ${SERVER_USER}"
echo ""

# 1. Upload child theme
echo "📦 Upload theme digital-store..."
${SSHPASS} scp ${SSH_OPTS} -r "${THEME_LOCAL}" \
  "${SERVER_USER}@${SERVER_IP}:${WP_PATH}/wp-content/themes/"

# 2. Upload MU-Plugin
echo ""
echo "🔌 Upload MU-Plugin digital-store.php..."
${SSHPASS} ssh ${SSH_OPTS} "${SERVER_USER}@${SERVER_IP}" \
  "mkdir -p ${WP_PATH}/wp-content/mu-plugins"
${SSHPASS} scp ${SSH_OPTS} "${MUPLUGIN_LOCAL}" \
  "${SERVER_USER}@${SERVER_IP}:${WP_PATH}/wp-content/mu-plugins/"

# 3. Fix quyền thư mục
echo ""
echo "🔒 Cấp quyền đúng cho files..."
${SSHPASS} ssh ${SSH_OPTS} "${SERVER_USER}@${SERVER_IP}" bash <<EOF
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
echo "  2. Tạo page 'Home', thêm shortcode [ds_homepage]"
echo "  3. Settings → Reading → Static page → Chọn 'Home'"
echo "  4. WooCommerce → Settings → Payments → Cấu hình PayPal"
echo "  5. URL: https://${DOMAIN}/wp-admin"
