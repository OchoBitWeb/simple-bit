<?php
/**
 * Lazy-load images.
 *
 * Modified version of Lazy Images module in Jetpack.
 *
 * @link https://github.com/Automattic/jetpack/blob/master/modules/lazy-images/lazy-images.php
 *
 * @package simplebit
 */

/**
 * Main function. Runs everything.
 */
function simplebit_lazyload_images() {

	// If this is the admin page, do nothing.
	if ( is_admin() ) {
		return;
	}

	// If lazy-load is disabled in Customizer, do nothing.
	if ( 'no-lazyload' === get_theme_mod( 'lazy_load_media' ) ) {
		return;
	}

	// If the Jetpack Lazy-Images module is active, do nothing.
	if ( ! apply_filters( 'lazyload_is_enabled', true ) ) {
		return;
	}

	// If AMP is active, do nothing.
	if ( simplebit_is_amp() ) {
		return;
	}

	add_action( 'wp_head', 'simplebit_setup_filters', PHP_INT_MAX );
	add_action( 'wp_enqueue_scripts', 'simplebit_enqueue_assets' );

	// Do not lazy load avatar in admin bar.
	add_action( 'admin_bar_menu', 'simplebit_remove_filters', 0 );
	add_filter( 'wp_kses_allowed_html', 'simplebit_allow_lazy_attributes' );

}
add_action( 'wp', 'simplebit_lazyload_images' );

/**
 * Setup filters to enable lazy-loading of images.
 */
function simplebit_setup_filters() {
	add_filter( 'the_content', 'simplebit_add_image_placeholders', PHP_INT_MAX );
	add_filter( 'post_thumbnail_html', 'simplebit_add_image_placeholders', PHP_INT_MAX );
	add_filter( 'get_avatar', 'simplebit_add_image_placeholders', PHP_INT_MAX );
	add_filter( 'widget_text', 'simplebit_add_image_placeholders', PHP_INT_MAX );
	add_filter( 'get_image_tag', 'simplebit_add_image_placeholders', PHP_INT_MAX );
	add_filter( 'wp_get_attachment_image_attributes', 'simplebit_process_image_attributes', PHP_INT_MAX );
}

/**
 * Remove filters for images that should not be lazy-loaded.
 */
function simplebit_remove_filters() {
	remove_filter( 'the_content', 'simplebit_add_image_placeholders', PHP_INT_MAX );
	remove_filter( 'post_thumbnail_html', 'simplebit_add_image_placeholders', PHP_INT_MAX );
	remove_filter( 'get_avatar', 'simplebit_add_image_placeholders', PHP_INT_MAX );
	remove_filter( 'widget_text', 'simplebit_add_image_placeholders', PHP_INT_MAX );
	remove_filter( 'get_image_tag', 'simplebit_add_image_placeholders', PHP_INT_MAX );
	remove_filter( 'wp_get_attachment_image_attributes', 'simplebit_process_image_attributes', PHP_INT_MAX );
}

/**
 * Ensure that our lazy image attributes are not filtered out of image tags.
 *
 * @param array $allowed_tags The allowed tags and their attributes.
 * @return array
 */
function simplebit_allow_lazy_attributes( $allowed_tags ) {
	if ( ! isset( $allowed_tags['img'] ) ) {
		return $allowed_tags;
	}
	// But, if images are allowed, ensure that our attributes are allowed!
	$img_attributes      = array_merge( $allowed_tags['img'], array(
		'data-src'    => 1,
		'data-srcset' => 1,
		'data-sizes'  => 1,
		'class'       => 1,
	) );
	$allowed_tags['img'] = $img_attributes;
	return $allowed_tags;
}

/**
 * Find image elements that should be lazy-loaded.
 *
 * @param object $content The content.
 * @return object
 */
function simplebit_add_image_placeholders( $content ) {
	// Don't lazyload for feeds, previews.
	if ( is_feed() || is_preview() ) {
		return $content;
	}
	// Don't lazy-load if the content has already been run through previously.
	if ( false !== strpos( $content, 'data-src' ) ) {
		return $content;
	}

	// Find all <img> elements via regex, add lazy-load attributes.
	$content = preg_replace_callback( '#<(img)([^>]+?)(>(.*?)</\\1>|[\/]?>)#si', 'simplebit_process_image', $content );
	return $content;

}

/**
 * Returns true when a given string of classes contains a class signifying image
 * should not be lazy-loaded
 *
 * @param string $classes A string of space-separated classes.
 * @return bool
 */
