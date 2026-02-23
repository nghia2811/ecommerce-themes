<?php
/**
 * Digital Store Theme — functions.php
 *
 * Blog reviews + digital products WooCommerce child theme.
 * SEO-optimised for Google crawling.
 *
 * @package DigitalStore
 */

defined( 'ABSPATH' ) || exit;

// ════════════════════════════════════════════════════════════════
// A. THEME SETUP
// ════════════════════════════════════════════════════════════════

add_action( 'after_setup_theme', function () {
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

// ── Enqueue CSS — high priority to override Storefront ──
add_action( 'wp_enqueue_scripts', function () {
    wp_dequeue_style( 'storefront-style' );
    wp_dequeue_style( 'storefront-fonts' );

    wp_enqueue_style( 'storefront-parent',
        get_template_directory_uri() . '/style.css', [],
        wp_get_theme()->parent()->get( 'Version' )
    );
    wp_enqueue_style( 'digital-store',
        get_stylesheet_uri(), [ 'storefront-parent' ],
        wp_get_theme()->get( 'Version' )
    );
    wp_enqueue_style( 'ds-google-fonts',
        'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap',
        [], null
    );
}, 999 );

// ── Disable Storefront homepage defaults ──
add_action( 'init', function () {
    remove_action( 'homepage', 'storefront_homepage_content', 10 );
    remove_action( 'homepage', 'storefront_product_categories', 20 );
    remove_action( 'homepage', 'storefront_recent_products', 30 );
    remove_action( 'homepage', 'storefront_featured_products', 40 );
    remove_action( 'homepage', 'storefront_popular_products', 50 );
    remove_action( 'homepage', 'storefront_on_sale_products', 60 );
    remove_action( 'homepage', 'storefront_best_selling_products', 70 );
} );

// ════════════════════════════════════════════════════════════════
// SEO — Schema.org structured data
// ════════════════════════════════════════════════════════════════

add_action( 'wp_head', function () {
    // Website schema
    if ( is_front_page() ) {
        $schema = [
            '@context'    => 'https://schema.org',
            '@type'       => 'WebSite',
            'name'        => get_bloginfo( 'name' ),
            'description' => get_bloginfo( 'description' ),
            'url'         => home_url( '/' ),
            'potentialAction' => [
                '@type'       => 'SearchAction',
                'target'      => home_url( '/?s={search_term_string}' ),
                'query-input' => 'required name=search_term_string',
            ],
        ];
        printf( '<script type="application/ld+json">%s</script>' . "\n", wp_json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) );
    }

    // Single product schema
    if ( function_exists( 'is_product' ) && is_product() ) {
        global $product;
        if ( $product ) {
            $schema = [
                '@context'    => 'https://schema.org',
                '@type'       => 'Product',
                'name'        => $product->get_name(),
                'description' => wp_strip_all_tags( $product->get_short_description() ),
                'image'       => wp_get_attachment_url( $product->get_image_id() ),
                'offers'      => [
                    '@type'         => 'Offer',
                    'price'         => $product->get_price(),
                    'priceCurrency' => get_woocommerce_currency(),
                    'availability'  => $product->is_in_stock()
                        ? 'https://schema.org/InStock'
                        : 'https://schema.org/OutOfStock',
                    'url'           => get_permalink(),
                ],
            ];
            printf( '<script type="application/ld+json">%s</script>' . "\n", wp_json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) );
        }
    }

    // Blog post schema
    if ( is_singular( 'post' ) ) {
        global $post;
        $schema = [
            '@context'      => 'https://schema.org',
            '@type'         => 'Article',
            'headline'      => get_the_title(),
            'description'   => wp_strip_all_tags( get_the_excerpt() ),
            'datePublished' => get_the_date( 'c' ),
            'dateModified'  => get_the_modified_date( 'c' ),
            'author'        => [
                '@type' => 'Person',
                'name'  => get_the_author(),
            ],
            'publisher'     => [
                '@type' => 'Organization',
                'name'  => get_bloginfo( 'name' ),
            ],
            'mainEntityOfPage' => get_permalink(),
        ];
        if ( has_post_thumbnail() ) {
            $schema['image'] = get_the_post_thumbnail_url( $post, 'large' );
        }
        printf( '<script type="application/ld+json">%s</script>' . "\n", wp_json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) );
    }
}, 5 );

