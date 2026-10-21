<?php
/**
 * Template helpers: breadcrumbs and contact links.
 *
 * @package Marina
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Build the breadcrumb trail for the current page.
 *
 * Used twice: to print the visible breadcrumbs, and to build the
 * BreadcrumbList schema in inc/seo.php, so both always match.
 *
 * @return array<int, array{name: string, url: string}>
 */
function marina_breadcrumb_items() {
	if ( is_front_page() ) {
		return array();
	}

	$items = array(
		array(
			'name' => __( 'Home', 'marina' ),
			'url'  => home_url( '/' ),
		),
	);

	if ( is_singular( 'page' ) ) {
		foreach ( array_reverse( get_post_ancestors( get_queried_object_id() ) ) as $ancestor_id ) {
			$items[] = array(
				'name' => get_the_title( $ancestor_id ),
				'url'  => get_permalink( $ancestor_id ),
			);
		}
		$items[] = array(
			'name' => get_the_title(),
			'url'  => get_permalink(),
		);
	} elseif ( is_singular( 'post' ) ) {
		$blog_page = (int) get_option( 'page_for_posts' );
		if ( $blog_page ) {
			$items[] = array(
				'name' => get_the_title( $blog_page ),
				'url'  => get_permalink( $blog_page ),
			);
		}
		$category = get_the_category();
		if ( $category ) {
			$items[] = array(
				'name' => $category[0]->name,
				'url'  => get_category_link( $category[0] ),
			);
		}
		$items[] = array(
			'name' => get_the_title(),
			'url'  => get_permalink(),
		);
	} elseif ( is_home() ) {
		$items[] = array(
			'name' => get_the_title( (int) get_option( 'page_for_posts' ) ),
			'url'  => get_permalink( (int) get_option( 'page_for_posts' ) ),
		);
	} elseif ( is_category() || is_tag() || is_tax() ) {
		$term    = get_queried_object();
		$items[] = array(
			'name' => $term->name,
			'url'  => get_term_link( $term ),
		);
	} elseif ( is_search() ) {
		$items[] = array(
			/* translators: %s: search query */
			'name' => sprintf( __( 'Search: %s', 'marina' ), get_search_query() ),
			'url'  => get_search_link(),
		);
	} elseif ( is_404() ) {
		$items[] = array(
			'name' => __( 'Page not found', 'marina' ),
			'url'  => '',
		);
	}

	return $items;
}

/**
 * Print visible breadcrumbs (skipped when Yoast or Rank Math print their own).
 */
function marina_breadcrumbs() {
	$items = marina_breadcrumb_items();
	if ( count( $items ) < 2 ) {
		return;
	}

	echo '<nav class="breadcrumbs" aria-label="' . esc_attr__( 'Breadcrumb', 'marina' ) . '"><ol>';
	$last = count( $items ) - 1;
	foreach ( $items as $i => $item ) {
		if ( $i === $last || '' === $item['url'] ) {
			echo '<li aria-current="page">' . esc_html( $item['name'] ) . '</li>';
		} else {
			echo '<li><a href="' . esc_url( $item['url'] ) . '">' . esc_html( $item['name'] ) . '</a></li>';
		}
	}
	echo '</ol></nav>';
}

/**
 * WhatsApp click-to-chat link, or the contact page as a fallback.
 */
function marina_cta_url() {
	$whatsapp = preg_replace( '/\D+/', '', marina_business( 'business_whatsapp' ) );
	if ( $whatsapp ) {
		return 'https://wa.me/' . $whatsapp;
	}
	$contact = get_page_by_path( 'contact' );
	return $contact ? get_permalink( $contact ) : home_url( '/' );
}
