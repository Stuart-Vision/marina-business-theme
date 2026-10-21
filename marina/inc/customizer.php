<?php
/**
 * Business details in the Customizer (Appearance > Customize > Business details).
 *
 * These values feed the header call-to-action, the footer, and the
 * LocalBusiness schema, so they are entered once and used everywhere.
 *
 * @package Marina
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Field definitions: id => label.
 */
function marina_business_fields() {
	return array(
		'business_type'     => __( 'Schema type (e.g. LocalBusiness, HomeAndConstructionBusiness)', 'marina' ),
		'business_phone'    => __( 'Phone (international format, e.g. +971 4 123 4567)', 'marina' ),
		'business_whatsapp' => __( 'WhatsApp number (digits only, e.g. 971501234567)', 'marina' ),
		'business_email'    => __( 'Email', 'marina' ),
		'business_street'   => __( 'Street address', 'marina' ),
		'business_city'     => __( 'City', 'marina' ),
		'business_country'  => __( 'Country code (e.g. AE)', 'marina' ),
		'business_hours'    => __( 'Opening hours (schema format, e.g. Mo-Fr 08:00-18:00)', 'marina' ),
		'hero_cta_text'     => __( 'Hero button text', 'marina' ),
	);
}

function marina_customize_register( $wp_customize ) {
	$wp_customize->add_section(
		'marina_business',
		array(
			'title'    => __( 'Business details', 'marina' ),
			'priority' => 30,
		)
	);

	foreach ( marina_business_fields() as $id => $label ) {
		$wp_customize->add_setting(
			$id,
			array(
				'default'           => marina_business_default( $id ),
				'sanitize_callback' => 'business_email' === $id ? 'sanitize_email' : 'sanitize_text_field',
			)
		);
		$wp_customize->add_control(
			$id,
			array(
				'label'   => $label,
				'section' => 'marina_business',
				'type'    => 'text',
			)
		);
	}
}
add_action( 'customize_register', 'marina_customize_register' );

/**
 * Sensible defaults so a fresh install still renders valid markup.
 */
function marina_business_default( $id ) {
	$defaults = array(
		'business_type'    => 'LocalBusiness',
		'business_city'    => 'Dubai',
		'business_country' => 'AE',
		'hero_cta_text'    => __( 'Request a quote', 'marina' ),
	);
	return $defaults[ $id ] ?? '';
}

/**
 * Read one business detail.
 */
function marina_business( $id ) {
	return trim( (string) get_theme_mod( $id, marina_business_default( $id ) ) );
}