// SEO meta description
add_action( 'wp_head', function () {
    if ( is_front_page() ) {
        $desc = get_bloginfo( 'description' );
    } elseif ( is_singular() ) {
        $desc = wp_strip_all_tags( get_the_excerpt() );
    } elseif ( function_exists( 'is_shop' ) && is_shop() ) {
        $desc = 'Browse and download premium digital products instantly.';
    } else {
        return;
    }
    if ( $desc ) {
        printf( '<meta name="description" content="%s" />' . "\n", esc_attr( wp_trim_words( $desc, 30 ) ) );
    }
}, 1 );

// Open Graph basic tags
add_action( 'wp_head', function () {
    if ( ! is_singular() ) return;
    echo '<meta property="og:title" content="' . esc_attr( get_the_title() ) . '" />' . "\n";
    echo '<meta property="og:url" content="' . esc_url( get_permalink() ) . '" />' . "\n";
    echo '<meta property="og:site_name" content="' . esc_attr( get_bloginfo( 'name' ) ) . '" />' . "\n";
    echo '<meta property="og:type" content="' . ( is_singular( 'post' ) ? 'article' : 'website' ) . '" />' . "\n";
    if ( has_post_thumbnail() ) {
        echo '<meta property="og:image" content="' . esc_url( get_the_post_thumbnail_url( null, 'large' ) ) . '" />' . "\n";
    }
}, 2 );

// ════════════════════════════════════════════════════════════════
// HOMEPAGE SHORTCODE — [ds_homepage]
// ════════════════════════════════════════════════════════════════

