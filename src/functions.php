<?php
/**
 * Digital Store Theme — functions.php
 *
 * Comprehensive WooCommerce hook customizations for:
 *  - Product detail page (SaaS-style layout)
 *  - Checkout page (one-column, minimal)
 *  - Theme setup, assets, and layout tweaks
 *
 * @package DigitalStore
 */

defined( 'ABSPATH' ) || exit;

// ════════════════════════════════════════════════════════════════
// A. THEME SETUP
// ════════════════════════════════════════════════════════════════

add_action( 'after_setup_theme', function () {
    // Inherit parent (Storefront) translations
    load_child_theme_textdomain( 'digital-store', get_stylesheet_directory() . '/languages' );

    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'html5', [ 'search-form', 'comment-form', 'gallery', 'caption' ] );
    add_theme_support( 'wc-product-gallery-zoom' );
    add_theme_support( 'wc-product-gallery-lightbox' );
    add_theme_support( 'wc-product-gallery-slider' );

    register_nav_menus( [
        'primary' => __( 'Primary Navigation', 'digital-store' ),
        'footer'  => __( 'Footer Navigation', 'digital-store' ),
    ] );
} );

// ── Enqueue child theme CSS (after Storefront parent) ────────────
add_action( 'wp_enqueue_scripts', function () {
    $parent_style = 'storefront-style';
    wp_enqueue_style( $parent_style, get_template_directory_uri() . '/style.css' );
    wp_enqueue_style(
        'digital-store',
        get_stylesheet_uri(),
        [ $parent_style ],
        wp_get_theme()->get( 'Version' )
    );
}, 20 );

// ═══════════════════════════════════════════════════════════════════
// B. SINGLE PRODUCT PAGE — WooCommerce Action Hooks
//
//  Hook Execution Order (default priorities):
//  woocommerce_before_single_product          (1)
//  woocommerce_before_single_product_summary  (10,20,30)
//  woocommerce_single_product_summary:
//    - woocommerce_template_single_title       (5)
//    - woocommerce_template_single_rating      (10)
//    - woocommerce_template_single_price       (10)
//    - woocommerce_template_single_excerpt     (20)
//    - woocommerce_template_single_add_to_cart (30)
//    - woocommerce_template_single_meta        (40)
//    - woocommerce_template_single_sharing     (50)
//  woocommerce_after_single_product_summary   (10)
//  woocommerce_after_single_product           (10)
// ═══════════════════════════════════════════════════════════════════

// B1. Remove elements from the default product summary
add_action( 'init', function () {
    // Remove rating stars under the title (design choice: keep page clean)
    remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10 );

    // Remove meta (category, tags) — we replace it with a cleaner version
    remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );

    // Remove default sharing links
    remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 50 );

    // Remove "Additional Information" tab (irrelevant for digital products)
    add_filter( 'woocommerce_product_tabs', function ( $tabs ) {
        unset( $tabs['additional_information'] );
        return $tabs;
    } );
} );

// B2. Add badge ribbon ABOVE title (e.g. "Best Seller", "New")
add_action( 'woocommerce_single_product_summary', 'ds_product_badge', 3 );
function ds_product_badge(): void {
    global $product;
    if ( ! $product ) return;

    // Read from custom product meta field "_ds_badge" (set via ACF or custom meta box)
    $badge = get_post_meta( $product->get_id(), '_ds_badge', true );
    if ( ! $badge ) $badge = $product->is_featured() ? 'Featured' : '';
    if ( ! $badge ) return;

    printf(
        '<span class="ds-product-badge">%s</span>',
        esc_html( $badge )
    );
}

// B3. Add a feature checklist AFTER the excerpt (before Add to Cart)
add_action( 'woocommerce_single_product_summary', 'ds_product_feature_list', 25 );
function ds_product_feature_list(): void {
    global $product;
    if ( ! $product ) return;

    // Read features from custom meta (comma-separated or JSON array)
    $raw      = get_post_meta( $product->get_id(), '_ds_features', true );
    $features = $raw ? array_filter( array_map( 'trim', explode( "\n", $raw ) ) ) : [];

    // Fallback defaults for digital products
    if ( empty( $features ) ) {
        $features = [
            'Instant download after payment',
            'Lifetime access — no subscription',
            'Compatible with all major platforms',
            '30-day money-back guarantee',
        ];
    }

    echo '<ul class="ds-product-features">';
    foreach ( $features as $feature ) {
        printf( '<li>%s</li>', esc_html( $feature ) );
    }
    echo '</ul>';
}

