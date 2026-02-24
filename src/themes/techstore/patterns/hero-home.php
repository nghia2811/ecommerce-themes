<?php
/**
 * Title: Hero – Home
 * Slug: techstore/hero-home
 * Categories: techstore_hero, techstore_page, banner
 * Viewport width: 1400
 * Description: Full-width homepage hero with headline, subtext, CTA buttons, and a product showcase image.
 */
?>

<!-- wp:group {"align":"full","style":{"color":{"background":"#0f172a","text":"#f8fafc"},"spacing":{"padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|60","left":"var:preset|spacing|50","right":"var:preset|spacing|50"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull" style="background-color:#0f172a;color:#f8fafc;padding-top:var(--wp--preset--spacing--60);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--60);padding-left:var(--wp--preset--spacing--50)">

	<!-- wp:columns {"align":"wide","verticalAlignment":"center","isStackedOnMobile":true,"style":{"spacing":{"blockGap":{"left":"var:preset|spacing|50"}}}} -->
	<div class="wp-block-columns alignwide are-vertically-aligned-center">

		<!-- Left: Copy -->
		<!-- wp:column {"verticalAlignment":"center","width":"55%"} -->
		<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:55%">

			<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"flex","orientation":"vertical"}} -->
			<div class="wp-block-group">

				<!-- wp:paragraph {"style":{"typography":{"fontSize":"0.8rem","fontWeight":"700","letterSpacing":"0.15em","textTransform":"uppercase"},"color":{"text":"var:preset|color|accent"}}} -->
				<p style="font-size:0.8rem;font-weight:700;letter-spacing:0.15em;text-transform:uppercase;color:var(--wp--preset--color--accent)"><?php echo esc_html_x( '⚡ Premium Electronics &amp; Tech', 'Hero tag label', 'techstore' ); ?></p>
				<!-- /wp:paragraph -->

				<!-- wp:heading {"level":1,"style":{"typography":{"fontWeight":"900","lineHeight":"1.05","letterSpacing":"-0.03em"},"color":{"text":"#ffffff"}}} -->
				<h1 class="wp-block-heading" style="color:#ffffff;font-weight:900;line-height:1.05;letter-spacing:-0.03em"><?php echo esc_html_x( 'Next-Level Tech. Unbeatable Prices.', 'Hero headline', 'techstore' ); ?></h1>
				<!-- /wp:heading -->

				<!-- wp:paragraph {"style":{"typography":{"fontSize":"1.1rem","lineHeight":"1.6"},"color":{"text":"#94a3b8"}}} -->
				<p style="font-size:1.1rem;line-height:1.6;color:#94a3b8"><?php echo esc_html_x( 'Discover the latest smartphones, laptops, audio gear, and smart home devices — all curated for tech enthusiasts who demand the best.', 'Hero subtext', 'techstore' ); ?></p>
				<!-- /wp:paragraph -->

				<!-- wp:buttons {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}}} -->
				<div class="wp-block-buttons">
					<!-- wp:button {"style":{"color":{"background":"var:preset|color|accent","text":"#ffffff"},"typography":{"fontSize":"1rem","fontWeight":"600"},"spacing":{"padding":{"top":"0.85rem","bottom":"0.85rem","left":"2rem","right":"2rem"}}}} -->
					<div class="wp-block-button">
						<a class="wp-block-button__link wp-element-button" href="/shop" style="background-color:var(--wp--preset--color--accent);color:#ffffff;font-size:1rem;font-weight:600;padding:0.85rem 2rem"><?php echo esc_html_x( 'Shop Now', 'Hero CTA primary', 'techstore' ); ?></a>
					</div>
					<!-- /wp:button -->
					<!-- wp:button {"className":"is-style-outline","style":{"border":{"color":"#475569","width":"1px"},"color":{"text":"#cbd5e1"},"typography":{"fontSize":"1rem"},"spacing":{"padding":{"top":"0.85rem","bottom":"0.85rem","left":"2rem","right":"2rem"}}}} -->
					<div class="wp-block-button is-style-outline">
						<a class="wp-block-button__link wp-element-button" href="/blog" style="border:1px solid #475569;color:#cbd5e1;font-size:1rem;padding:0.85rem 2rem"><?php echo esc_html_x( 'Read Blog', 'Hero CTA secondary', 'techstore' ); ?></a>
					</div>
					<!-- /wp:button -->
				</div>
				<!-- /wp:buttons -->

				<!-- Trust stats row -->
				<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"flex","flexWrap":"wrap"}} -->
				<div class="wp-block-group">
					<!-- wp:group {"style":{"spacing":{"blockGap":"0.25rem"}},"layout":{"type":"flex","orientation":"vertical"}} -->
					<div class="wp-block-group">
						<!-- wp:heading {"level":4,"style":{"typography":{"fontWeight":"800","fontSize":"1.5rem"},"color":{"text":"#ffffff"}}} -->
						<h4 class="wp-block-heading" style="color:#ffffff;font-weight:800;font-size:1.5rem">50K+</h4>
						<!-- /wp:heading -->
						<!-- wp:paragraph {"style":{"typography":{"fontSize":"0.8rem"},"color":{"text":"#64748b"}}} -->
						<p style="font-size:0.8rem;color:#64748b">Happy Customers</p>
						<!-- /wp:paragraph -->
					</div>
					<!-- /wp:group -->
					<!-- wp:group {"style":{"spacing":{"blockGap":"0.25rem"}},"layout":{"type":"flex","orientation":"vertical"}} -->
					<div class="wp-block-group">
						<!-- wp:heading {"level":4,"style":{"typography":{"fontWeight":"800","fontSize":"1.5rem"},"color":{"text":"#ffffff"}}} -->
						<h4 class="wp-block-heading" style="color:#ffffff;font-weight:800;font-size:1.5rem">2,000+</h4>
						<!-- /wp:heading -->
						<!-- wp:paragraph {"style":{"typography":{"fontSize":"0.8rem"},"color":{"text":"#64748b"}}} -->
						<p style="font-size:0.8rem;color:#64748b">Products</p>
						<!-- /wp:paragraph -->
					</div>
					<!-- /wp:group -->
					<!-- wp:group {"style":{"spacing":{"blockGap":"0.25rem"}},"layout":{"type":"flex","orientation":"vertical"}} -->
					<div class="wp-block-group">
						<!-- wp:heading {"level":4,"style":{"typography":{"fontWeight":"800","fontSize":"1.5rem"},"color":{"text":"#ffffff"}}} -->
						<h4 class="wp-block-heading" style="color:#ffffff;font-weight:800;font-size:1.5rem">4.9★</h4>
						<!-- /wp:heading -->
						<!-- wp:paragraph {"style":{"typography":{"fontSize":"0.8rem"},"color":{"text":"#64748b"}}} -->
						<p style="font-size:0.8rem;color:#64748b">Avg. Rating</p>
						<!-- /wp:paragraph -->
					</div>
					<!-- /wp:group -->
				</div>
				<!-- /wp:group -->

			</div>
			<!-- /wp:group -->

		</div>
		<!-- /wp:column -->

		<!-- Right: Hero image -->
		<!-- wp:column {"verticalAlignment":"center","width":"45%"} -->
		<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:45%">
			<!-- wp:image {"align":"center","sizeSlug":"large","linkDestination":"none","style":{"border":{"radius":"1.5rem"}}} -->
			<figure class="wp-block-image aligncenter size-large" style="border-radius:1.5rem">
				<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/hero-product.webp' ); ?>" alt="<?php esc_attr_e( 'Latest tech products collection', 'techstore' ); ?>" />
			</figure>
			<!-- /wp:image -->
		</div>
		<!-- /wp:column -->

	</div>
	<!-- /wp:columns -->

</div>
<!-- /wp:group -->
