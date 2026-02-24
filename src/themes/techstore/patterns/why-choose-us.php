<?php
/**
 * Title: Why Choose Us – Feature Grid
 * Slug: techstore/why-choose-us
 * Categories: techstore_page
 * Viewport width: 1400
 * Description: A 4-column feature benefits section for ecommerce trust-building.
 */
?>

<!-- wp:group {"align":"full","style":{"color":{"background":"var:preset|color|base"},"spacing":{"padding":{"top":"var:preset|spacing|50","bottom":"var:preset|spacing|50","left":"var:preset|spacing|50","right":"var:preset|spacing|50"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull" style="background-color:var(--wp--preset--color--base);padding:var(--wp--preset--spacing--50)">

	<!-- Section heading -->
	<!-- wp:group {"align":"wide","style":{"spacing":{"margin":{"bottom":"var:preset|spacing|40"}}},"layout":{"type":"flex","orientation":"vertical","verticalAlignment":"center"}} -->
	<div class="wp-block-group alignwide" style="margin-bottom:var(--wp--preset--spacing--40)">
		<!-- wp:heading {"level":2,"textAlign":"center","style":{"typography":{"fontWeight":"800","letterSpacing":"-0.02em"}}} -->
		<h2 class="wp-block-heading has-text-align-center"><?php echo esc_html_x( 'Why Shop With TechStore?', 'Section heading', 'techstore' ); ?></h2>
		<!-- /wp:heading -->
		<!-- wp:paragraph {"align":"center","style":{"color":{"text":"var:preset|color|contrast-2"}}} -->
		<p class="has-text-align-center" style="color:var(--wp--preset--color--contrast-2)"><?php echo esc_html_x( 'We\'re committed to delivering the best tech shopping experience.', 'Section subtext', 'techstore' ); ?></p>
		<!-- /wp:paragraph -->
	</div>
	<!-- /wp:group -->

	<!-- Feature columns -->
	<!-- wp:columns {"align":"wide","isStackedOnMobile":false,"style":{"spacing":{"blockGap":{"left":"var:preset|spacing|30"}}}} -->
	<div class="wp-block-columns alignwide">

		<?php
		$features = array(
			array( 'icon' => '🚀', 'title' => 'Fast Delivery', 'desc' => 'Same-day dispatch on orders placed before 2 PM. Free delivery on orders over $50.' ),
			array( 'icon' => '🛡️', 'title' => 'Secure Shopping', 'desc' => 'SSL-encrypted checkout, trusted payment gateways, and buyer protection on every order.' ),
			array( 'icon' => '💯', 'title' => 'Genuine Products', 'desc' => 'Every product is 100% authentic, sourced directly from authorised brand distributors.' ),
			array( 'icon' => '🔧', 'title' => 'Expert Support', 'desc' => 'Our tech-savvy support team is available 7 days a week to help you find the perfect gear.' ),
		);
		foreach ( $features as $feature ) :
		?>
		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:group {"style":{"border":{"radius":"0.75rem","width":"1px","color":"var:preset|color|contrast-3"},"color":{"background":"var:preset|color|base-2"},"spacing":{"padding":{"top":"var:preset|spacing|40","bottom":"var:preset|spacing|40","left":"var:preset|spacing|30","right":"var:preset|spacing|30"}}},"layout":{"type":"flex","orientation":"vertical","blockGap":"var:preset|spacing|20"}} -->
			<div class="wp-block-group" style="border-radius:0.75rem;border:1px solid var(--wp--preset--color--contrast-3);background-color:var(--wp--preset--color--base-2);padding:var(--wp--preset--spacing--40) var(--wp--preset--spacing--30)">
				<!-- wp:heading {"level":2,"style":{"typography":{"fontSize":"2.5rem"}}} -->
				<h2 class="wp-block-heading" style="font-size:2.5rem"><?php echo esc_html( $feature['icon'] ); ?></h2>
				<!-- /wp:heading -->
				<!-- wp:heading {"level":4,"style":{"typography":{"fontWeight":"700"}}} -->
				<h4 class="wp-block-heading" style="font-weight:700"><?php echo esc_html_x( $feature['title'], 'Feature card title', 'techstore' ); ?></h4>
				<!-- /wp:heading -->
				<!-- wp:paragraph {"style":{"color":{"text":"var:preset|color|contrast-2"},"typography":{"lineHeight":"1.6"}}} -->
				<p style="color:var(--wp--preset--color--contrast-2);line-height:1.6"><?php echo esc_html_x( $feature['desc'], 'Feature card description', 'techstore' ); ?></p>
				<!-- /wp:paragraph -->
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:column -->
		<?php endforeach; ?>

	</div>
	<!-- /wp:columns -->

</div>
<!-- /wp:group -->
