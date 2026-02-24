<?php
/**
 * Title: Promotional Banner
 * Slug: techstore/promo-banner
 * Categories: techstore_hero, techstore_ecommerce, banner
 * Viewport width: 1400
 * Description: A full-width promotional/sale banner with countdown-style urgency text.
 */
?>

<!-- wp:group {"align":"full","style":{"color":{"background":"#1d4ed8","text":"#ffffff"},"spacing":{"padding":{"top":"var:preset|spacing|50","bottom":"var:preset|spacing|50","left":"var:preset|spacing|50","right":"var:preset|spacing|50"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull" style="background-color:#1d4ed8;color:#ffffff;padding:var(--wp--preset--spacing--50)">

	<!-- wp:group {"align":"wide","layout":{"type":"flex","flexWrap":"wrap","justifyContent":"space-between","verticalAlignment":"center"}} -->
	<div class="wp-block-group alignwide">

		<!-- Left: Promo text -->
		<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|10"}},"layout":{"type":"flex","orientation":"vertical"}} -->
		<div class="wp-block-group">
			<!-- wp:paragraph {"style":{"typography":{"fontSize":"0.85rem","fontWeight":"700","letterSpacing":"0.12em","textTransform":"uppercase"},"color":{"text":"#93c5fd"}}} -->
			<p style="font-size:0.85rem;font-weight:700;letter-spacing:0.12em;text-transform:uppercase;color:#93c5fd">🔥 <?php echo esc_html_x( 'Limited Time Deal', 'Promo badge', 'techstore' ); ?></p>
			<!-- /wp:paragraph -->
			<!-- wp:heading {"level":2,"style":{"typography":{"fontWeight":"900","fontSize":"clamp(1.5rem,3vw,2.25rem)","letterSpacing":"-0.02em"},"color":{"text":"#ffffff"}}} -->
			<h2 class="wp-block-heading" style="color:#ffffff;font-weight:900;letter-spacing:-0.02em"><?php echo esc_html_x( 'Summer Tech Sale — Up to 40% Off', 'Promo headline', 'techstore' ); ?></h2>
			<!-- /wp:heading -->
			<!-- wp:paragraph {"style":{"color":{"text":"#bfdbfe"}}} -->
			<p style="color:#bfdbfe"><?php echo esc_html_x( 'Shop the biggest discounts of the year on top-brand electronics. Offer ends soon.', 'Promo subtext', 'techstore' ); ?></p>
			<!-- /wp:paragraph -->
		</div>
		<!-- /wp:group -->

		<!-- Right: CTA + Badges -->
		<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"flex","orientation":"vertical","verticalAlignment":"center"}} -->
		<div class="wp-block-group">
			<!-- wp:buttons {"layout":{"type":"flex"}} -->
			<div class="wp-block-buttons">
				<!-- wp:button {"style":{"color":{"background":"#ffffff","text":"#1d4ed8"},"typography":{"fontWeight":"700","fontSize":"1rem"},"spacing":{"padding":{"top":"0.85rem","bottom":"0.85rem","left":"2rem","right":"2rem"}},"border":{"radius":"0.4rem"}}} -->
				<div class="wp-block-button">
					<a class="wp-block-button__link wp-element-button" href="/shop" style="background-color:#ffffff;color:#1d4ed8;font-weight:700;font-size:1rem;padding:0.85rem 2rem;border-radius:0.4rem"><?php echo esc_html_x( 'Shop the Sale →', 'Promo CTA', 'techstore' ); ?></a>
				</div>
				<!-- /wp:button -->
			</div>
			<!-- /wp:buttons -->
			<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"flex","flexWrap":"wrap"}} -->
			<div class="wp-block-group">
				<!-- wp:paragraph {"style":{"typography":{"fontSize":"0.8rem"},"color":{"text":"#93c5fd"}}} -->
				<p style="font-size:0.8rem;color:#93c5fd">✓ Free Shipping</p>
				<!-- /wp:paragraph -->
				<!-- wp:paragraph {"style":{"typography":{"fontSize":"0.8rem"},"color":{"text":"#93c5fd"}}} -->
				<p style="font-size:0.8rem;color:#93c5fd">✓ Easy Returns</p>
				<!-- /wp:paragraph -->
				<!-- wp:paragraph {"style":{"typography":{"fontSize":"0.8rem"},"color":{"text":"#93c5fd"}}} -->
				<p style="font-size:0.8rem;color:#93c5fd">✓ Secure Payment</p>
				<!-- /wp:paragraph -->
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:group -->

	</div>
	<!-- /wp:group -->

</div>
<!-- /wp:group -->
