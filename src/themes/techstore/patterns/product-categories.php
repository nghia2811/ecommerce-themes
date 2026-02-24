<?php
/**
 * Title: Product Categories Showcase
 * Slug: techstore/product-categories
 * Categories: techstore_ecommerce, techstore_page
 * Viewport width: 1400
 * Description: A grid of product category cards for the homepage.
 */
?>

<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|50","bottom":"var:preset|spacing|50","left":"var:preset|spacing|50","right":"var:preset|spacing|50"}},"color":{"background":"var:preset|color|base"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull" style="background-color:var(--wp--preset--color--base);padding-top:var(--wp--preset--spacing--50);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--50);padding-left:var(--wp--preset--spacing--50)">

	<!-- Section title -->
	<!-- wp:group {"align":"wide","style":{"spacing":{"blockGap":"var:preset|spacing|10","margin":{"bottom":"var:preset|spacing|40"}}},"layout":{"type":"flex","flexWrap":"wrap","justifyContent":"space-between","verticalAlignment":"center"}} -->
	<div class="wp-block-group alignwide" style="margin-bottom:var(--wp--preset--spacing--40)">
		<!-- wp:group {"layout":{"type":"flex","orientation":"vertical","blockGap":"0.25rem"}} -->
		<div class="wp-block-group">
			<!-- wp:heading {"level":2,"style":{"typography":{"fontWeight":"800","letterSpacing":"-0.02em"}}} -->
			<h2 class="wp-block-heading"><?php echo esc_html_x( 'Shop by Category', 'Section heading', 'techstore' ); ?></h2>
			<!-- /wp:heading -->
			<!-- wp:paragraph {"style":{"color":{"text":"var:preset|color|contrast-2"}}} -->
			<p style="color:var(--wp--preset--color--contrast-2)"><?php echo esc_html_x( 'Find exactly what you need from our curated tech collection.', 'Section subtext', 'techstore' ); ?></p>
			<!-- /wp:paragraph -->
		</div>
		<!-- /wp:group -->
		<!-- wp:buttons -->
		<div class="wp-block-buttons">
			<!-- wp:button {"className":"is-style-outline"} -->
			<div class="wp-block-button is-style-outline">
				<a class="wp-block-button__link wp-element-button" href="/shop"><?php echo esc_html_x( 'View All Categories →', 'Section link', 'techstore' ); ?></a>
			</div>
			<!-- /wp:button -->
		</div>
		<!-- /wp:buttons -->
	</div>
	<!-- /wp:group -->

	<!-- Category cards grid -->
	<!-- wp:columns {"align":"wide","isStackedOnMobile":false,"style":{"spacing":{"blockGap":{"left":"var:preset|spacing|20","top":"var:preset|spacing|20"}}}} -->
	<div class="wp-block-columns alignwide">

		<?php
		$categories = array(
			array( 'emoji' => '📱', 'name' => 'Smartphones', 'desc' => 'Latest models & deals', 'color' => '#eff6ff', 'accent' => '#3b82f6', 'slug' => 'smartphones' ),
			array( 'emoji' => '💻', 'name' => 'Laptops', 'desc' => 'Work, gaming & more', 'color' => '#f0fdf4', 'accent' => '#22c55e', 'slug' => 'laptops' ),
			array( 'emoji' => '🎧', 'name' => 'Audio', 'desc' => 'Headphones, speakers', 'color' => '#fdf4ff', 'accent' => '#a855f7', 'slug' => 'audio' ),
			array( 'emoji' => '🖥️', 'name' => 'Monitors', 'desc' => 'HD, 4K & ultrawide', 'color' => '#fff7ed', 'accent' => '#f97316', 'slug' => 'monitors' ),
			array( 'emoji' => '🏠', 'name' => 'Smart Home', 'desc' => 'Automate your space', 'color' => '#ecfdf5', 'accent' => '#06b6d4', 'slug' => 'smart-home' ),
			array( 'emoji' => '🎮', 'name' => 'Gaming', 'desc' => 'Consoles & accessories', 'color' => '#fef2f2', 'accent' => '#ef4444', 'slug' => 'gaming' ),
		);
		foreach ( $categories as $cat ) :
		?>
		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:group {"style":{"border":{"radius":"0.75rem","width":"1px","color":"<?php echo esc_attr( $cat['color'] ); ?>"},"color":{"background":"<?php echo esc_attr( $cat['color'] ); ?>"},"spacing":{"padding":{"top":"var:preset|spacing|30","bottom":"var:preset|spacing|30","left":"var:preset|spacing|30","right":"var:preset|spacing|30"}}}} -->
			<div class="wp-block-group" style="border-radius:0.75rem;background-color:<?php echo esc_attr( $cat['color'] ); ?>;padding:var(--wp--preset--spacing--30)">
				<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"flex","orientation":"vertical"}} -->
				<div class="wp-block-group">
					<!-- wp:heading {"level":2,"style":{"typography":{"fontSize":"2.5rem"}}} -->
					<h2 class="wp-block-heading" style="font-size:2.5rem"><?php echo esc_html( $cat['emoji'] ); ?></h2>
					<!-- /wp:heading -->
					<!-- wp:heading {"level":4,"style":{"typography":{"fontWeight":"700"}}} -->
					<h4 class="wp-block-heading" style="font-weight:700"><?php echo esc_html( $cat['name'] ); ?></h4>
					<!-- /wp:heading -->
					<!-- wp:paragraph {"style":{"typography":{"fontSize":"0.875rem"},"color":{"text":"var:preset|color|contrast-2"}}} -->
					<p style="font-size:0.875rem;color:var(--wp--preset--color--contrast-2)"><?php echo esc_html( $cat['desc'] ); ?></p>
					<!-- /wp:paragraph -->
					<!-- wp:paragraph {"style":{"typography":{"fontSize":"0.875rem","fontWeight":"600"},"color":{"text":"<?php echo esc_attr( $cat['accent'] ); ?>"}}} -->
					<p><a href="/product-category/<?php echo esc_attr( $cat['slug'] ); ?>" style="font-size:0.875rem;font-weight:600;color:<?php echo esc_attr( $cat['accent'] ); ?>;text-decoration:none"><?php echo esc_html_x( 'Shop Now →', 'Category card link', 'techstore' ); ?></a></p>
					<!-- /wp:paragraph -->
				</div>
				<!-- /wp:group -->
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:column -->
		<?php endforeach; ?>

	</div>
	<!-- /wp:columns -->

</div>
<!-- /wp:group -->
