<?php
/**
 * Simple Bit functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package simplebit
 */

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function simplebit_setup() {
	/*
		* Make theme available for translation.
		* Translations can be filed in the /languages/ directory.
		* If you're building a theme based on simplebit, use a find and replace
		* to change 'simplebit' to the name of your theme in all the template files.
		*/
	load_theme_textdomain( 'simplebit', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
		* Let WordPress manage the document title.
		* By adding theme support, we declare that this theme does not use a
		* hard-coded <title> tag in the document head, and expect WordPress to
		* provide it for us.
		*/
	add_theme_support( 'title-tag' );

	/*
		* Enable support for Post Thumbnails on posts and pages.
		*
		* @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		*/
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus(
		array(
			'primary' => esc_html__( 'Primary', 'simplebit' ),
			'footer'  => esc_html__( 'Footer', 'simplebit' ),
		)
	);

	/*
		* Switch default core markup for search form, comment form, and comments
		* to output valid HTML5.
		*/
	add_theme_support(
		'html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		)
	);

	// Set up the WordPress core custom background feature.
	add_theme_support(
		'custom-background', apply_filters(
			'simplebit_custom_background_args', array(
				'default-color' => 'ffffff',
				'default-image' => '',
			)
		)
	);

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );

	/**
	 * Add support for core custom logo.
	 *
	 * @link https://codex.wordpress.org/Theme_Logo
	 */
	add_theme_support(
		'custom-logo', array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => false,
			'flex-height' => false,
		)
	);

	/**
	 * Add support for wide aligments.
	 *
	 * @link https://wordpress.org/gutenberg/handbook/extensibility/theme-support/
	 */
	add_theme_support( 'align-wide' );

	/**
	 * Add support for block color palettes.
	 *
	 * @link https://wordpress.org/gutenberg/handbook/extensibility/theme-support/
	 */
	add_theme_support( 'editor-color-palette', array(
		array(
			'name'  => __( 'Dusty orange', 'simplebit' ),
			'slug'  => 'dusty-orange',
			'color' => '#ed8f5b',
		),
		array(
			'name'  => __( 'Dusty red', 'simplebit' ),
			'slug'  => 'dusty-red',
			'color' => '#e36d60',
		),
		array(
			'name'  => __( 'Dusty wine', 'simplebit' ),
			'slug'  => 'dusty-wine',
			'color' => '#9c4368',
		),
		array(
			'name'  => __( 'Dark sunset', 'simplebit' ),
			'slug'  => 'dark-sunset',
			'color' => '#33223b',
		),
		array(
			'name'  => __( 'Almost black', 'simplebit' ),
			'slug'  => 'almost-black',
			'color' => '#0a1c28',
		),
		array(
			'name'  => __( 'Dusty water', 'simplebit' ),
			'slug'  => 'dusty-water',
			'color' => '#41848f',
		),
		array(
			'name'  => __( 'Dusty sky', 'simplebit' ),
			'slug'  => 'dusty-sky',
			'color' => '#72a7a3',
		),
		array(
			'name'  => __( 'Dusty daylight', 'simplebit' ),
			'slug'  => 'dusty-daylight',
			'color' => '#97c0b7',
		),
		array(
			'name'  => __( 'Dusty sun', 'simplebit' ),
			'slug'  => 'dusty-sun',
			'color' => '#eee9d1',
		),
	) );

	/**
	 * Optional: Disable custom colors in block color palettes.
	 *
	 * @link https://wordpress.org/gutenberg/handbook/extensibility/theme-support/
	 *
	 * add_theme_support( 'disable-custom-colors' );
	 */

	/**
	 * Add support for font sizes.
	 *
	 * @link https://wordpress.org/gutenberg/handbook/extensibility/theme-support/
	 */
	add_theme_support( 'editor-font-sizes', array(
		array(
			'name'      => __( 'small', 'simplebit' ),
			'shortName' => __( 'S', 'simplebit' ),
			'size'      => 16,
			'slug'      => 'small',
		),
		array(
			'name'      => __( 'regular', 'simplebit' ),
			'shortName' => __( 'M', 'simplebit' ),
			'size'      => 20,
			'slug'      => 'regular',
		),
		array(
			'name'      => __( 'large', 'simplebit' ),
			'shortName' => __( 'L', 'simplebit' ),
			'size'      => 36,
			'slug'      => 'large',
		),
		array(
			'name'      => __( 'larger', 'simplebit' ),
			'shortName' => __( 'XL', 'simplebit' ),
			'size'      => 48,
			'slug'      => 'larger',
		),
	) );

	/**
	 * Optional: Add AMP support.
	 *
	 * Add built-in support for the AMP plugin and specific AMP features.
	 * Control how the plugin, when activated, impacts the theme.
	 *
	 * @link https://wordpress.org/plugins/amp/
	 */
	add_theme_support( 'amp', array(
		'comments_live_list' => true,
	) );

}
add_action( 'after_setup_theme', 'simplebit_setup' );

/**
 * Set the embed width in pixels, based on the theme's design and stylesheet.
 *
 * @param array $dimensions An array of embed width and height values in pixels (in that order).
 * @return array
 */
function simplebit_embed_dimensions( array $dimensions ) {
	$dimensions['width'] = 720;
	return $dimensions;
}
add_filter( 'embed_defaults', 'simplebit_embed_dimensions' );

/**
 * Register Google Fonts
 */
