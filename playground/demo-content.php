<?php
/**
 * Demo content for the live WordPress Playground preview.
 *
 * Creates a fictional Dubai building-materials trader: pages, a Services
 * section with child pages, blog posts, menus and business details.
 */

require_once '/wordpress/wp-load.php';

update_option( 'blogname', 'Desert Line Building Materials' );
update_option( 'blogdescription', 'Cement, steel, tiles and site delivery for contractors across Dubai and the UAE.' );
global $wp_rewrite;
$wp_rewrite->set_permalink_structure( '/%postname%/' );

$make = function ( $title, $content, $excerpt = '', $parent = 0, $order = 0, $type = 'page' ) {
	return wp_insert_post(
		array(
			'post_type'    => $type,
			'post_status'  => 'publish',
			'post_title'   => $title,
			'post_content' => $content,
			'post_excerpt' => $excerpt,
			'post_parent'  => $parent,
			'menu_order'   => $order,
		)
	);
};

$home     = $make( 'Home', '' );
$about    = $make( 'About us', '<p>Desert Line is a demo building-materials trading company based in Dubai. We supply contractors, fit-out companies and developers with certified materials and on-time site delivery across the UAE.</p><h2>Why contractors choose us</h2><ul><li>Certified products that meet UAE standards</li><li>Same-day quotes on WhatsApp</li><li>Own delivery fleet across all seven emirates</li></ul>', 'Dubai building-materials supplier for contractors and developers, with certified products and site delivery across the UAE.', 0, 1 );
$services = $make( 'Services', '<p>Everything a construction site needs, from foundations to finishing, supplied from our Dubai warehouse.</p>', 'Building materials and delivery services for construction projects in Dubai and across the UAE.', 0, 2 );
$make( 'Cement and concrete', '<p>Ordinary Portland cement, sulphate-resisting cement and ready-mix concrete for hot-climate construction.</p><h2>Grades we supply</h2><ul><li>OPC 42.5N and 52.5N</li><li>Sulphate-resisting cement for coastal sites</li><li>Ready-mix concrete by the cubic metre</li></ul>', 'OPC and sulphate-resisting cement plus ready-mix concrete suited to UAE heat and coastal sites.', $services, 1 );
$make( 'Steel and rebar', '<p>Deformed rebar, mesh and structural steel cut and bent to your drawings.</p>', 'Rebar, steel mesh and structural steel, cut and bent to your drawings for Dubai projects.', $services, 2 );
$make( 'Tiles and flooring', '<p>Porcelain, ceramic and natural-stone tiles for residential and commercial fit-outs.</p>', 'Porcelain, ceramic and stone tiles for villa, apartment and commercial fit-outs in the UAE.', $services, 3 );
$make( 'Site delivery and logistics', '<p>Scheduled deliveries with crane trucks to sites in Dubai, Sharjah and Abu Dhabi.</p>', 'Scheduled crane-truck delivery of building materials to sites in Dubai, Sharjah and Abu Dhabi.', $services, 4 );
$contact  = $make( 'Contact', '<p>Call or WhatsApp us for a same-day quote. Our sales desk is open Monday to Saturday, 8:00 to 18:00.</p>', 'Contact Desert Line for building-materials quotes in Dubai. Call or WhatsApp, Monday to Saturday.', 0, 3 );
$blog     = $make( 'Insights', '', 'Guides for contractors buying building materials in the UAE.', 0, 4 );

$category = wp_insert_term( 'Buying guides', 'category' );
$cat_id   = is_wp_error( $category ) ? 1 : $category['term_id'];

$posts = array(
	array( 'Choosing the right cement for UAE heat', 'High temperatures speed up setting and increase cracking. Here is how to choose a cement grade and curing plan for summer pours in Dubai.' ),
	array( 'Rebar sizes explained for small contractors', 'A practical guide to rebar diameters, weights per metre and how to estimate quantities from structural drawings.' ),
	array( 'Planning material deliveries on busy Dubai sites', 'Site access windows, crane trucks and storage space all affect delivery. Plan them early to avoid idle crews.' ),
);
foreach ( $posts as $i => $post ) {
	wp_insert_post(
		array(
			'post_type'     => 'post',
			'post_status'   => 'publish',
			'post_title'    => $post[0],
			'post_excerpt'  => $post[1],
			'post_content'  => '<p>' . $post[1] . '</p><h2>Key points</h2><ul><li>Check the specification on your drawings.</li><li>Ask for test certificates with every batch.</li><li>Order early during peak season.</li></ul>',
			'post_category' => array( $cat_id ),
			'post_date'     => gmdate( 'Y-m-d H:i:s', strtotime( '-' . ( $i * 9 + 2 ) . ' days' ) ),
		)
	);
}

update_option( 'show_on_front', 'page' );
update_option( 'page_on_front', $home );
update_option( 'page_for_posts', $blog );

// Menus.
$primary = wp_create_nav_menu( 'Primary' );
foreach ( array( $about, $services, $blog, $contact ) as $page_id ) {
	wp_update_nav_menu_item( $primary, 0, array( 'menu-item-object-id' => $page_id, 'menu-item-object' => 'page', 'menu-item-type' => 'post_type', 'menu-item-status' => 'publish' ) );
}
$footer = wp_create_nav_menu( 'Footer' );
foreach ( array( $services, $about, $contact ) as $page_id ) {
	wp_update_nav_menu_item( $footer, 0, array( 'menu-item-object-id' => $page_id, 'menu-item-object' => 'page', 'menu-item-type' => 'post_type', 'menu-item-status' => 'publish' ) );
}
set_theme_mod( 'nav_menu_locations', array( 'primary' => $primary, 'footer' => $footer ) );

// Business details (fictional demo data).
$details = array(
	'business_type'     => 'HomeAndConstructionBusiness',
	'business_phone'    => '+971 4 000 0000',
	'business_whatsapp' => '971500000000',
	'business_email'    => 'sales@desertline.example',
	'business_street'   => 'Warehouse 12, Al Quoz Industrial Area 3',
	'business_city'     => 'Dubai',
	'business_country'  => 'AE',
	'business_hours'    => 'Mo-Sa 08:00-18:00',
	'hero_cta_text'     => 'Get a quote on WhatsApp',
);
foreach ( $details as $key => $value ) {
	set_theme_mod( $key, $value );
}

$wp_rewrite->flush_rules( true );
