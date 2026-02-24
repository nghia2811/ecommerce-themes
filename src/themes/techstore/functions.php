<?php
/**
 * TechStore functions and definitions
 *
 * @package TechStore
 * @since 1.0.0
 */

/**
 * Theme setup: supports, image sizes, WooCommerce.
 */
if ( ! function_exists( 'techstore_setup' ) ) :
	function techstore_setup() {
		// Core WordPress supports.
		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'title-tag' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ) );
		add_theme_support( 'customize-selective-refresh-widgets' );
		add_theme_support( 'align-wide' );
		add_theme_support( 'responsive-embeds' );
		add_theme_support( 'block-nav-menus' );

		// Custom image sizes.
		add_image_size( 'techstore-product-card', 400, 400, true );
		add_image_size( 'techstore-product-featured', 800, 800, true );
		add_image_size( 'techstore-hero', 1920, 800, true );
		add_image_size( 'techstore-blog-card', 600, 400, true );

		// WooCommerce support.
		add_theme_support( 'woocommerce', array(
			'thumbnail_image_width' => 400,
			'single_image_width'    => 800,
			'product_grid'          => array(
				'default_rows'    => 4,
				'min_rows'        => 1,
				'default_columns' => 4,
				'min_columns'     => 1,
				'max_columns'     => 6,
			),
		) );
		add_theme_support( 'wc-product-gallery-zoom' );
		add_theme_support( 'wc-product-gallery-lightbox' );
		add_theme_support( 'wc-product-gallery-slider' );
	}
endif;
add_action( 'after_setup_theme', 'techstore_setup' );

/**
 * Enqueue theme scripts and styles.
 */
if ( ! function_exists( 'techstore_scripts' ) ) :
	function techstore_scripts() {
		$version = wp_get_theme()->get( 'Version' );

		// Google Fonts — Inter (fallback if local font files are absent).
		wp_enqueue_style(
			'techstore-google-fonts',
			'https://fonts.googleapis.com/css2?family=Inter:wght@300..900&display=swap',
			array(),
			null
		);

		// WooCommerce customisations.
		if ( class_exists( 'WooCommerce' ) ) {
			wp_enqueue_style(
				'techstore-woocommerce',
				get_template_directory_uri() . '/assets/css/woocommerce.css',
				array( 'woocommerce-general' ),
				$version
			);
		}
	}
endif;
add_action( 'wp_enqueue_scripts', 'techstore_scripts' );

/**
 * Register custom block styles.
 */
if ( ! function_exists( 'techstore_block_styles' ) ) :
	function techstore_block_styles() {

		// Details block – arrow icon toggle.
		register_block_style( 'core/details', array(
			'name'         => 'arrow-icon-details',
			'label'        => __( 'Arrow icon', 'techstore' ),
			'inline_style' => '
				.is-style-arrow-icon-details {
					padding-top: var(--wp--preset--spacing--10);
					padding-bottom: var(--wp--preset--spacing--10);
				}
				.is-style-arrow-icon-details summary {
					list-style-type: "\2193\00a0\00a0\00a0";
				}
				.is-style-arrow-icon-details[open]>summary {
					list-style-type: "\2192\00a0\00a0\00a0";
				}',
		) );

		// Post terms – pill badges.
		register_block_style( 'core/post-terms', array(
			'name'         => 'pill',
			'label'        => __( 'Pill', 'techstore' ),
			'inline_style' => '
				.is-style-pill a,
				.is-style-pill span:not([class], [data-rich-text-placeholder]) {
					display: inline-block;
					background-color: var(--wp--preset--color--accent);
					color: #ffffff;
					padding: 0.25rem 0.75rem;
					border-radius: 2rem;
					font-size: 0.75rem;
					font-weight: 600;
					text-transform: uppercase;
					letter-spacing: 0.05em;
					text-decoration: none;
				}
				.is-style-pill a:hover {
					background-color: var(--wp--preset--color--accent-2);
				}',
		) );

		// List – checkmark style.
		register_block_style( 'core/list', array(
			'name'         => 'checkmark-list',
			'label'        => __( 'Checkmark', 'techstore' ),
			'inline_style' => '
				ul.is-style-checkmark-list {
					list-style-type: "\2713";
				}
				ul.is-style-checkmark-list li {
					padding-inline-start: 1ch;
				}',
		) );

		// Navigation link – with arrow.
		register_block_style( 'core/navigation-link', array(
			'name'         => 'arrow-link',
			'label'        => __( 'With arrow', 'techstore' ),
			'inline_style' => '
				.is-style-arrow-link .wp-block-navigation-item__label:after {
					content: "\2197";
					padding-inline-start: 0.25rem;
					vertical-align: middle;
					text-decoration: none;
					display: inline-block;
				}',
		) );

		// Heading – with tech accent line.
		register_block_style( 'core/heading', array(
			'name'         => 'accent-underline',
			'label'        => __( 'Accent underline', 'techstore' ),
			'inline_style' => '
				.is-style-accent-underline {
					border-bottom: 3px solid var(--wp--preset--color--accent);
					padding-bottom: 0.5rem;
					display: inline-block;
				}',
		) );

		// Heading – with left border (tech section style).
		register_block_style( 'core/heading', array(
			'name'         => 'left-border',
			'label'        => __( 'Left border', 'techstore' ),
			'inline_style' => '
				.is-style-left-border {
					border-left: 4px solid var(--wp--preset--color--accent);
					padding-left: 1rem;
				}',
		) );

		// Button – pill/rounded.
		register_block_style( 'core/button', array(
			'name'         => 'pill',
			'label'        => __( 'Pill', 'techstore' ),
			'inline_style' => '
				.is-style-pill .wp-block-button__link {
					border-radius: 99px;
				}',
		) );
	}
