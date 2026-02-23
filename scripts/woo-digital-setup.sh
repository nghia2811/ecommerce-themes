#!/bin/bash
# ============================================================
# WooCommerce Digital Store — WP-CLI Setup Script
# Run trên server (SSH vào, cd đến thư mục WordPress):
#   cd /home/your-domain.com/public_html
#   bash /path/to/scripts/woo-digital-setup.sh
# ============================================================
set -e

WP="wp --allow-root"
echo "🚀 Starting WooCommerce Digital Store configuration..."

# ── 1. Store identity ─────────────────────────────────────────
echo ""
echo "⚙️  [1/6] Configuring store type for Digital Products..."

$WP option update woocommerce_sell_in_person "no"

# Disable physical shipping globally (digital store only)
$WP option update woocommerce_ship_to_countries "disabled"
$WP option update woocommerce_shipping_cost_requires_address "no"

# Restrict to downloadable + virtual product types
$WP option update woocommerce_enable_guest_checkout "yes"
$WP option update woocommerce_enable_checkout_login_reminder "yes"
$WP option update woocommerce_enable_signup_and_login_from_checkout "yes"

echo "   ✅ Store type configured for Digital Products only."

# ── 2. Download / Virtual Settings ───────────────────────────
echo ""
echo "⚙️  [2/6] Configuring download & virtual product settings..."

# Grant download access immediately after payment (no manual approval)
$WP option update woocommerce_downloads_grant_access_after_payment "yes"

# Require account to download (security for paid digital products)
$WP option update woocommerce_downloads_require_login "yes"

# Allow redirects for download links (cleaner URL)
$WP option update woocommerce_file_download_method "redirect"

# Add download permissions when order transitions to processing (not just completed)
$WP option update woocommerce_downloads_add_hash_to_filename "yes"

echo "   ✅ Download settings configured."

# ── 3. PayPal Checkout — Sandbox credentials ──────────────────
echo ""
echo "⚙️  [3/6] Configuring PayPal Checkout (Sandbox mode)..."

# Enable the PayPal Payments plugin (must already be installed; see README)
$WP option patch update woocommerce_ppcp-gateway_settings enabled "yes"
$WP option patch update woocommerce_ppcp-gateway_settings sandbox_on "yes"

# Sandbox credentials — replace with real values from .env
$WP option patch update woocommerce_ppcp-gateway_settings sandbox_client_id \
    "${PAYPAL_SANDBOX_CLIENT_ID:-PASTE_SANDBOX_CLIENT_ID}"
$WP option patch update woocommerce_ppcp-gateway_settings sandbox_client_secret \
    "${PAYPAL_SANDBOX_SECRET:-PASTE_SANDBOX_SECRET}"

# Title shown to customer at checkout
$WP option patch update woocommerce_ppcp-gateway_settings title "PayPal"
$WP option patch update woocommerce_ppcp-gateway_settings description \
    "Pay securely via PayPal — Credit/Debit card accepted."

echo "   ✅ PayPal Sandbox configured."
echo "   ℹ️  Switch to Live: set sandbox_on=no and add live credentials."

# ── 4. Disable other payment gateways ────────────────────────
echo ""
echo "⚙️  [4/6] Disabling non-PayPal payment gateways..."

$WP option patch update woocommerce_bacs_settings enabled "no"   # Bank transfer
$WP option patch update woocommerce_cheque_settings enabled "no" # Cheque
$WP option patch update woocommerce_cod_settings enabled "no"    # Cash on delivery

echo "   ✅ Only PayPal remains active."

# ── 5. Order email settings ───────────────────────────────────
echo ""
echo "⚙️  [5/6] Configuring order notification emails..."

$WP option patch update woocommerce_customer_completed_order_settings enabled "yes"
$WP option patch update woocommerce_customer_completed_order_settings subject \
    "Your order is complete — download your products"
$WP option patch update woocommerce_customer_completed_order_settings heading \
    "Thank you for your purchase!"

echo "   ✅ Customer Completed Order email enabled."

# ── 6. Performance & UX ──────────────────────────────────────
echo ""
echo "⚙️  [6/6] Applying performance & UX tweaks..."

# Disable cart page for digital goods (optional but faster UX)
$WP option update woocommerce_cart_redirect_after_add "yes"

# Enable AJAX add-to-cart on archives
$WP option update woocommerce_enable_ajax_add_to_cart "yes"

# High res product images for digital content previews
$WP option update woocommerce_single_image_width 800
$WP option update woocommerce_thumbnail_image_width 450

echo "   ✅ UX tweaks applied."

echo ""
echo "══════════════════════════════════════════════════════"
echo "🎉  WooCommerce Digital Store setup COMPLETE!"
echo "══════════════════════════════════════════════════════"
echo ""
echo "Next steps:"
echo "  1. Install 'PayPal Payments' plugin from wp-admin if not yet installed."
echo "  2. Replace sandbox credentials with Live keys from PayPal Developer Dashboard."
echo "  3. Set PAYPAL_SANDBOX_CLIENT_ID and PAYPAL_SANDBOX_SECRET in .env"
echo "  4. Verify the mu-plugin is active: wp plugin list --allow-root"
