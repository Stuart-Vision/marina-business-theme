<?php
/**
 * Front-end performance clean-up for better Core Web Vitals.
 *
 * Removes scripts and tags a business site rarely needs, so pages
 * ship less code and render faster.
 *
 * @package Marina
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Drop the emoji detection script and styles (modern browsers render emoji natively).
 */
function marina_disable_emojis() {
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
}
add_action( 'init', 'marina_disable_emojis' );

/**
 * Remove head tags that add bytes but no value for visitors or search engines.
 */
remove_action( 'wp_head', 'wp_generator' );
remove_action( 'wp_head', 'wlwmanifest_link' );
remove_action( 'wp_head', 'rsd_link' );
remove_action( 'wp_head', 'wp_shortlink_wp_head' );

/**
 * Skip the block-library CSS on pages that contain no blocks.
 */
function marina_trim_block_styles() {
	if ( is_front_page() || ( is_singular() && ! has_blocks( get_queried_object() ) ) ) {
		wp_dequeue_style( 'wp-block-library' );
		wp_dequeue_style( 'wp-block-library-theme' );
		wp_dequeue_style( 'global-styles' );
		wp_dequeue_style( 'classic-theme-styles' );
	}
}
add_action( 'wp_enqueue_scripts', 'marina_trim_block_styles', 100 );