endif;
add_action( 'init', 'techstore_block_styles' );

/**
 * Per-block stylesheet: button outline variant.
 */
if ( ! function_exists( 'techstore_block_stylesheets' ) ) :
	function techstore_block_stylesheets() {
		$outline_css_path = get_template_directory() . '/assets/css/button-outline.css';
		if ( file_exists( $outline_css_path ) ) {
			wp_enqueue_block_style( 'core/button', array(
				'handle' => 'techstore-button-style-outline',
				'src'    => get_template_directory_uri() . '/assets/css/button-outline.css',
				'ver'    => wp_get_theme()->get( 'Version' ),
				'path'   => $outline_css_path,
			) );
		}
	}
endif;
add_action( 'init', 'techstore_block_stylesheets' );

/**
 * Register pattern categories.
 */
if ( ! function_exists( 'techstore_pattern_categories' ) ) :
	function techstore_pattern_categories() {
		register_block_pattern_category( 'techstore_page', array(
			'label'       => _x( 'Pages', 'Block pattern category', 'techstore' ),
			'description' => __( 'Full page layout patterns.', 'techstore' ),
		) );
		register_block_pattern_category( 'techstore_ecommerce', array(
			'label'       => _x( 'Ecommerce', 'Block pattern category', 'techstore' ),
			'description' => __( 'Patterns for product display and shop sections.', 'techstore' ),
		) );
		register_block_pattern_category( 'techstore_blog', array(
			'label'       => _x( 'Blog', 'Block pattern category', 'techstore' ),
			'description' => __( 'Blog listing and post layout patterns.', 'techstore' ),
		) );
		register_block_pattern_category( 'techstore_hero', array(
			'label'       => _x( 'Hero & Banners', 'Block pattern category', 'techstore' ),
			'description' => __( 'Hero sections and promotional banners.', 'techstore' ),
		) );
	}
endif;
add_action( 'init', 'techstore_pattern_categories' );

/**
 * WooCommerce: remove default sidebar hooks (FSE templates handle layout).
 */
if ( class_exists( 'WooCommerce' ) ) {
	remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );
}

/**
 * WooCommerce: customise number of products per page on archive.
 */
add_filter( 'loop_shop_per_page', function() {
	return 12;
}, 20 );

/**
 * WooCommerce: customise columns on shop archive.
 */
add_filter( 'loop_shop_columns', function() {
	return 4;
} );

/**
 * WooCommerce: add sale badge with % off.
 */
add_filter( 'woocommerce_sale_flash', function( $html, $post, $product ) {
	if ( $product->is_type( 'variable' ) ) {
		return $html;
	}
	$regular = (float) $product->get_regular_price();
	$sale    = (float) $product->get_sale_price();
	if ( $regular > 0 && $sale > 0 ) {
		$percent = round( ( $regular - $sale ) / $regular * 100 );
		return '<span class="onsale techstore-badge-sale">-' . $percent . '%</span>';
	}
	return $html;
}, 10, 3 );

/**
 * WooCommerce: breadcrumb defaults.
 */
add_filter( 'woocommerce_breadcrumb_defaults', function( $defaults ) {
	$defaults['delimiter'] = ' &rsaquo; ';
	return $defaults;
} );

/**
 * Custom excerpt length for blog listings.
 */
add_filter( 'excerpt_length', function() {
	return 25;
}, 999 );

add_filter( 'excerpt_more', function() {
	return '&hellip;';
} );

/**
 * Add "New" badge to products added within 30 days.
 */
function techstore_is_new_product( $product ) {
	$created = strtotime( $product->get_date_created() );
	return ( time() - $created ) < ( 30 * DAY_IN_SECONDS );
}
