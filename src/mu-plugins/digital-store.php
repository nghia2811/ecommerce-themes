<?php
/**
 * Plugin Name:  Digital Store — Core Functions
 * Plugin URI:   https://github.com/nghiant1/ecommerce-themes
 * Description:  Automates WooCommerce setup for digital-only stores:
 *               PayPal-only checkout, download email, and security tweaks.
 * Version:      1.0.0
 * Author:       Your Name
 * License:      GPL-2.0+
 *
 * Drop this file into: wp-content/mu-plugins/digital-store.php
 * It loads automatically — no activation required.
 */

defined( 'ABSPATH' ) || exit;

// ════════════════════════════════════════════════════════════════
// 1. STORE TYPE — Mark store as "Sells Virtual/Downloadable only"
// ════════════════════════════════════════════════════════════════

/**
 * Remove the "Shipping" tab from WooCommerce settings when the
 * cart contains only virtual/downloadable items.
 * Also pre-checks "Virtual" and "Downloadable" on the product edit page.
 */
add_filter( 'woocommerce_product_data_tabs', function ( $tabs ) {
    // Shipping tab is irrelevant for digital products
    unset( $tabs['shipping'] );
    return $tabs;
} );

add_filter( 'woocommerce_product_data_panels', function () {
    // Default new products to Virtual + Downloadable
    ?><script>
    jQuery(function ($) {
        if ($('#product-type').val() === 'simple') {
            $('#_virtual').prop('checked', true).trigger('change');
            $('#_downloadable').prop('checked', true).trigger('change');
        }
    });
    </script><?php
} );

// ════════════════════════════════════════════════════════════════
// 2. SHIPPING — Disable shipping step for all-digital carts
// ════════════════════════════════════════════════════════════════

/**
 * Returns true when every item in the cart is Virtual or Downloadable.
 */
function dsh_cart_is_all_digital(): bool {
    if ( ! WC()->cart ) {
        return false;
    }
    foreach ( WC()->cart->get_cart() as $item ) {
        $product = $item['data'] ?? null;
        if ( ! $product instanceof WC_Product ) {
            return false;
        }
        if ( ! $product->is_virtual() && ! $product->is_downloadable() ) {
            return false;
        }
    }
    return true;
}

/** Skip the shipping step in the checkout for digital-only carts. */
add_filter( 'woocommerce_cart_needs_shipping', function ( bool $needs ) {
    if ( dsh_cart_is_all_digital() ) {
        return false;
    }
    return $needs;
} );

/** Remove shipping-address fields when not needed. */
add_filter( 'woocommerce_checkout_fields', function ( array $fields ) {
    if ( dsh_cart_is_all_digital() ) {
        unset( $fields['shipping'] );
    }
    return $fields;
} );

// ════════════════════════════════════════════════════════════════
// 3. PAYPAL CHECKOUT — Show only PayPal for digital-only carts
// ════════════════════════════════════════════════════════════════

/**
 * PayPal API Credentials
 * ──────────────────────
 * How to connect:
 *
 *  SANDBOX (testing):
 *    1. Go to https://developer.paypal.com → My Apps & Credentials
 *    2. Create a Sandbox app → copy Client ID & Secret
 *    3. In wp-admin → WooCommerce → Settings → Payments → PayPal:
 *       - Enable Sandbox mode
 *       - Paste Sandbox Client ID & Secret
 *
 *  LIVE (production):
 *    1. Switch to "Live" tab in PayPal Developer Dashboard
 *    2. Create a Live app → copy Client ID & Secret
 *    3. In WooCommerce PayPal settings:
 *       - Disable Sandbox mode
 *       - Paste Live Client ID & Secret
 *
 *  Alternatively, define constants in wp-config.php or .env:
 *    PAYPAL_CLIENT_ID_SANDBOX, PAYPAL_SECRET_SANDBOX
 *    PAYPAL_CLIENT_ID_LIVE,    PAYPAL_SECRET_LIVE
 *
 *  The WooCommerce PayPal Payments plugin (ppcp) reads its own DB options,
 *  so this file sets those options programmatically on init when the
 *  constants are defined (handy for Docker/CI environments).
 */
add_action( 'init', function () {
    // Only update if constants are explicitly defined (e.g. via wp-config.php)
    if ( defined( 'PAYPAL_CLIENT_ID_SANDBOX' ) && defined( 'PAYPAL_SECRET_SANDBOX' ) ) {
        $sandbox_settings = get_option( 'woocommerce_ppcp-gateway_settings', [] );
        $sandbox_settings['sandbox_on']            = 'yes';
        $sandbox_settings['sandbox_client_id']     = PAYPAL_CLIENT_ID_SANDBOX;
        $sandbox_settings['sandbox_client_secret'] = PAYPAL_SECRET_SANDBOX;
        $sandbox_settings['enabled']               = 'yes';
        update_option( 'woocommerce_ppcp-gateway_settings', $sandbox_settings );
    }

    if ( defined( 'PAYPAL_CLIENT_ID_LIVE' ) && defined( 'PAYPAL_SECRET_LIVE' ) ) {
        $live_settings = get_option( 'woocommerce_ppcp-gateway_settings', [] );
        $live_settings['sandbox_on']        = 'no';
        $live_settings['client_id']         = PAYPAL_CLIENT_ID_LIVE;
        $live_settings['client_secret']     = PAYPAL_SECRET_LIVE;
        $live_settings['enabled']           = 'yes';
        update_option( 'woocommerce_ppcp-gateway_settings', $live_settings );
    }
} );

