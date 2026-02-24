<?php
/**
 * Title: Featured Products Grid
 * Slug: techstore/featured-products
 * Categories: techstore_ecommerce, techstore_page
 * Viewport width: 1400
 * Description: A 4-column grid showcasing featured products using WooCommerce product collection.
 */
?>

<!-- wp:group {"align":"full","style":{"color":{"background":"var:preset|color|base-2"},"spacing":{"padding":{"top":"var:preset|spacing|50","bottom":"var:preset|spacing|60","left":"var:preset|spacing|50","right":"var:preset|spacing|50"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull" style="background-color:var(--wp--preset--color--base-2);padding-top:var(--wp--preset--spacing--50);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--60);padding-left:var(--wp--preset--spacing--50)">

	<!-- Section title -->
	<!-- wp:group {"align":"wide","style":{"spacing":{"margin":{"bottom":"var:preset|spacing|40"}}},"layout":{"type":"flex","flexWrap":"wrap","justifyContent":"space-between","verticalAlignment":"center"}} -->
	<div class="wp-block-group alignwide" style="margin-bottom:var(--wp--preset--spacing--40)">
		<!-- wp:group {"layout":{"type":"flex","orientation":"vertical","blockGap":"0.25rem"}} -->
		<div class="wp-block-group">
			<!-- wp:heading {"level":2,"style":{"typography":{"fontWeight":"800","letterSpacing":"-0.02em"}}} -->
			<h2 class="wp-block-heading"><?php echo esc_html_x( 'Featured Products', 'Section heading', 'techstore' ); ?></h2>
			<!-- /wp:heading -->
			<!-- wp:paragraph {"style":{"color":{"text":"var:preset|color|contrast-2"}}} -->
			<p style="color:var(--wp--preset--color--contrast-2)"><?php echo esc_html_x( 'Hand-picked top sellers and new arrivals you\'ll love.', 'Section subtext', 'techstore' ); ?></p>
			<!-- /wp:paragraph -->
		</div>
		<!-- /wp:group -->
		<!-- wp:buttons -->
		<div class="wp-block-buttons">
			<!-- wp:button {"className":"is-style-outline"} -->
			<div class="wp-block-button is-style-outline">
				<a class="wp-block-button__link wp-element-button" href="/shop"><?php echo esc_html_x( 'View All Products →', 'Section link', 'techstore' ); ?></a>
			</div>
			<!-- /wp:button -->
		</div>
		<!-- /wp:buttons -->
	</div>
	<!-- /wp:group -->

	<!-- WooCommerce product collection: featured products -->
	<!-- wp:woocommerce/product-collection {"queryId":10,"query":{"perPage":8,"pages":0,"offset":0,"postType":"product","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"inherit":false,"isProductCollectionBlock":true,"featured":true,"woocommerceOnSale":false,"woocommerceStockStatus":["instock"],"woocommerceAttributes":[],"woocommerceHandPickedProducts":[]},"align":"wide","layout":{"type":"grid","columnCount":4}} -->
	<div class="wp-block-woocommerce-product-collection alignwide">

		<!-- wp:woocommerce/product-template -->

			<!-- wp:group {"style":{"border":{"radius":"0.75rem","width":"1px","color":"var:preset|color|contrast-3"},"color":{"background":"var:preset|color|base-2"},"spacing":{"padding":{"bottom":"var:preset|spacing|20"}}},"layout":{"type":"flex","orientation":"vertical"}} -->
			<div class="wp-block-group" style="border-radius:0.75rem;border:1px solid var(--wp--preset--color--contrast-3);background-color:var(--wp--preset--color--base-2);padding-bottom:var(--wp--preset--spacing--20)">

				<!-- Product image with sale badge overlay -->
				<!-- wp:cover {"useFeaturedImage":true,"minHeight":220,"contentPosition":"top right","style":{"border":{"radius":{"topLeft":"0.75rem","topRight":"0.75rem","bottomLeft":"0","bottomRight":"0"}}}} -->
				<div class="wp-block-cover" style="min-height:220px;border-radius:0.75rem 0.75rem 0 0">
					<span aria-hidden="true" class="wp-block-cover__background has-background-dim"></span>
					<div class="wp-block-cover__inner-container">
						<!-- wp:woocommerce/product-sale-badge {"style":{"typography":{"fontSize":"0.7rem","fontWeight":"700"}}} /-->
					</div>
				</div>
				<!-- /wp:cover -->

				<!-- wp:woocommerce/product-image {"aspectRatio":"1","imageSizing":"thumbnail","showProductLink":true,"style":{"border":{"radius":{"topLeft":"0.75rem","topRight":"0.75rem","bottomLeft":"0","bottomRight":"0"}},"spacing":{"margin":{"top":"-220px"}}}} /-->

				<!-- Product info -->
				<!-- wp:group {"style":{"spacing":{"padding":{"left":"var:preset|spacing|20","right":"var:preset|spacing|20","top":"var:preset|spacing|20"},"blockGap":"var:preset|spacing|10"}},"layout":{"type":"flex","orientation":"vertical"}} -->
				<div class="wp-block-group" style="padding:var(--wp--preset--spacing--20) var(--wp--preset--spacing--20) 0">
					<!-- wp:post-terms {"term":"product_cat","style":{"typography":{"fontSize":"0.75rem"},"color":{"text":"var:preset|color|accent"}}} /-->
					<!-- wp:post-title {"level":4,"isLink":true,"style":{"typography":{"fontWeight":"600","fontSize":"0.95rem","lineHeight":"1.3"},"elements":{"link":{"color":{"text":"var:preset|color|contrast"},":hover":{"color":{"text":"var:preset|color|accent"}}}}}} /-->
					<!-- wp:woocommerce/product-rating {"style":{"typography":{"fontSize":"0.75rem"}}} /-->
					<!-- wp:woocommerce/product-price {"style":{"typography":{"fontWeight":"700","fontSize":"1.05rem"}}} /-->
					<!-- wp:woocommerce/add-to-cart-button {"textAlign":"center","style":{"spacing":{"padding":{"top":"0.6rem","bottom":"0.6rem"}},"typography":{"fontSize":"0.85rem"}}} /-->
				</div>
				<!-- /wp:group -->

			</div>
			<!-- /wp:group -->

		<!-- /wp:woocommerce/product-template -->

		<!-- wp:query-no-results -->
		<!-- wp:paragraph {"align":"center"} -->
		<p class="has-text-align-center"><?php echo esc_html_x( 'No featured products yet. Mark products as featured in WooCommerce.', 'No products message', 'techstore' ); ?></p>
		<!-- /wp:paragraph -->
		<!-- /wp:query-no-results -->

	</div>
	<!-- /wp:woocommerce/product-collection -->

</div>
<!-- /wp:group -->