add_shortcode( 'ds_homepage', 'ds_render_homepage' );
function ds_render_homepage(): string {
    ob_start();
    ?>

    <!-- Hero -->
    <section class="ds-hero">
        <div class="ds-hero__inner">
            <span class="ds-hero__badge">✨ Premium Digital Products</span>
            <h1 class="ds-hero__title">
                Reviews &amp; <span>Digital Products</span><br>You Can Trust
            </h1>
            <p class="ds-hero__subtitle">
                In-depth reviews, honest recommendations, and premium digital downloads — all in one place.
            </p>
            <div class="ds-hero__actions">
                <a href="<?php echo esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ); ?>" class="ds-btn ds-btn--primary ds-btn--lg">
                    Browse Products →
                </a>
                <a href="<?php echo esc_url( get_permalink( get_option( 'page_for_posts' ) ) ?: home_url( '/blog/' ) ); ?>" class="ds-btn ds-btn--outline ds-btn--lg">
                    Read Reviews
                </a>
            </div>
            <div class="ds-hero__stats">
                <div class="ds-hero__stat">
                    <strong>500+</strong>
                    <span>Digital Products</span>
                </div>
                <div class="ds-hero__stat-divider"></div>
                <div class="ds-hero__stat">
                    <strong>10k+</strong>
                    <span>Happy Customers</span>
                </div>
                <div class="ds-hero__stat-divider"></div>
                <div class="ds-hero__stat">
                    <strong>4.9★</strong>
                    <span>Average Rating</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Trust -->
    <section class="ds-trust-strip">
        <div class="ds-trust-strip__inner">
            <div class="ds-trust-strip__item">
                <span class="ds-trust-strip__icon">⚡</span>
                <div>
                    <strong>Instant Download</strong>
                    <span>Access your files immediately after payment</span>
                </div>
            </div>
            <div class="ds-trust-strip__item">
                <span class="ds-trust-strip__icon">🔒</span>
                <div>
                    <strong>Secure Checkout</strong>
                    <span>256-bit SSL — your data is safe with us</span>
                </div>
            </div>
            <div class="ds-trust-strip__item">
                <span class="ds-trust-strip__icon">💳</span>
                <div>
                    <strong>PayPal Protected</strong>
                    <span>Full buyer protection on every order</span>
                </div>
            </div>
            <div class="ds-trust-strip__item">
                <span class="ds-trust-strip__icon">🔄</span>
                <div>
                    <strong>30-Day Refund</strong>
                    <span>Not happy? Get a full refund, no questions</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Latest Blog Posts -->
    <section class="ds-latest-posts-section">
        <div class="ds-section-header">
            <h2 class="ds-section-title">Latest Reviews & Articles</h2>
            <p class="ds-section-sub">Our latest insights, reviews, and guides</p>
        </div>
        <?php
        $posts_query = new WP_Query( [
            'post_type'      => 'post',
            'posts_per_page' => 3,
            'post_status'    => 'publish',
        ] );
        if ( $posts_query->have_posts() ) :
            echo '<div class="ds-posts-grid">';
            while ( $posts_query->have_posts() ) : $posts_query->the_post();
                $cats = get_the_category();
                $cat_name = ! empty( $cats ) ? $cats[0]->name : '';
                ?>
                <article class="ds-post-card" itemscope itemtype="https://schema.org/Article">
                    <?php if ( has_post_thumbnail() ) : ?>
                        <a href="<?php the_permalink(); ?>">
                            <img class="ds-post-card__img" src="<?php echo esc_url( get_the_post_thumbnail_url( null, 'medium_large' ) ); ?>"
                                 alt="<?php the_title_attribute(); ?>" loading="lazy" itemprop="image" />
                        </a>
                    <?php endif; ?>
                    <div class="ds-post-card__body">
                        <?php if ( $cat_name ) : ?>
                            <span class="ds-post-card__cat"><?php echo esc_html( $cat_name ); ?></span>
                        <?php endif; ?>
                        <h3 class="ds-post-card__title" itemprop="headline">
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </h3>
                        <p class="ds-post-card__excerpt"><?php echo wp_trim_words( get_the_excerpt(), 20 ); ?></p>
                        <span class="ds-post-card__meta">
                            <time datetime="<?php echo get_the_date( 'c' ); ?>" itemprop="datePublished"><?php echo get_the_date(); ?></time>
                            · <?php echo get_the_author(); ?>
                        </span>
                    </div>
                </article>
                <?php
            endwhile;
            echo '</div>';
            wp_reset_postdata();
        endif;
        ?>
    </section>

    <!-- Featured Products -->
    <section class="ds-featured-section">
        <div class="ds-section-header">
            <h2 class="ds-section-title">Digital Products</h2>
            <p class="ds-section-sub">Download instantly, use forever</p>
        </div>
        <?php
        $featured = wc_get_products( [ 'status' => 'publish', 'featured' => true, 'limit' => 1 ] );
        if ( ! empty( $featured ) ) {
            echo do_shortcode( '[products limit="6" columns="3" visibility="featured"]' );
        } else {
            echo do_shortcode( '[products limit="6" columns="3" orderby="date"]' );
        }
        ?>
    </section>

    <!-- CTA -->
    <section class="ds-cta-section">
        <div class="ds-cta-section__inner">
            <h2>Ready to get started?</h2>
            <p>Browse all products and find exactly what you need.</p>
            <a href="<?php echo esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ); ?>" class="ds-btn ds-btn--primary ds-btn--lg">
                View All Products →
            </a>
        </div>
    </section>

    <?php
    return ob_get_clean();
}

// Hook for Storefront homepage template
add_action( 'homepage', function () {
    echo do_shortcode( '[ds_homepage]' );
}, 10 );

// ═══════════════════════════════════════════════════════════════════
// B. SINGLE PRODUCT PAGE
// ═══════════════════════════════════════════════════════════════════

add_action( 'init', function () {
    remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10 );
    remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
    remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 50 );
    add_filter( 'woocommerce_product_tabs', function ( $tabs ) {
        unset( $tabs['additional_information'] );
        return $tabs;
    } );
} );

add_action( 'woocommerce_single_product_summary', 'ds_product_badge', 3 );
function ds_product_badge(): void {
    global $product;
    if ( ! $product ) return;
    $badge = get_post_meta( $product->get_id(), '_ds_badge', true );
    if ( ! $badge ) $badge = $product->is_featured() ? 'Featured' : '';
    if ( ! $badge ) return;
    printf( '<span class="ds-product-badge">%s</span>', esc_html( $badge ) );
}