// B4. Add trust badges AFTER the Add to Cart button
add_action( 'woocommerce_single_product_summary', 'ds_trust_badges', 35 );
function ds_trust_badges(): void {
    $badges = [
        [ 'icon' => '🔒', 'text' => 'Secure checkout' ],
        [ 'icon' => '⚡', 'text' => 'Instant delivery' ],
        [ 'icon' => '💳', 'text' => 'PayPal protected' ],
        [ 'icon' => '🔄', 'text' => '30-day refund' ],
    ];

    echo '<div class="ds-trust-badges">';
    foreach ( $badges as $badge ) {
        printf(
            '<span class="badge"><span>%s</span> %s</span>',
            esc_html( $badge['icon'] ),
            esc_html( $badge['text'] )
        );
    }
    echo '</div>';
}

// B5. Add a "File details" section AFTER the tabs (download info bar)
add_action( 'woocommerce_after_single_product_summary', 'ds_download_details_bar', 15 );
function ds_download_details_bar(): void {
    global $product;
    if ( ! $product || ! $product->is_downloadable() ) return;

    $file_size   = get_post_meta( $product->get_id(), '_ds_file_size', true )   ?: '—';
    $file_format = get_post_meta( $product->get_id(), '_ds_file_format', true ) ?: 'ZIP';
    $version     = get_post_meta( $product->get_id(), '_ds_version', true )      ?: '1.0';
    $updated     = get_post_meta( $product->get_id(), '_ds_updated', true )       ?: get_the_modified_date();

    echo '<div class="ds-file-info-bar">';
    echo '<div class="ds-file-info-bar__inner">';
    $items = [
        [ 'label' => 'Format',       'value' => $file_format ],
        [ 'label' => 'File size',    'value' => $file_size ],
        [ 'label' => 'Version',      'value' => $version ],
        [ 'label' => 'Last updated', 'value' => $updated ],
    ];
    foreach ( $items as $item ) {
        printf(
            '<div class="ds-file-info-item"><span class="label">%s</span><span class="value">%s</span></div>',
            esc_html( $item['label'] ),
            esc_html( $item['value'] )
        );
    }
    echo '</div></div>';
}

// B6. Rename the "Description" tab to "Overview"
add_filter( 'woocommerce_product_tabs', function ( $tabs ) {
    if ( isset( $tabs['description'] ) ) {
        $tabs['description']['title'] = __( 'Overview', 'digital-store' );
    }
    if ( isset( $tabs['reviews'] ) ) {
        $tabs['reviews']['title'] = __( 'Reviews', 'digital-store' );
    }
    return $tabs;
} );

// B7. Add a custom "Changelog" tab for digital products
add_filter( 'woocommerce_product_tabs', function ( $tabs ) {
    global $product;
    if ( ! $product ) return $tabs;

    $changelog = get_post_meta( $product->get_id(), '_ds_changelog', true );
    if ( ! $changelog ) return $tabs;

    $tabs['ds_changelog'] = [
        'title'    => __( 'Changelog', 'digital-store' ),
        'priority' => 25,
        'callback' => function () use ( $changelog ) {
            echo '<div class="ds-changelog">';
            echo wp_kses_post( wpautop( $changelog ) );
            echo '</div>';
        },
    ];

    return $tabs;
} );

// B8. Replace default "Related Products" header text
add_filter( 'woocommerce_product_related_products_heading', fn() =>
    __( 'You might also like', 'digital-store' )
);

// B9. Limit related products to 3
add_filter( 'woocommerce_output_related_products_args', function ( $args ) {
    $args['posts_per_page'] = 3;
    $args['columns']        = 3;
    return $args;
} );

