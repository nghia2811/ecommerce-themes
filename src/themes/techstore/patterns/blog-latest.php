<?php
/**
 * Title: Latest Blog Posts
 * Slug: techstore/blog-latest
 * Categories: techstore_blog, techstore_page
 * Viewport width: 1400
 * Description: A 3-column grid of the most recent blog posts.
 */
?>

<!-- wp:group {"align":"full","style":{"color":{"background":"var:preset|color|base"},"spacing":{"padding":{"top":"var:preset|spacing|50","bottom":"var:preset|spacing|60","left":"var:preset|spacing|50","right":"var:preset|spacing|50"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull" style="background-color:var(--wp--preset--color--base);padding:var(--wp--preset--spacing--50) var(--wp--preset--spacing--50) var(--wp--preset--spacing--60)">

	<!-- Section title -->
	<!-- wp:group {"align":"wide","style":{"spacing":{"margin":{"bottom":"var:preset|spacing|40"}}},"layout":{"type":"flex","flexWrap":"wrap","justifyContent":"space-between","verticalAlignment":"center"}} -->
	<div class="wp-block-group alignwide" style="margin-bottom:var(--wp--preset--spacing--40)">
		<!-- wp:group {"layout":{"type":"flex","orientation":"vertical","blockGap":"0.25rem"}} -->
		<div class="wp-block-group">
			<!-- wp:heading {"level":2,"style":{"typography":{"fontWeight":"800","letterSpacing":"-0.02em"}}} -->
			<h2 class="wp-block-heading"><?php echo esc_html_x( 'From the Tech Blog', 'Section heading', 'techstore' ); ?></h2>
			<!-- /wp:heading -->
			<!-- wp:paragraph {"style":{"color":{"text":"var:preset|color|contrast-2"}}} -->
			<p style="color:var(--wp--preset--color--contrast-2)"><?php echo esc_html_x( 'Reviews, guides, and tech news to help you make smarter choices.', 'Section subtext', 'techstore' ); ?></p>
			<!-- /wp:paragraph -->
		</div>
		<!-- /wp:group -->
		<!-- wp:buttons -->
		<div class="wp-block-buttons">
			<!-- wp:button {"className":"is-style-outline"} -->
			<div class="wp-block-button is-style-outline">
				<a class="wp-block-button__link wp-element-button" href="/blog"><?php echo esc_html_x( 'All Articles →', 'Section link', 'techstore' ); ?></a>
			</div>
			<!-- /wp:button -->
		</div>
		<!-- /wp:buttons -->
	</div>
	<!-- /wp:group -->

	<!-- Query: latest 3 posts -->
	<!-- wp:query {"queryId":20,"query":{"perPage":3,"pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"exclude","inherit":false},"align":"wide"} -->
	<div class="wp-block-query alignwide">

		<!-- wp:post-template {"style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"grid","columnCount":3}} -->

			<!-- wp:group {"style":{"border":{"radius":"0.75rem","width":"1px","color":"var:preset|color|contrast-3"},"color":{"background":"var:preset|color|base-2"},"spacing":{"padding":{"bottom":"var:preset|spacing|30"}}},"layout":{"type":"flex","orientation":"vertical"}} -->
			<div class="wp-block-group" style="border-radius:0.75rem;border:1px solid var(--wp--preset--color--contrast-3);background-color:var(--wp--preset--color--base-2);padding-bottom:var(--wp--preset--spacing--30)">
				<!-- wp:post-featured-image {"isLink":true,"aspectRatio":"16/9","style":{"border":{"radius":{"topLeft":"0.75rem","topRight":"0.75rem","bottomLeft":"0","bottomRight":"0"}}}} /-->
				<!-- wp:group {"style":{"spacing":{"padding":{"left":"var:preset|spacing|30","right":"var:preset|spacing|30","top":"var:preset|spacing|20"},"blockGap":"var:preset|spacing|10"}},"layout":{"type":"flex","orientation":"vertical"}} -->
				<div class="wp-block-group" style="padding:var(--wp--preset--spacing--20) var(--wp--preset--spacing--30) 0">
					<!-- wp:post-terms {"term":"category","className":"is-style-pill"} /-->
					<!-- wp:post-title {"level":3,"isLink":true,"style":{"typography":{"fontWeight":"700","lineHeight":"1.3","fontSize":"1rem"},"elements":{"link":{"color":{"text":"var:preset|color|contrast"},":hover":{"color":{"text":"var:preset|color|accent"}}}}}} /-->
					<!-- wp:post-excerpt {"excerptLength":18} /-->
					<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|10"}},"layout":{"type":"flex","flexWrap":"wrap","verticalAlignment":"center"}} -->
					<div class="wp-block-group">
						<!-- wp:post-date {"style":{"typography":{"fontSize":"0.8rem"},"color":{"text":"var:preset|color|contrast-2"}}} /-->
						<!-- wp:read-more {"content":"Read more \u2192","style":{"typography":{"fontSize":"0.8rem","fontWeight":"600"},"color":{"text":"var:preset|color|accent"}}} /-->
					</div>
					<!-- /wp:group -->
				</div>
				<!-- /wp:group -->
			</div>
			<!-- /wp:group -->

		<!-- /wp:post-template -->

	</div>
	<!-- /wp:query -->

</div>
<!-- /wp:group -->
