#!/bin/bash
# ============================================================
# WP-CLI One-Shot Installer for WordPress + WooCommerce
# Runs once on first deploy, then exits cleanly
# ============================================================
set -e

WP_PATH="/var/www/html"
cd "$WP_PATH"

echo "⏳ Waiting for database to be ready..."
until wp db check --allow-root --quiet 2>/dev/null; do
    echo "   DB not ready yet, retrying in 5s..."
    sleep 5
done
echo "✅ Database is ready."

# Check if WordPress is already installed
if wp core is-installed --allow-root --quiet 2>/dev/null; then
    echo "ℹ️  WordPress is already installed. Skipping core install."
else
    echo "🔧 Installing WordPress core..."
    wp core install \
        --allow-root \
        --url="${WP_SITEURL}" \
        --title="${WP_TITLE}" \
        --admin_user="${WP_ADMIN_USER}" \
        --admin_password="${WP_ADMIN_PASSWORD}" \
        --admin_email="${WP_ADMIN_EMAIL}" \
        --skip-email
    echo "✅ WordPress installed."

    # ── Set Permalink to Post Name (SEO Friendly) ─────────────
    echo "🔗 Setting permalink structure..."
    wp rewrite structure '/%postname%/' --allow-root
    wp rewrite flush --allow-root
    echo "✅ Permalinks configured."

    # ── Install & Activate WooCommerce ────────────────────────
    echo "🛒 Installing WooCommerce..."
    wp plugin install woocommerce --activate --allow-root
    echo "✅ WooCommerce installed and activated."

    # ── Install Redis Object Cache ────────────────────────────
    echo "⚡ Installing Redis Object Cache plugin..."
    wp plugin install redis-cache --activate --allow-root
    wp redis enable --allow-root
    echo "✅ Redis cache enabled."

    # ── Configure WooCommerce for Digital Products ────────────
    echo "⚙️  Configuring WooCommerce for digital products..."
    wp option update woocommerce_store_address "" --allow-root
    wp option update woocommerce_default_country "US" --allow-root
    wp option update woocommerce_currency "USD" --allow-root
    wp option update woocommerce_manage_stock "yes" --allow-root
    wp option update woocommerce_downloads_require_login "yes" --allow-root
    wp option update woocommerce_downloads_grant_access_after_payment "yes" --allow-root
    echo "✅ WooCommerce configured for digital products."

    # ── Install Storefront parent theme + Digital Store child ──
    echo "🎨 Installing Storefront parent theme..."
    wp theme install storefront --allow-root
    echo "✅ Storefront installed."

    echo "🎨 Activating Digital Store child theme..."
    wp theme activate digital-store --allow-root
    echo "✅ Digital Store theme activated."

    # ── Set WordPress to use Redis for object cache ────────────
    wp config set WP_REDIS_HOST redis --allow-root
    wp config set WP_REDIS_PORT 6379 --allow-root
    wp config set WP_REDIS_PASSWORD "${REDIS_PASSWORD:-}" --allow-root
    wp config set WP_CACHE true --raw --allow-root

    # ── Harden WooCommerce Upload Folder ──────────────────────
    echo "🔒 Securing uploads directory..."
    WOO_UPLOADS="$WP_PATH/wp-content/uploads/woocommerce_uploads"
    mkdir -p "$WOO_UPLOADS"
    # Nginx will block direct access; add htaccess as defense-in-depth
    cat > "$WOO_UPLOADS/.htaccess" <<'HTACCESS'
Options -Indexes
<Files "*.php">
    deny from all
</Files>
HTACCESS
    echo "✅ Uploads directory secured."
fi

echo ""
echo "🎉 Setup complete! Your WordPress/WooCommerce store is ready."
echo "   Admin URL : ${WP_SITEURL}/wp-admin"
echo "   User      : ${WP_ADMIN_USER}"
