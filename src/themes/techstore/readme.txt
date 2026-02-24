=== TechStore ===
Contributors: techstore
Requires at least: WordPress 6.4
Tested up to: WordPress 6.7
Requires PHP: 7.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A modern Full Site Editing WordPress ecommerce theme for electronics and technology products.

== Description ==

TechStore is a clean, professional ecommerce theme designed for electronics and technology product stores. It is built on the WordPress Full Site Editing (FSE) architecture and integrates seamlessly with WooCommerce.

Key Features:
- Full WooCommerce integration (product pages, shop archive, cart, checkout, my account)
- Blog support with post listings, single posts, archives and categories
- Tech-inspired color palette (blues, cyans, greens, oranges)
- Inter variable font for a clean, modern typography
- Wide layout (1280px) with constrained content (720px)
- Responsive design
- Block patterns for homepage, shop, and blog
- Accessible and SEO-friendly markup
- CyberPanel / cPanel compatible

== Required Plugins ==

- WooCommerce 8.0 or higher (for shop functionality)

== Setup Instructions ==

1. Upload the `techstore` folder to `/wp-content/themes/`
2. Activate the theme from WordPress Admin > Appearance > Themes
3. Install and activate WooCommerce
4. Go to Appearance > Editor to customize the site
5. Import homepage pattern from Patterns > Pages

== Font Setup ==

This theme uses the Inter variable font. To enable local font loading:

Option A - Copy from Twenty Twenty-Four:
  Copy `wp-content/themes/twentytwentyfour/assets/fonts/inter/` to
  `wp-content/themes/techstore/assets/fonts/inter/`

Option B - Download from Google Fonts:
  Visit https://fonts.google.com/specimen/Inter
  Download and place the variable font in `assets/fonts/inter/`
  File should be named: `Inter-VariableFont_slnt,wght.woff2`

Option C - Google Fonts CDN (enabled by default in functions.php):
  The theme automatically loads Inter from Google Fonts CDN as a fallback.
  No font files needed for this option.

== WooCommerce Pages ==

After installing WooCommerce, create these pages and assign the block templates:
- Shop page     → template: "Shop" (archive-product)
- Cart page     → template: "Cart" (cart)
- Checkout page → template: "Checkout" (checkout)
- My Account    → template: "My Account" (my-account)

== Changelog ==

= 1.0.0 =
* Initial release
