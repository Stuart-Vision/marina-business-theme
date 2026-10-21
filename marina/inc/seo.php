<?php
/**
 * Built-in SEO: meta description, canonical, Open Graph, robots and JSON-LD schema.
 *
 * Everything here switches off automatically when Yoast SEO or Rank Math is
 * active, so the site never prints two sets of SEO tags.
 *
 * @package Marina
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Is a dedicated SEO plugin handling meta tags and schema?
 */
function marina_seo_plugin_active() {
	return defined( 'WPSEO_VERSION' ) || defined( 'RANK_MATH_VERSION' ) || class_exists( 'RankMath' );
}

/**
 * Meta description for the current view, at most ~155 characters.
 */
function marina_meta_description() {
	$text = '';

	if ( is_singular() ) {
		$post = get_queried_object();
		$text = has_excerpt( $post ) ? $post->post_excerpt : $post->post_content;
	} elseif ( is_category() || is_tag() || is_tax() ) {
		$text = term_description();
	}

	if ( '' === trim( wp_strip_all_tags( $text ) ) ) {
		$text = get_bloginfo( 'description' );
	}

	$text = trim( preg_replace( '/\s+/', ' ', wp_strip_all_tags( strip_shortcodes( $text ) ) ) );
	return wp_html_excerpt( $text, 155, '…' );
}

/**
 * Canonical URL. WordPress core already prints one on single posts and pages
 * (including a static front page), so this only covers the views core leaves out.
 */
function marina_canonical_url() {
	if ( is_singular() ) {
		return '';
	}
	if ( is_front_page() ) {
		return home_url( '/' );
	}
	if ( is_home() ) {
		$blog_page = (int) get_option( 'page_for_posts' );
		return $blog_page ? get_permalink( $blog_page ) : home_url( '/' );
	}
	if ( is_category() || is_tag() || is_tax() ) {
		$link = get_term_link( get_queried_object() );
		return is_wp_error( $link ) ? '' : $link;
	}
	return '';
}

/**
 * Best image for social previews: featured image, then the site logo.
 */
function marina_share_image() {
	if ( is_singular() && has_post_thumbnail() ) {
		return get_the_post_thumbnail_url( null, 'large' );
	}
	$logo_id = (int) get_theme_mod( 'custom_logo' );
	return $logo_id ? wp_get_attachment_image_url( $logo_id, 'full' ) : '';
}

/**
 * Print description, canonical and Open Graph / Twitter tags.
 */
function marina_print_meta_tags() {
	if ( marina_seo_plugin_active() || is_404() ) {
		return;
	}

	$description = marina_meta_description();
	$canonical   = marina_canonical_url();
	$url         = is_singular() ? wp_get_canonical_url() : ( $canonical ? $canonical : home_url( add_query_arg( array() ) ) );
	$image       = marina_share_image();

	$tags = array(
		'description'    => $description,
		'og:type'        => is_singular( 'post' ) ? 'article' : 'website',
		'og:title'       => wp_get_document_title(),
		'og:description' => $description,
		'og:url'         => $url,
		'og:site_name'   => get_bloginfo( 'name' ),
		'og:locale'      => get_locale(),
		'og:image'       => $image,
		'twitter:card'   => $image ? 'summary_large_image' : 'summary',
	);

	echo "\n<!-- Marina SEO -->\n";
	if ( $canonical && ! is_paged() ) {
		printf( '<link rel="canonical" href="%s">' . "\n", esc_url( $canonical ) );
	}
	foreach ( $tags as $name => $content ) {
		if ( '' === (string) $content ) {
			continue;
		}
		$attr = str_starts_with( $name, 'og:' ) ? 'property' : 'name';
		printf( '<meta %s="%s" content="%s">' . "\n", $attr, esc_attr( $name ), esc_attr( $content ) );
	}
}
add_action( 'wp_head', 'marina_print_meta_tags', 1 );

/**
 * Keep thin pages out of Google: date and author archives get noindex,follow
 * so their links are still crawled. (Core already noindexes search results.)
 */
function marina_robots( array $robots ) {
	if ( marina_seo_plugin_active() ) {
		return $robots;
	}
	if ( is_date() || is_author() || is_404() ) {
		$robots['noindex'] = true;
		$robots['follow']  = true;
	}
	return $robots;
}
add_filter( 'wp_robots', 'marina_robots' );

/**
 * The business itself, as LocalBusiness (or a more specific type) schema.
 */
function marina_business_schema() {
	$address = array_filter(
		array(
			'@type'           => 'PostalAddress',
			'streetAddress'   => marina_business( 'business_street' ),
			'addressLocality' => marina_business( 'business_city' ),
			'addressCountry'  => marina_business( 'business_country' ),
		)
	);

	$logo_id = (int) get_theme_mod( 'custom_logo' );

	return array_filter(
		array(
			'@type'        => marina_business( 'business_type' ) ? marina_business( 'business_type' ) : 'LocalBusiness',
			'@id'          => home_url( '/#business' ),
			'name'         => get_bloginfo( 'name' ),
			'description'  => get_bloginfo( 'description' ),
			'url'          => home_url( '/' ),
			'telephone'    => marina_business( 'business_phone' ),
			'email'        => marina_business( 'business_email' ),
			'address'      => count( $address ) > 1 ? $address : null,
			'openingHours' => marina_business( 'business_hours' ),
			'logo'         => $logo_id ? wp_get_attachment_image_url( $logo_id, 'full' ) : null,
		)
	);
}

/**
 * Print one JSON-LD @graph with the schema that fits the current page.
 */
function marina_print_schema() {
	if ( marina_seo_plugin_active() || is_404() ) {
		return;
	}

	$graph = array(
		array(
			'@type'           => 'WebSite',
			'@id'             => home_url( '/#website' ),
			'url'             => home_url( '/' ),
			'name'            => get_bloginfo( 'name' ),
			'publisher'       => array( '@id' => home_url( '/#business' ) ),
			'potentialAction' => array(
				'@type'       => 'SearchAction',
				'target'      => home_url( '/?s={search_term_string}' ),
				'query-input' => 'required name=search_term_string',
			),
		),
		marina_business_schema(),
	);

	$crumbs = marina_breadcrumb_items();
	if ( count( $crumbs ) > 1 ) {
		$list = array();
		foreach ( $crumbs as $i => $crumb ) {
			$list[] = array_filter(
				array(
					'@type'    => 'ListItem',
					'position' => $i + 1,
					'name'     => $crumb['name'],
					'item'     => $crumb['url'],
				)
			);
		}
		$graph[] = array(
			'@type'           => 'BreadcrumbList',
			'itemListElement' => $list,
		);
	}

	if ( is_singular( 'post' ) ) {
		$graph[] = array_filter(
			array(
				'@type'            => 'BlogPosting',
				'headline'         => get_the_title(),
				'description'      => marina_meta_description(),
				'datePublished'    => get_the_date( 'c' ),
				'dateModified'     => get_the_modified_date( 'c' ),
				'author'           => array(
					'@type' => 'Person',
					'name'  => get_the_author_meta( 'display_name', (int) get_post_field( 'post_author' ) ),
				),
				'publisher'        => array( '@id' => home_url( '/#business' ) ),
				'mainEntityOfPage' => get_permalink(),
				'image'            => has_post_thumbnail() ? get_the_post_thumbnail_url( null, 'large' ) : null,
			)
		);
	}

	$data = array(
		'@context' => 'https://schema.org',
		'@graph'   => $graph,
	);

	echo '<script type="application/ld+json">' . wp_json_encode( $data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . "</script>\n";
}
add_action( 'wp_head', 'marina_print_schema', 20 );
