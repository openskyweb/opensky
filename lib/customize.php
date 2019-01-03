<?php
/**
 * Open Sky - Template.
 *
 * This file adds the required CSS to the front end to the Genesis Child Theme.
 *
 * @package Open Sky - Template
 * @author  Open Sky Web Studio
 * @license GPL-2.0+
 * @link    http://www.openskywebstudio.com/
 */

/**
 * Get default link color for Customizer.
 *
 * Abstracted here since at least two functions use it.
 *
 * @since 2.2.3
 *
 * @return string Hex color code for link color.
 */
function opensky_customizer_get_default_link_color() {
	return '#c3251d';
}

/**
 * Get default accent color for Customizer.
 *
 * Abstracted here since at least two functions use it.
 *
 * @since 2.2.3
 *
 * @return string Hex color code for accent color.
 */
function opensky_customizer_get_default_accent_color() {
	return '#c3251d';
}

add_action( 'customize_register', 'opensky_customizer_register' );
/**
 * Register settings and controls with the Customizer.
 *
 * @since 2.2.3
 * 
 * @param WP_Customize_Manager $wp_customize Customizer object.
 */
function opensky_customizer_register() {

	global $wp_customize;

	$wp_customize->add_setting(
		'opensky_link_color',
		array(
			'default'           => opensky_customizer_get_default_link_color(),
			'sanitize_callback' => 'sanitize_hex_color',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'opensky_link_color',
			array(
				'description' => __( 'Change the default color for linked titles, menu links, post info links and more.', 'opensky' ),
			    'label'       => __( 'Link Color', 'opensky' ),
			    'section'     => 'colors',
			    'settings'    => 'opensky_link_color',
			)
		)
	);

	$wp_customize->add_setting(
		'opensky_accent_color',
		array(
			'default'           => opensky_customizer_get_default_accent_color(),
			'sanitize_callback' => 'sanitize_hex_color',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'opensky_accent_color',
			array(
				'description' => __( 'Change the default color for button hovers.', 'opensky' ),
			    'label'       => __( 'Accent Color', 'opensky' ),
			    'section'     => 'colors',
			    'settings'    => 'opensky_accent_color',
			)
		)
	);

}
