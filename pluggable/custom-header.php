<?php
/**
 * Custom Header feature
 *
 * @link https://developer.wordpress.org/themes/functionality/custom-headers/
 *
 * @package simplebit
 */

/**
 * Set up the WordPress core custom header feature.
 *
 * @uses simplebit_header_style()
 */
function simplebit_custom_header_setup() {
	add_theme_support(
		'custom-header', apply_filters(
			'simplebit_custom_header_args', array(
				'default-image'          => '',
				'default-text-color'     => '000000',
				'width'                  => 1600,
				'height'                 => 250,
				'flex-height'            => true,
				'wp-head-callback'       => 'simplebit_header_style',
			)
		)
	);
}
add_action( 'after_setup_theme', 'simplebit_custom_header_setup' );

if ( ! function_exists( 'simplebit_header_style' ) ) :
	/**
	 * Styles the header image and text displayed on the blog.
	 *
	 * @see simplebit_custom_header_setup().
	 */
	function simplebit_header_style() {
		$header_text_color = get_header_textcolor();

		/*
		 * If no custom options for text are set, let's bail.
		 * get_header_textcolor() options: Any hex value, 'blank' to hide text. Default: add_theme_support( 'custom-header' ).
		 */
		if ( get_theme_support( 'custom-header', 'default-text-color' ) === $header_text_color ) {
			return;
		}

		// If we get this far, we have custom styles. Let's do this.
		?>
		<style type="text/css">
		<?php
		// Has the text been hidden?
		if ( ! display_header_text() ) :
			?>
			.site-title,
			.site-description {
				position: absolute;
				clip: rect(1px, 1px, 1px, 1px);
			}
			<?php
			// If the user has set a custom color for the text use that.
		else :
			?>
			.site-title a,
			.site-description {
				color: #<?php echo esc_attr( $header_text_color ); ?>;
			}
		<?php endif; ?>
		</style>
		<?php
	}
endif;
