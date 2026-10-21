<?php
/**
 * Marina Business theme setup.
 *
 * @package Marina
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'MARINA_VERSION', '1.0.0' );

require get_template_directory() . '/inc/customizer.php';
require get_template_directory() . '/inc/template-tags.php';
require get_template_directory() . '/inc/seo.php';
require get_template_directory() . '/inc/performance.php';

/**
 * Register theme features and menus.
 */
function marina_setup() {
	load_theme_textdomain( 'marina', get_template_directory() . '/languages' );

	// Let WordPress manage the <title> tag (SEO plugins hook into this).
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support(
		'html5',
		array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' )
	);
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 96,
			'width'       => 320,
			'flex-height' => true,
			'flex-width'  => true,
		)
	);

	add_image_size( 'marina-card', 640, 360, true );

	register_nav_menus(
		array(
			'primary' => __( 'Primary menu', 'marina' ),
			'footer'  => __( 'Footer menu', 'marina' ),
		)
	);
}
add_action( 'after_setup_theme', 'marina_setup' );

/**
 * Enqueue the stylesheet and the tiny menu script.
 *
 * filemtime() busts the browser cache whenever a file changes.
 */
function marina_assets() {
	$dir = get_template_directory();
	$uri = get_template_directory_uri();

	wp_enqueue_style( 'marina-style', get_stylesheet_uri(), array(), filemtime( $dir . '/style.css' ) );

	wp_enqueue_script(
		'marina-navigation',
		$uri . '/assets/navigation.js',
		array(),
		filemtime( $dir . '/assets/navigation.js' ),
		array(
			'in_footer' => true,
			'strategy'  => 'defer',
		)
	);
}
add_action( 'wp_enqueue_scripts', 'marina_assets' );

/**
 * Shorter, cleaner excerpts for cards.
 */
function marina_excerpt_length() {
	return 24;
}
add_filter( 'excerpt_length', 'marina_excerpt_length' );

function marina_excerpt_more() {
	return '&hellip;';
}
add_filter( 'excerpt_more', 'marina_excerpt_more' );