add_action( 'woocommerce_single_product_summary', 'ds_product_feature_list', 25 );
function ds_product_feature_list(): void {
    global $product;
    if ( ! $product ) return;
    $raw = get_post_meta( $product->get_id(), '_ds_features', true );
    $features = $raw ? array_filter( array_map( 'trim', explode( "\n", $raw ) ) ) : [
        'Instant download after payment',
        'Lifetime access — no subscription',
        'Compatible with all major platforms',
        '30-day money-back guarantee',
    ];
    echo '<ul class="ds-product-features">';
    foreach ( $features as $f ) printf( '<li>%s</li>', esc_html( $f ) );
    echo '</ul>';
}

add_action( 'woocommerce_single_product_summary', 'ds_trust_badges', 35 );
function ds_trust_badges(): void {
    $badges = [
        [ 'icon' => '🔒', 'text' => 'Secure checkout' ],
        [ 'icon' => '⚡', 'text' => 'Instant delivery' ],
        [ 'icon' => '💳', 'text' => 'PayPal protected' ],
        [ 'icon' => '🔄', 'text' => '30-day refund' ],
    ];
    echo '<div class="ds-trust-badges">';
    foreach ( $badges as $b ) printf( '<span class="badge"><span>%s</span> %s</span>', esc_html( $b['icon'] ), esc_html( $b['text'] ) );
    echo '</div>';
}

add_action( 'woocommerce_after_single_product_summary', 'ds_download_details_bar', 15 );
function ds_download_details_bar(): void {
    global $product;
    if ( ! $product || ! $product->is_downloadable() ) return;
    $items = [
        [ 'label' => 'Format',       'value' => get_post_meta( $product->get_id(), '_ds_file_format', true ) ?: 'ZIP' ],
        [ 'label' => 'File size',    'value' => get_post_meta( $product->get_id(), '_ds_file_size', true ) ?: '—' ],
        [ 'label' => 'Version',      'value' => get_post_meta( $product->get_id(), '_ds_version', true ) ?: '1.0' ],
        [ 'label' => 'Last updated', 'value' => get_post_meta( $product->get_id(), '_ds_updated', true ) ?: get_the_modified_date() ],
    ];
    echo '<div class="ds-file-info-bar"><div class="ds-file-info-bar__inner">';
    foreach ( $items as $i ) printf( '<div class="ds-file-info-item"><span class="label">%s</span><span class="value">%s</span></div>', esc_html( $i['label'] ), esc_html( $i['value'] ) );
    echo '</div></div>';
}

add_filter( 'woocommerce_product_tabs', function ( $tabs ) {
    if ( isset( $tabs['description'] ) ) $tabs['description']['title'] = __( 'Overview', 'digital-store' );
    if ( isset( $tabs['reviews'] ) ) $tabs['reviews']['title'] = __( 'Reviews', 'digital-store' );
    return $tabs;
} );

add_filter( 'woocommerce_product_tabs', function ( $tabs ) {
    global $product;
    if ( ! $product ) return $tabs;
    $changelog = get_post_meta( $product->get_id(), '_ds_changelog', true );
    if ( ! $changelog ) return $tabs;
    $tabs['ds_changelog'] = [
        'title' => __( 'Changelog', 'digital-store' ), 'priority' => 25,
        'callback' => function () use ( $changelog ) {
            echo '<div class="ds-changelog">' . wp_kses_post( wpautop( $changelog ) ) . '</div>';
        },
    ];
    return $tabs;
} );

add_filter( 'woocommerce_product_related_products_heading', fn() => __( 'You might also like', 'digital-store' ) );
add_filter( 'woocommerce_output_related_products_args', function ( $args ) {
    $args['posts_per_page'] = 3; $args['columns'] = 3; return $args;
} );

// ═══════════════════════════════════════════════════════════════════
// C. CHECKOUT
// ═══════════════════════════════════════════════════════════════════