function simplebit_fonts_url() {
	$fonts_url = '';

	/**
	 * Translator: If Roboto Sans does not support characters in your language, translate this to 'off'.
	 */
	$roboto = esc_html_x( 'on', 'Roboto Condensed font: on or off', 'simplebit' );
	/**
	 * Translator: If Open Sans does not support characters in your language, translate this to 'off'.
	 */
	$open_sans = esc_html_x( 'on', 'Open Sans font: on or off', 'simplebit' );

	$font_families = array();

	if ( 'off' !== $roboto ) {
		$font_families[] = 'Roboto Condensed:400,400i,700,700i';
	}

	if ( 'off' !== $open_sans ) {
		$font_families[] = 'Open Sans:400,400i,600,600i';
	}

	if ( in_array( 'on', array( $roboto, $open_sans ) ) ) {
		$query_args = array(
			'family' => urlencode( implode( '|', $font_families ) ),
			'subset' => urlencode( 'latin,latin-ext' ),
		);

		$fonts_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );
	}

	return esc_url_raw( $fonts_url );

}

/**
 * Add preconnect for Google Fonts.
 *
 * @since Twenty Seventeen 1.0
 *
 * @param array  $urls           URLs to print for resource hints.
 * @param string $relation_type  The relation type the URLs are printed.
 * @return array $urls           URLs to print for resource hints.
 */
function simplebit_resource_hints( $urls, $relation_type ) {
	if ( wp_style_is( 'simplebit-fonts', 'queue' ) && 'preconnect' === $relation_type ) {
		$urls[] = array(
			'href' => 'https://fonts.gstatic.com',
			'crossorigin',
		);
	}

	return $urls;
}
add_filter( 'wp_resource_hints', 'simplebit_resource_hints', 10, 2 );

/**
 * Enqueue WordPress theme styles within Gutenberg.
 */
function simplebit_gutenberg_styles() {
	// Add custom fonts, used in the main stylesheet.
	wp_enqueue_style( 'simplebit-fonts', simplebit_fonts_url(), array(), null );

	// Enqueue main stylesheet.
	wp_enqueue_style( 'simplebit-base-style', get_theme_file_uri( '/css/editor-styles.css' ), array(), '20180514' );
}
add_action( 'enqueue_block_editor_assets', 'simplebit_gutenberg_styles' );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function simplebit_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'simplebit' ),
		'id'            => 'sidebar-1',
		'description'   => esc_html__( 'Add widgets here.', 'simplebit' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
}
add_action( 'widgets_init', 'simplebit_widgets_init' );

/**
 * Enqueue styles.
 */
function simplebit_styles() {
	// Add custom fonts, used in the main stylesheet.
	wp_enqueue_style( 'simplebit-fonts', simplebit_fonts_url(), array(), null );

	// Enqueue main stylesheet.
	wp_enqueue_style( 'simplebit-base-style', get_stylesheet_uri(), array(), '20180514' );
	wp_enqueue_style( 'simplebit-sass-main-styles', get_theme_file_uri( '/css/main.css' ), array(), '20180514' );

	// Register component styles that are printed as needed.
	wp_register_style( 'simplebit-comments', get_theme_file_uri( '/css/comments.css' ), array(), '20180514' );
	wp_register_style( 'simplebit-content', get_theme_file_uri( '/css/content.css' ), array(), '20180514' );
	wp_register_style( 'simplebit-sidebar', get_theme_file_uri( '/css/sidebar.css' ), array(), '20180514' );
	wp_register_style( 'simplebit-widgets', get_theme_file_uri( '/css/widgets.css' ), array(), '20180514' );
	wp_register_style( 'simplebit-front-page', get_theme_file_uri( '/css/front-page.css' ), array(), '20180514' );
}
add_action( 'wp_enqueue_scripts', 'simplebit_styles' );

/**
 * Enqueue scripts.
 */
function simplebit_scripts() {

	// If the AMP plugin is active, return early.
	if ( simplebit_is_amp() ) {
		return;
	}

	// Enqueue the navigation script.
	wp_enqueue_script( 'simplebit-navigation', get_theme_file_uri( '/js/navigation.js' ), array(), '20180514', false );
	wp_script_add_data( 'simplebit-navigation', 'async', true );
	wp_localize_script( 'simplebit-navigation', 'simplebitScreenReaderText', array(
		'expand'   => __( 'Expand child menu', 'simplebit' ),
		'collapse' => __( 'Collapse child menu', 'simplebit' ),
	));

	// Enqueue skip-link-focus script.
	wp_enqueue_script( 'simplebit-skip-link-focus-fix', get_theme_file_uri( '/js/skip-link-focus-fix.js' ), array(), '20180514', false );
	wp_script_add_data( 'simplebit-skip-link-focus-fix', 'defer', true );

	// Enqueue comment script on singular post/page views only.
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	wp_enqueue_script( 'custom-js', get_theme_file_uri( '/js/custom.js' ), array(), '20180514', false );

}
add_action( 'wp_enqueue_scripts', 'simplebit_scripts' );

/**
 * Custom responsive image sizes.
 */
require get_template_directory() . '/inc/image-sizes.php';

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/pluggable/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Optional: Add theme support for lazyloading images.
 *
 * @link https://developers.google.com/web/fundamentals/performance/lazy-loading-guidance/images-and-video/
 */
require get_template_directory() . '/pluggable/lazyload/lazyload.php';