// ═══════════════════════════════════════════════════════════════════
// C. CHECKOUT PAGE — WooCommerce Action Hooks
//
//  Hook Execution Order:
//  woocommerce_before_checkout_form         (10)
//  woocommerce_checkout_before_customer_details
//  woocommerce_checkout_billing             (10)
//  woocommerce_checkout_shipping            (10)  ← removed for digital
//  woocommerce_checkout_after_customer_details
//  woocommerce_checkout_before_order_review (10)
//  woocommerce_review_order_before_cart_contents
//  woocommerce_review_order_after_cart_contents
//  woocommerce_review_order_before_payment  (10)
//  woocommerce_review_order_after_payment   (10)
//  woocommerce_checkout_after_order_review  (10)
//  woocommerce_after_checkout_form          (10)
// ═══════════════════════════════════════════════════════════════════

// C1. Add a progress indicator at the top of the checkout
add_action( 'woocommerce_before_checkout_form', 'ds_checkout_progress_bar', 5 );
function ds_checkout_progress_bar(): void {
    ?>
    <div class="ds-checkout-steps" aria-label="Checkout steps">
        <div class="ds-checkout-steps__step is-active">
            <span class="step-num">1</span>
            <span class="step-label"><?php esc_html_e( 'Cart', 'digital-store' ); ?></span>
        </div>
        <div class="ds-checkout-steps__divider"></div>
        <div class="ds-checkout-steps__step is-active is-current">
            <span class="step-num">2</span>
            <span class="step-label"><?php esc_html_e( 'Checkout', 'digital-store' ); ?></span>
        </div>
        <div class="ds-checkout-steps__divider"></div>
        <div class="ds-checkout-steps__step">
            <span class="step-num">3</span>
            <span class="step-label"><?php esc_html_e( 'Confirmation', 'digital-store' ); ?></span>
        </div>
    </div>
    <?php
}

// C2. Change checkout page section headings to match SaaS style
add_filter( 'woocommerce_checkout_fields', function ( $fields ) {
    // Remove "First name / Last name" split — replace with full name for digital
    // (optional: combine into one field)
    if ( isset( $fields['billing']['billing_company'] ) ) {
        // Make "Company" optional and less prominent
        $fields['billing']['billing_company']['required'] = false;
        $fields['billing']['billing_company']['class']    = [ 'form-row-wide' ];
    }

    // For digital products, remove shipping-related billing fields
    $remove_billing = [ 'billing_address_1', 'billing_address_2', 'billing_city', 'billing_postcode', 'billing_state' ];
    foreach ( $remove_billing as $field ) {
        if ( isset( $fields['billing'][ $field ] ) ) {
            $fields['billing'][ $field ]['required'] = false;
            $fields['billing'][ $field ]['class']    = [ 'form-row-wide', 'ds-optional-field' ];
        }
    }

    return $fields;
} );

// C3. Add a "Secure Checkout" trust banner before the payment section
add_action( 'woocommerce_review_order_before_payment', 'ds_secure_payment_badge' );
function ds_secure_payment_badge(): void {
    echo '<div class="ds-secure-badge">';
    echo '🔒 <strong>' . esc_html__( 'Secure Checkout', 'digital-store' ) . '</strong>';
    echo ' — ' . esc_html__( '256-bit SSL encryption · PayPal Buyer Protection', 'digital-store' );
    echo '</div>';
}

// C4. Customize the "Place Order" button text
add_filter( 'woocommerce_order_button_text', function () {
    return __( '🛒 Complete Purchase', 'digital-store' );
} );

// C5. Add terms notice above the Place Order button
add_action( 'woocommerce_review_order_after_payment', 'ds_checkout_terms_note', 5 );
function ds_checkout_terms_note(): void {
    $terms_url   = get_privacy_policy_url();
    $refund_url  = get_permalink( wc_get_page_id( 'terms' ) );

    printf(
        '<p class="ds-checkout-terms">%s <a href="%s">%s</a> %s <a href="%s">%s</a>.</p>',
        esc_html__( 'By completing this purchase you agree to our', 'digital-store' ),
        esc_url( $refund_url ),
        esc_html__( 'Terms of Service', 'digital-store' ),
        esc_html__( 'and', 'digital-store' ),
        esc_url( $terms_url ),
        esc_html__( 'Privacy Policy', 'digital-store' )
    );
}