add_action( 'woocommerce_before_checkout_form', function () {
    ?>
    <div class="ds-checkout-steps" aria-label="Checkout steps">
        <div class="ds-checkout-steps__step is-active"><span class="step-num">1</span><span class="step-label">Cart</span></div>
        <div class="ds-checkout-steps__divider"></div>
        <div class="ds-checkout-steps__step is-active is-current"><span class="step-num">2</span><span class="step-label">Checkout</span></div>
        <div class="ds-checkout-steps__divider"></div>
        <div class="ds-checkout-steps__step"><span class="step-num">3</span><span class="step-label">Done</span></div>
    </div>
    <?php
}, 5 );

add_filter( 'woocommerce_checkout_fields', function ( $fields ) {
    if ( isset( $fields['billing']['billing_company'] ) ) {
        $fields['billing']['billing_company']['required'] = false;
    }
    foreach ( [ 'billing_address_1', 'billing_address_2', 'billing_city', 'billing_postcode', 'billing_state' ] as $f ) {
        if ( isset( $fields['billing'][ $f ] ) ) $fields['billing'][ $f ]['required'] = false;
    }
    return $fields;
} );

add_action( 'woocommerce_review_order_before_payment', function () {
    echo '<div class="ds-secure-badge">🔒 <strong>Secure Checkout</strong> — 256-bit SSL · PayPal Buyer Protection</div>';
} );

add_filter( 'woocommerce_order_button_text', fn() => '🛒 Complete Purchase' );

add_action( 'woocommerce_after_checkout_form', function () {
    $items = [ '✓ 30-day money-back guarantee', '✓ Instant download', '✓ Lifetime updates', '✓ Email support' ];
    echo '<div class="ds-guarantee-strip">';
    foreach ( $items as $i ) printf( '<span class="ds-guarantee-strip__item">%s</span>', esc_html( $i ) );
    echo '</div>';
} );

// ═══════════════════════════════════════════════════════════════════
// D. SHOP / ARCHIVE
// ═══════════════════════════════════════════════════════════════════

remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );

add_action( 'woocommerce_before_shop_loop', function () {
    if ( ! is_shop() ) return;
    ?>
    <div class="ds-shop-hero">
        <h1 class="ds-shop-hero__title">Digital Products</h1>
        <p class="ds-shop-hero__sub">Download instantly. Use forever.</p>
    </div>
    <?php
}, 5 );

add_filter( 'woocommerce_product_add_to_cart_text', function ( $text, $product ) {
    return ( $product->is_downloadable() || $product->is_virtual() ) ? 'Get Instant Access' : $text;
}, 10, 2 );

add_filter( 'woocommerce_product_single_add_to_cart_text', function ( $text, $product ) {
    return ( $product->is_downloadable() || $product->is_virtual() ) ? '⬇ Buy & Download Now' : $text;
}, 10, 2 );

add_filter( 'loop_shop_per_page', fn() => 12, 20 );

// ═══════════════════════════════════════════════════════════════════
// E. THANK YOU
// ═══════════════════════════════════════════════════════════════════

add_filter( 'woocommerce_thankyou_order_received_text', function ( $text, $order ) {
    if ( $order ) {
        return sprintf( '🎉 Thank you, %s! Your download links have been emailed to you.', esc_html( $order->get_billing_first_name() ) );
    }
    return $text;
}, 10, 2 );

add_action( 'woocommerce_thankyou', function ( int $order_id ) {
    $order = wc_get_order( $order_id );
    $downloads = $order ? $order->get_downloadable_items() : [];
    if ( empty( $downloads ) ) return;
    echo '<section class="ds-thankyou-downloads"><h3>⬇ Your Downloads</h3><ul class="ds-download-list">';
    foreach ( $downloads as $dl ) {
        printf( '<li><a class="button" href="%s">%s — %s</a></li>', esc_url( $dl['download_url'] ), esc_html( $dl['product_name'] ), esc_html( $dl['download_name'] ) );
    }
    echo '</ul></section>';
}, 20 );

// ═══════════════════════════════════════════════════════════════════
// F. EMPTY CART
// ═══════════════════════════════════════════════════════════════════

add_filter( 'woocommerce_empty_cart_message', fn() =>
    '<span class="cart-empty-icon">🛒</span><p>Your cart is empty — browse our digital products!</p>'
);