function simplebit_should_skip_image_with_blacklisted_class( $classes ) {
	$blacklisted_classes = array(
		'skip-lazy',
	);

	foreach ( $blacklisted_classes as $class ) {
		if ( false !== strpos( $classes, $class ) ) {
			return true;
		}
	}
	return false;
}

/**
 * Processes images in content by acting as the preg_replace_callback.
 *
 * @param array $matches <img> element to be altered.
 *
 * @return string The image with updated lazy attributes
 */
function simplebit_process_image( $matches ) {
	$old_attributes_str       = $matches[2];
	$old_attributes_kses_hair = wp_kses_hair( $old_attributes_str, wp_allowed_protocols() );
	if ( empty( $old_attributes_kses_hair['src'] ) ) {
		return $matches[0];
	}
	$old_attributes = simplebit_flatten_kses_hair_data( $old_attributes_kses_hair );
	$new_attributes = simplebit_process_image_attributes( $old_attributes );
	// If we didn't add lazy attributes, just return the original image source.
	if ( empty( $new_attributes['data-src'] ) ) {
		return $matches[0];
	}
	$new_attributes_str = simplebit_build_attributes_string( $new_attributes );

	return sprintf( '<img %1$s><noscript>%2$s</noscript>', $new_attributes_str, $matches[0] );
}

/**
 * Given an array of image attributes, updates the `src`, `srcset`, and `sizes` attributes so
 * that they load lazily.
 *
 * @param array $attributes Attributes of the current <img> element.
 *
 * @return array The updated image attributes array with lazy load attributes.
 */
function simplebit_process_image_attributes( $attributes ) {
	if ( empty( $attributes['src'] ) ) {
		return $attributes;
	}
	if ( ! empty( $attributes['class'] ) && simplebit_should_skip_image_with_blacklisted_class( $attributes['class'] ) ) {
		return $attributes;
	}

	$old_attributes = $attributes;

	// Add the lazy class to the img element.
	$attributes['class'] = simplebit_set_lazy_class( $attributes );

	// Set placeholder and lazy-src.
	$attributes['src'] = simplebit_get_placeholder_image();

	// Set data-src to the original source uri.
	$attributes['data-src'] = $old_attributes['src'];

	// Process `srcset` attribute.
	if ( ! empty( $attributes['srcset'] ) ) {
		$attributes['data-srcset'] = $old_attributes['srcset'];
		unset( $attributes['srcset'] );
	}
	// Process `sizes` attribute.
	if ( ! empty( $attributes['sizes'] ) ) {
		$attributes['data-sizes'] = $old_attributes['sizes'];
		unset( $attributes['sizes'] );
	}

	return $attributes;
}

/**
 * Append a `lazy` class to <img> elements for lazy-loading.
 *
 * @param array $attributes <img> element attributes.
 * @return string
 */
function simplebit_set_lazy_class( $attributes ) {
	if ( array_key_exists( 'class', $attributes ) ) {
		$classes  = $attributes['class'];
		$classes .= ' lazy';
	} else {
		$classes = 'lazy';
	}

	return $classes;
}

/**
 * Set the placeholder image.
 *
 * @return string The URL to the placeholder image.
 */
function simplebit_get_placeholder_image() {
	return get_theme_file_uri( '/images/placeholder.svg' );
}

/**
 * Flatten attribute list into string.
 *
 * @param array $attributes Array of attributes.
 * @return string $flattened_attributes
 */
function simplebit_flatten_kses_hair_data( $attributes ) {
	$flattened_attributes = array();
	foreach ( $attributes as $name => $attribute ) {
		$flattened_attributes[ $name ] = $attribute['value'];
	}
	return $flattened_attributes;
}

/**
 * Build string of new attributes to be returned to the <img> element.
 *
 * @param array $attributes Array of attributes.
 * @return string
 */
function simplebit_build_attributes_string( $attributes ) {
	$string = array();
	foreach ( $attributes as $name => $value ) {
		if ( '' === $value ) {
			$string[] = sprintf( '%s', $name );
		} else {
			$string[] = sprintf( '%s="%s"', $name, esc_attr( $value ) );
		}
	}

	return implode( ' ', $string );
}

/**
 * Enqueue and defer lazyload script.
 */
function simplebit_enqueue_assets() {
	wp_enqueue_script( 'simplebit-lazy-load-images', get_theme_file_uri( '/pluggable/lazyload/js/lazyload.js' ), array(), '20151215', false );
	wp_script_add_data( 'simplebit-lazy-load-images', 'defer', true );
}
