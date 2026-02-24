<?php
/**
 * Title: Customer Testimonials
 * Slug: techstore/testimonials
 * Categories: techstore_page
 * Viewport width: 1400
 * Description: Three customer reviews in a 3-column card layout.
 */
?>

<!-- wp:group {"align":"full","style":{"color":{"background":"var:preset|color|contrast","text":"var:preset|color|base"},"spacing":{"padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|60","left":"var:preset|spacing|50","right":"var:preset|spacing|50"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull" style="background-color:var(--wp--preset--color--contrast);color:var(--wp--preset--color--base);padding:var(--wp--preset--spacing--60) var(--wp--preset--spacing--50)">

	<!-- Heading -->
	<!-- wp:group {"align":"wide","style":{"spacing":{"margin":{"bottom":"var:preset|spacing|50"}}},"layout":{"type":"flex","orientation":"vertical","verticalAlignment":"center"}} -->
	<div class="wp-block-group alignwide" style="margin-bottom:var(--wp--preset--spacing--50)">
		<!-- wp:heading {"level":2,"textAlign":"center","style":{"typography":{"fontWeight":"800","letterSpacing":"-0.02em"},"color":{"text":"#ffffff"}}} -->
		<h2 class="wp-block-heading has-text-align-center" style="color:#ffffff"><?php echo esc_html_x( 'What Our Customers Say', 'Section heading', 'techstore' ); ?></h2>
		<!-- /wp:heading -->
		<!-- wp:paragraph {"align":"center","style":{"color":{"text":"#94a3b8"}}} -->
		<p class="has-text-align-center" style="color:#94a3b8"><?php echo esc_html_x( 'Real reviews from real tech lovers.', 'Section subtext', 'techstore' ); ?></p>
		<!-- /wp:paragraph -->
	</div>
	<!-- /wp:group -->

	<!-- Testimonial cards -->
	<!-- wp:columns {"align":"wide","isStackedOnMobile":false,"style":{"spacing":{"blockGap":{"left":"var:preset|spacing|30"}}}} -->
	<div class="wp-block-columns alignwide">

		<?php
		$testimonials = array(
			array(
				'quote'  => 'The laptop I ordered arrived the next day, in perfect condition. Setup was a breeze and performance is incredible. Will definitely shop here again!',
				'name'   => 'Alex M.',
				'title'  => 'Software Developer',
				'rating' => '★★★★★',
				'product' => 'MacBook Pro 14"',
			),
			array(
				'quote'  => 'Best prices I found anywhere online. The noise-cancelling headphones are a game-changer for my commute. Customer service was super helpful too.',
				'name'   => 'Sarah K.',
				'title'  => 'Product Designer',
				'rating' => '★★★★★',
				'product' => 'Sony WH-1000XM5',
			),
			array(
				'quote'  => 'I\'ve ordered three times and every experience has been flawless. The product descriptions are accurate, and shipping is blazing fast.',
				'name'   => 'James T.',
				'title'  => 'IT Manager',
				'rating' => '★★★★★',
				'product' => 'Repeat Customer',
			),
		);
		foreach ( $testimonials as $t ) :
		?>
		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:group {"style":{"border":{"radius":"0.75rem","color":"#1e293b","width":"1px"},"color":{"background":"#1e293b"},"spacing":{"padding":{"top":"var:preset|spacing|40","bottom":"var:preset|spacing|40","left":"var:preset|spacing|30","right":"var:preset|spacing|30"}}},"layout":{"type":"flex","orientation":"vertical","blockGap":"var:preset|spacing|20"}} -->
			<div class="wp-block-group" style="border-radius:0.75rem;border:1px solid #1e293b;background-color:#1e293b;padding:var(--wp--preset--spacing--40) var(--wp--preset--spacing--30)">

				<!-- Stars rating -->
				<!-- wp:paragraph {"style":{"typography":{"fontSize":"1.1rem","letterSpacing":"0.05em"},"color":{"text":"var:preset|color|accent-5"}}} -->
				<p style="font-size:1.1rem;letter-spacing:0.05em;color:var(--wp--preset--color--accent-5)"><?php echo esc_html( $t['rating'] ); ?></p>
				<!-- /wp:paragraph -->

				<!-- Quote -->
				<!-- wp:paragraph {"style":{"typography":{"fontStyle":"italic","lineHeight":"1.7","fontSize":"0.95rem"},"color":{"text":"#cbd5e1"}}} -->
				<p style="font-style:italic;line-height:1.7;font-size:0.95rem;color:#cbd5e1">&ldquo;<?php echo esc_html_x( $t['quote'], 'Testimonial quote', 'techstore' ); ?>&rdquo;</p>
				<!-- /wp:paragraph -->

				<!-- wp:separator {"style":{"color":{"background":"#334155"}}} -->
				<hr class="wp-block-separator has-alpha-channel-opacity" style="background-color:#334155;border-color:#334155" />
				<!-- /wp:separator -->

				<!-- Author -->
				<!-- wp:group {"style":{"spacing":{"blockGap":"0.25rem"}},"layout":{"type":"flex","orientation":"vertical"}} -->
				<div class="wp-block-group">
					<!-- wp:heading {"level":5,"style":{"typography":{"fontWeight":"700","fontSize":"0.95rem"},"color":{"text":"#ffffff"}}} -->
					<h5 class="wp-block-heading" style="color:#ffffff;font-weight:700;font-size:0.95rem"><?php echo esc_html_x( $t['name'], 'Testimonial author name', 'techstore' ); ?></h5>
					<!-- /wp:heading -->
					<!-- wp:paragraph {"style":{"typography":{"fontSize":"0.8rem"},"color":{"text":"#64748b"}}} -->
					<p style="font-size:0.8rem;color:#64748b"><?php echo esc_html_x( $t['title'], 'Testimonial author title', 'techstore' ); ?> &mdash; <?php echo esc_html_x( $t['product'], 'Testimonial product', 'techstore' ); ?></p>
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