/**
 * Hide every payment gateway EXCEPT PayPal when the cart is all-digital.
 *
 * Keeps Bank Transfer, Cash on Delivery, Cheque, etc. hidden — only
 * PayPal (ppcp-gateway or paypal) is shown to the customer.
 *
 * @param  array $gateways  Indexed list of available WC_Payment_Gateway objects.
 * @return array
 */
add_filter( 'woocommerce_available_payment_gateways', function ( array $gateways ): array {
    if ( ! is_checkout() || ! dsh_cart_is_all_digital() ) {
        return $gateways;
    }

    // Gateway IDs that are PayPal-based (keep them all)
    $paypal_ids = [ 'ppcp-gateway', 'ppcp_pay_later', 'paypal', 'paypal_express' ];

    foreach ( array_keys( $gateways ) as $id ) {
        if ( ! in_array( $id, $paypal_ids, true ) ) {
            unset( $gateways[ $id ] );
        }
    }

    return $gateways;
} );

// ════════════════════════════════════════════════════════════════
// 4. DOWNLOAD EMAIL — Send download links on order "Completed"
// ════════════════════════════════════════════════════════════════

/**
 * Trigger a custom download-link email when an order transitions
 * to "Completed" (which happens automatically after PayPal payment
 * if "Grant access after payment" is enabled in WooCommerce settings).
 *
 * The official WooCommerce "Customer Completed Order" email already
 * includes download links. This hook adds an *additional* dedicated
 * "Your Downloads Are Ready" email with a clean, focused template.
 *
 * Hook: woocommerce_order_status_completed
 */
add_action( 'woocommerce_order_status_completed', 'dsh_send_download_links_email', 20 );

function dsh_send_download_links_email( int $order_id ): void {
    $order = wc_get_order( $order_id );
    if ( ! $order ) {
        return;
    }

    // Collect downloadable items from the order
    $downloads = $order->get_downloadable_items();
    if ( empty( $downloads ) ) {
        return; // Nothing to send for non-digital orders
    }

    $customer_email = $order->get_billing_email();
    $customer_name  = $order->get_billing_first_name();
    $site_name      = get_bloginfo( 'name' );
    $admin_email    = get_option( 'admin_email' );

    // ── Build the email body ──────────────────────────────────
    $subject = sprintf(
        __( '[%s] Your downloads are ready — Order #%s', 'digital-store' ),
        $site_name,
        $order->get_order_number()
    );

    ob_start();
    dsh_render_download_email_html( $order, $customer_name, $downloads );
    $message = ob_get_clean();

    // ── Send via WooCommerce mailer (inherits store email template) ──
    $mailer  = WC()->mailer();
    $headers = [
        'Content-Type: text/html; charset=UTF-8',
        "From: {$site_name} <{$admin_email}>",
    ];

    $mailer->send( $customer_email, $subject, $message, $headers );

    // Log for audit (visible in WooCommerce order notes)
    $order->add_order_note(
        sprintf(
            __( 'Download-links email sent to %s.', 'digital-store' ),
            $customer_email
        )
    );
}

/**
 * Render the HTML email body for download links.
 *
 * @param WC_Order $order
 * @param string   $customer_name
 * @param array    $downloads   Array of download data from WooCommerce.
 */