// C6. Add satisfaction guarantee below order review
add_action( 'woocommerce_after_checkout_form', 'ds_guarantee_strip' );
function ds_guarantee_strip(): void {
    $items = [
        '✓ 30-day money-back guarantee',
        '✓ Instant download access',
        '✓ Lifetime product updates',
        '✓ Priority email support',
    ];
    echo '<div class="ds-guarantee-strip">';
    foreach ( $items as $item ) {
        printf( '<span class="ds-guarantee-strip__item">%s</span>', esc_html( $item ) );
    }
    echo '</div>';
}

// ═══════════════════════════════════════════════════════════════════
// D. CART PAGE — Minor Hooks
// ═══════════════════════════════════════════════════════════════════

// D1. Custom empty cart message
add_filter( 'woocommerce_empty_cart_message', fn() =>
    '<span class="cart-empty-icon">🛒</span><p>' .
    esc_html__( 'Your cart is empty — browse our digital products!', 'digital-store' ) .
    '</p>'
);

// D2. Add "Continue Shopping" button to empty cart
add_action( 'woocommerce_cart_is_empty', function () {
    $shop_url = get_permalink( wc_get_page_id( 'shop' ) );
    wc_get_template( 'global/link-button.php', [
        'url'   => $shop_url,
        'label' => __( '← Browse Products', 'digital-store' ),
        'class' => 'button ds-continue-shopping',
    ] );
}, 20 );

// ═══════════════════════════════════════════════════════════════════
// E. SHOP / ARCHIVE PAGE
// ═══════════════════════════════════════════════════════════════════

// E1. Remove default WooCommerce breadcrumb
remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );

// E2. Add a custom hero banner above the shop loop
add_action( 'woocommerce_before_shop_loop', 'ds_shop_hero', 5 );
function ds_shop_hero(): void {
    if ( ! is_shop() ) return;
    $headline = get_option( '_ds_shop_headline', __( 'Digital Products', 'digital-store' ) );
    $sub      = get_option( '_ds_shop_subheadline', __( 'Download instantly. Use forever.', 'digital-store' ) );
    ?>
    <div class="ds-shop-hero">
        <h1 class="ds-shop-hero__title"><?php echo esc_html( $headline ); ?></h1>
        <p  class="ds-shop-hero__sub"><?php echo esc_html( $sub ); ?></p>
    </div>
    <?php
}

// E3. Change "Add to Cart" button text for downloadable products
add_filter( 'woocommerce_product_add_to_cart_text', function ( $text, $product ) {
    if ( $product->is_downloadable() || $product->is_virtual() ) {
        return __( 'Get Instant Access', 'digital-store' );
    }
    return $text;
}, 10, 2 );

add_filter( 'woocommerce_product_single_add_to_cart_text', function ( $text, $product ) {
    if ( $product->is_downloadable() || $product->is_virtual() ) {
        return __( '⬇ Buy & Download Now', 'digital-store' );
    }
    return $text;
}, 10, 2 );

// E4. Set products per page
add_filter( 'loop_shop_per_page', fn() => 12, 20 );

// ═══════════════════════════════════════════════════════════════════
// F. THANK YOU / ORDER RECEIVED PAGE
// ═══════════════════════════════════════════════════════════════════

// F1. Custom thank-you heading
add_filter( 'woocommerce_thankyou_order_received_text', function ( $text, $order ) {
    if ( $order ) {
        $name = $order->get_billing_first_name();
        return sprintf(
            __( '🎉 Thank you, %s! Your purchase is confirmed and your download links have been emailed to you.', 'digital-store' ),
            esc_html( $name )
        );
    }
    return $text;
}, 10, 2 );

// F2. Show download links prominently on the thank-you page
add_action( 'woocommerce_thankyou', 'ds_thankyou_download_links', 20 );
function ds_thankyou_download_links( int $order_id ): void {
    $order     = wc_get_order( $order_id );
    $downloads = $order ? $order->get_downloadable_items() : [];

    if ( empty( $downloads ) ) return;

    echo '<section class="ds-thankyou-downloads">';
    echo '<h3>' . esc_html__( '⬇ Your Downloads', 'digital-store' ) . '</h3>';
    echo '<ul class="ds-download-list">';
    foreach ( $downloads as $dl ) {
        printf(
            '<li><a class="button" href="%s">%s — %s</a></li>',
            esc_url( $dl['download_url'] ),
            esc_html( $dl['product_name'] ),
            esc_html( $dl['download_name'] )
        );
    }
    echo '</ul>';
    echo '</section>';
}
