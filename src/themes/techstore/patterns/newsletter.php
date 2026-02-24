<?php
/**
 * Title: Newsletter Signup
 * Slug: techstore/newsletter
 * Categories: techstore_page, call-to-action
 * Viewport width: 1400
 * Description: A centered newsletter signup section with email input and trust copy.
 */
?>

<!-- wp:group {"align":"full","style":{"color":{"background":"var:preset|color|base-2"},"spacing":{"padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|60","left":"var:preset|spacing|50","right":"var:preset|spacing|50"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull" style="background-color:var(--wp--preset--color--base-2);padding:var(--wp--preset--spacing--60) var(--wp--preset--spacing--50)">

	<!-- wp:group {"layout":{"type":"constrained","contentSize":"620px"}} -->
	<div class="wp-block-group">

		<!-- wp:group {"style":{"border":{"radius":"1.5rem","width":"2px","color":"var:preset|color|accent"},"spacing":{"padding":{"top":"var:preset|spacing|50","bottom":"var:preset|spacing|50","left":"var:preset|spacing|50","right":"var:preset|spacing|50"}}},"layout":{"type":"flex","orientation":"vertical","verticalAlignment":"center"},"textColor":"contrast","backgroundColor":"base"} -->
		<div class="wp-block-group has-contrast-color has-text-color has-base-background-color has-background" style="border-radius:1.5rem;border:2px solid var(--wp--preset--color--accent);padding:var(--wp--preset--spacing--50)">

			<!-- wp:paragraph {"align":"center","style":{"typography":{"fontSize":"0.8rem","fontWeight":"700","letterSpacing":"0.12em","textTransform":"uppercase"},"color":{"text":"var:preset|color|accent"}}} -->
			<p class="has-text-align-center" style="font-size:0.8rem;font-weight:700;letter-spacing:0.12em;text-transform:uppercase;color:var(--wp--preset--color--accent)"><?php echo esc_html_x( '📧 Stay in the Loop', 'Newsletter label', 'techstore' ); ?></p>
			<!-- /wp:paragraph -->

			<!-- wp:heading {"level":2,"textAlign":"center","style":{"typography":{"fontWeight":"800","letterSpacing":"-0.02em"}}} -->
			<h2 class="wp-block-heading has-text-align-center"><?php echo esc_html_x( 'Get Exclusive Tech Deals First', 'Newsletter heading', 'techstore' ); ?></h2>
			<!-- /wp:heading -->

			<!-- wp:paragraph {"align":"center","style":{"color":{"text":"var:preset|color|contrast-2"}}} -->
			<p class="has-text-align-center" style="color:var(--wp--preset--color--contrast-2)"><?php echo esc_html_x( 'Subscribe to our newsletter and be the first to hear about flash sales, new arrivals, and tech tips. No spam, ever.', 'Newsletter subtext', 'techstore' ); ?></p>
			<!-- /wp:paragraph -->

			<!-- wp:spacer {"height":"1rem"} -->
			<div style="height:1rem" aria-hidden="true" class="wp-block-spacer"></div>
			<!-- /wp:spacer -->

			<!-- wp:search {"label":"Newsletter signup","showLabel":false,"placeholder":"Enter your email address\u2026","buttonText":"Subscribe","buttonPosition":"button-outside","style":{"border":{"radius":"0.4rem"}}} /-->

			<!-- wp:paragraph {"align":"center","style":{"typography":{"fontSize":"0.8rem"},"color":{"text":"var:preset|color|contrast-3"}}} -->
			<p class="has-text-align-center" style="font-size:0.8rem;color:var(--wp--preset--color--contrast-3)"><?php echo esc_html_x( 'By subscribing you agree to our Privacy Policy. Unsubscribe any time.', 'Newsletter disclaimer', 'techstore' ); ?></p>
			<!-- /wp:paragraph -->

		</div>
		<!-- /wp:group -->

	</div>
	<!-- /wp:group -->

</div>
<!-- /wp:group -->