function dsh_render_download_email_html( WC_Order $order, string $customer_name, array $downloads ): void {
    $site_name = get_bloginfo( 'name' );
    $site_url  = home_url();
    $accent    = '#6c63ff'; // Adjust to your brand color

    echo wc_get_template_html( 'emails/email-header.php', [ 'email_heading' => "Your downloads are ready!" ] );
    ?>
    <p style="font-family:Arial,sans-serif;font-size:15px;color:#333;">
        Hi <?php echo esc_html( $customer_name ); ?>,
    </p>
    <p style="font-family:Arial,sans-serif;font-size:15px;color:#333;">
        Thank you for your purchase at <strong><?php echo esc_html( $site_name ); ?></strong>!
        Your payment via PayPal has been confirmed and your digital products are ready to download.
    </p>

    <h3 style="font-family:Arial,sans-serif;color:<?php echo esc_attr( $accent ); ?>;">
        📦 Order #<?php echo esc_html( $order->get_order_number() ); ?> – Download Links
    </h3>

    <table width="100%" cellpadding="10" cellspacing="0"
           style="border-collapse:collapse;font-family:Arial,sans-serif;font-size:14px;">
        <thead>
            <tr style="background:<?php echo esc_attr( $accent ); ?>;color:#fff;">
                <th align="left" style="padding:10px;">Product</th>
                <th align="left" style="padding:10px;">File</th>
                <th align="center" style="padding:10px;">Downloads Left</th>
                <th align="center" style="padding:10px;">Expires</th>
                <th align="center" style="padding:10px;">Link</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ( $downloads as $i => $dl ) :
            $remaining = $dl['downloads_remaining'] === '' ? '∞' : intval( $dl['downloads_remaining'] );
            $expires   = $dl['access_expires']
                ? date_i18n( get_option( 'date_format' ), strtotime( $dl['access_expires'] ) )
                : __( 'Never', 'digital-store' );
            $row_bg    = $i % 2 === 0 ? '#f9f9f9' : '#ffffff';
        ?>
            <tr style="background:<?php echo esc_attr( $row_bg ); ?>;">
                <td style="padding:10px;border-bottom:1px solid #eee;">
                    <?php echo esc_html( $dl['product_name'] ); ?>
                </td>
                <td style="padding:10px;border-bottom:1px solid #eee;">
                    <?php echo esc_html( $dl['download_name'] ); ?>
                </td>
                <td align="center" style="padding:10px;border-bottom:1px solid #eee;">
                    <?php echo esc_html( $remaining ); ?>
                </td>
                <td align="center" style="padding:10px;border-bottom:1px solid #eee;">
                    <?php echo esc_html( $expires ); ?>
                </td>
                <td align="center" style="padding:10px;border-bottom:1px solid #eee;">
                    <a href="<?php echo esc_url( $dl['download_url'] ); ?>"
                       style="background:<?php echo esc_attr( $accent ); ?>;color:#fff;
                              padding:8px 16px;border-radius:4px;text-decoration:none;
                              font-weight:bold;display:inline-block;">
                        ⬇ Download
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <p style="font-family:Arial,sans-serif;font-size:13px;color:#888;margin-top:20px;">
        You can also access all your downloads anytime from your
        <a href="<?php echo esc_url( wc_get_account_endpoint_url( 'downloads' ) ); ?>"
           style="color:<?php echo esc_attr( $accent ); ?>;">My Account → Downloads</a> page.
    </p>
    <?php
    echo wc_get_template_html( 'emails/email-footer.php' );
}

// ════════════════════════════════════════════════════════════════
// 5. SECURITY — Prevent direct URL access to digital files
//    (Complements the Nginx `internal` directive for woocommerce_uploads)
// ════════════════════════════════════════════════════════════════

/**
 * Force "Force Download" delivery method so WooCommerce serves files
 * through PHP (checking permissions) rather than a public redirect.
 * Best used together with WooCommerce Downloads → File Download Method = Force.
 */
add_filter( 'woocommerce_file_download_method', function () {
    return 'force'; // Options: 'force' | 'xsendfile' | 'redirect'
} );

/**
 * Add X-Accel-Redirect support for Nginx (most efficient method).
 * Nginx serves the file directly after PHP auth, bypassing PHP file streaming.
 *
 * Requires:
 *   - Nginx `internal` directive on /wp-content/uploads/woocommerce_uploads/
 *   - WooCommerce file method set to 'xsendfile'
 *   - Uncomment the filter below and set woocommerce_file_download_method to 'xsendfile'
 */
/*
add_filter( 'woocommerce_file_download_method', fn() => 'xsendfile' );
add_filter( 'woocommerce_download_file_xsendfile_header', function( $file_path, $download ) {
    // Strip the server path prefix so Nginx sees the URI
    $uri = str_replace( ABSPATH, '/', $file_path );
    header( 'X-Accel-Redirect: ' . $uri );
    exit;
}, 10, 2 );
*/

// ════════════════════════════════════════════════════════════════
// 6. ADMIN NOTICE — Remind admin to install PayPal Payments plugin
// ════════════════════════════════════════════════════════════════
add_action( 'admin_notices', function () {
    if ( ! current_user_can( 'manage_woocommerce' ) ) {
        return;
    }
    if ( is_plugin_active( 'woocommerce-paypal-payments/woocommerce-paypal-payments.php' ) ) {
        return;
    }
    $install_url = admin_url( 'plugin-install.php?s=woocommerce+paypal+payments&tab=search&type=term' );
    ?>
    <div class="notice notice-warning is-dismissible">
        <p>
            <strong>Digital Store:</strong>
            The <em>WooCommerce PayPal Payments</em> plugin is not installed.
            <a href="<?php echo esc_url( $install_url ); ?>">Install it now →</a>
            to enable PayPal Checkout for your digital store.
        </p>
    </div>
    <?php
} );
