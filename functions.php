<?php
/**
 * Open Sky - Template.
 *
 * This file adds functions to the Genesis Child Theme.
 *
 * @package Open Sky - Template
 * @author  Open Sky Web Studio
 * @license GPL-2.0+
 * @link    http://www.openskywebstudio.com/
 */

//* Start the engine
include_once( get_template_directory() . '/lib/init.php' );

//* Setup Theme
include_once( get_stylesheet_directory() . '/lib/theme-defaults.php' );

//* Set Localization (do not remove)
load_child_theme_textdomain( 'opensky', apply_filters( 'child_theme_textdomain', get_stylesheet_directory() . '/languages', 'opensky' ) );

//* Add Image upload and Color select to WordPress Theme Customizer
require_once( get_stylesheet_directory() . '/lib/customize.php' );

//* Include Customizer CSS
include_once( get_stylesheet_directory() . '/lib/output.php' );

//* Child theme (do not remove)
define( 'CHILD_THEME_NAME', 'Open Sky - Template' );
define( 'CHILD_THEME_URL', 'http://www.openskywebstudio.com/' );
define( 'CHILD_THEME_VERSION', '2.2.3' );

//* Enqueue Scripts and Styles
add_action( 'wp_enqueue_scripts', 'opensky_enqueue_scripts_styles' );
function opensky_enqueue_scripts_styles() {

	wp_enqueue_style( 'opensky-fonts', '//fonts.googleapis.com/css?family=Open+Sans:400,600,700', array(), CHILD_THEME_VERSION );
	wp_enqueue_style( 'dashicons' );

	wp_enqueue_script( 'opensky-responsive-menu', CHILD_URL . '/js/responsive-menu.js', array( 'jquery' ), '1.0.0', true );
	$output = array(
		'mainMenu' => __( 'Menu', 'opensky' ),
		'subMenu'  => __( 'Menu', 'opensky' ),
	);
	wp_localize_script( 'opensky-responsive-menu', 'openskyL10n', $output );

}

//* Add HTML5 markup structure
add_theme_support( 'html5', array( 'caption', 'comment-form', 'comment-list', 'gallery', 'search-form' ) );

//* Add Accessibility support
add_theme_support( 'genesis-accessibility', array( '404-page', 'drop-down-menu', 'headings', 'rems', 'search-form', 'skip-links' ) );

//* Add viewport meta tag for mobile browsers
add_theme_support( 'genesis-responsive-viewport' );

//* Add support for custom header
add_theme_support( 'custom-header', array(
	'width'            => 600,
	'height'           => 160,
	'flex-height'      => true,
	'flex-width'       => true,
	'header-text'      => false,
) );

//* Add support for custom background
//add_theme_support( 'custom-background' );

//* Add support for after entry widget
add_theme_support( 'genesis-after-entry-widget-area' );

//* Add support for 3-column footer widgets
add_theme_support( 'genesis-footer-widgets', 3 );

//* Add Image Sizes
add_image_size( 'featured-image', 720, 400, true );

//* Rename primary and secondary navigation menus
add_theme_support( 'genesis-menus' , array( 'primary' => __( 'Primary Navigation', 'opensky' ), 'secondary' => __( 'Footer Menu', 'opensky' ) ) );

//* Reposition the secondary navigation menu
remove_action( 'genesis_after_header', 'genesis_do_subnav' );
add_action( 'genesis_footer', 'genesis_do_subnav', 5 );

//* Reduce the secondary navigation menu to one level depth
add_filter( 'wp_nav_menu_args', 'opensky_secondary_menu_args' );
function opensky_secondary_menu_args( $args ) {

	if ( 'secondary' != $args['theme_location'] ) {
		return $args;
	}

	$args['depth'] = 1;

	return $args;

}

//* Modify size of the Gravatar in the author box
add_filter( 'genesis_author_box_gravatar_size', 'opensky_author_box_gravatar' );
function opensky_author_box_gravatar( $size ) {

	return 90;

}

//* Modify size of the Gravatar in the entry comments
add_filter( 'genesis_comment_list_args', 'opensky_comments_gravatar' );
function opensky_comments_gravatar( $args ) {

	$args['avatar_size'] = 60;

	return $args;

}

// Remove Header Right widget area
unregister_sidebar( 'header-right' );

// Reposition the primary navigation menu
remove_action( 'genesis_after_header', 'genesis_do_nav' );
add_action( 'genesis_header', 'genesis_do_nav' );

// Remove Primary Navigation's wrap
add_theme_support( 'genesis-structural-wraps', array(
	'header',
	// 'nav',
	'subnav',
	'footer-widgets',
	'footer'
) );

// Remove custom Genesis custom header style
remove_action( 'wp_head', 'genesis_custom_header_style' );

/**********************************
 *
 * Replace Header Site Title with Inline Logo
 *
 * @author AlphaBlossom / Tony Eppright, Neil Gee
 * @link http://www.alphablossom.com/a-better-wordpress-genesis-responsive-logo-header/
 * @link https://wpbeaches.com/adding-in-a-responsive-html-logoimage-header-via-the-customizer-for-genesis/
 *
 * @edited by Sridhar Katakam
 * @link https://sridharkatakam.com/
 *
************************************/
add_filter( 'genesis_seo_title', 'custom_header_inline_logo', 10, 3 );
function custom_header_inline_logo( $title, $inside, $wrap ) {

	if ( get_header_image() ) {
		$logo = '<img  src="' . get_header_image() . '" width="' . esc_attr( get_custom_header()->width ) . '" height="' . esc_attr( get_custom_header()->height ) . '" alt="' . esc_attr( get_bloginfo( 'name' ) ) . ' Homepage">';
	} else {
		$logo = get_bloginfo( 'name' );
	}

	$inside = sprintf( '<a href="%s">%s<span class="screen-reader-text">%s</span></a>', trailingslashit( home_url() ), $logo, get_bloginfo( 'name' ) );

	// Determine which wrapping tags to use
	$wrap = genesis_is_root_page() && 'title' === genesis_get_seo_option( 'home_h1_on' ) ? 'h1' : 'p';

	// A little fallback, in case an SEO plugin is active
	$wrap = genesis_is_root_page() && ! genesis_get_seo_option( 'home_h1_on' ) ? 'h1' : $wrap;

	// And finally, $wrap in h1 if HTML5 & semantic headings enabled
	$wrap = genesis_html5() && genesis_get_seo_option( 'semantic_headings' ) ? 'h1' : $wrap;

	return sprintf( '<%1$s %2$s>%3$s</%1$s>', $wrap, genesis_attr( 'site-title' ), $inside );

}

// Hide tagline
// add_filter( 'genesis_attr_site-description', 'abte_add_site_description_class' );
/**
 * Add class for screen readers to site description.
 *
 * Unhook this if you'd like to show the site description.
 *
 * @since 1.0.0
 *
 * @param array $attributes Existing HTML attributes for site description element.
 * @return string Amended HTML attributes for site description element.
 */
function abte_add_site_description_class( $attributes ) {
	$attributes['class'] .= ' screen-reader-text';

	return $attributes;
}

/**
 * Remove Genesis Page Templates
 *
 * @author Bill Erickson
 * @link http://www.billerickson.net/remove-genesis-page-templates
 *
 * @param array $page_templates
 * @return array
 */
function be_remove_genesis_page_templates( $page_templates ) {
	unset( $page_templates['page_archive.php'] );
	unset( $page_templates['page_blog.php'] );
	return $page_templates;
}
add_filter( 'theme_page_templates', 'be_remove_genesis_page_templates' );

// Add post navigation
add_action( 'genesis_after_loop', 'genesis_prev_next_post_nav' );

// Display author box on single posts
// add_filter( 'get_the_author_genesis_author_box_single', '__return_true' );

// Display author box on archive pages
// add_filter( 'get_the_author_genesis_author_box_archive', '__return_true' );

/**
 * Remove Metaboxes
 * This removes unused or unneeded metaboxes from Genesis > Theme Settings.
 * See /genesis/lib/admin/theme-settings for all metaboxes.
 *
 * @author Bill Erickson
 * @link http://www.billerickson.net/code/remove-metaboxes-from-genesis-theme-settings/
 */

function be_remove_metaboxes( $_genesis_theme_settings_pagehook ) {
	remove_meta_box( 'genesis-theme-settings-blogpage', $_genesis_theme_settings_pagehook, 'main' );
}

add_action( 'genesis_theme_settings_metaboxes', 'be_remove_metaboxes' );





// Execute Shortcodes in Widgets
add_filter( 'widget_text', 'shortcode_unautop' );
add_filter( 'widget_text', 'do_shortcode' );	
add_filter( 'the_excerpt', 'shortcode_unautop' );
add_filter( 'the_excerpt', 'do_shortcode' );
add_filter( 'the_title', 'do_shortcode' );


/** Customize the return to top of page text */
add_filter( 'genesis_footer_backtotop_text', 'opensky_footer_backtotop_text' );
function opensky_footer_backtotop_text($backtotop) {
    $backtotop = '[footer_backtotop text="Return to Top"]';
    return $backtotop;
}


/** Modify the speak your mind text */
add_filter( 'genesis_comment_form_args', 'opensky_custom_comment_form_args' );
function opensky_custom_comment_form_args($args) {
    $args['title_reply'] = 'Leave a Comment';
    return $args;
}

add_filter('excerpt_more', 'opensky_excerpt_more');
function opensky_excerpt_more($more) {
    return '...';
}

/** Customize the post info function */
add_filter( 'genesis_post_info', 'opensky_post_info_filter' );
function opensky_post_info_filter($post_info) {
	if ( !is_page() ) {
		//$post_info = '[post_date] by [post_author_posts_link] [post_comments] [post_edit]';
		$post_info = '[post_date] [post_comments] [post_edit]';
		return $post_info;
	}
}

//* Remove comment form allowed tags
add_filter( 'comment_form_defaults', 'opensky_remove_comment_form_allowed_tags' );
function opensky_remove_comment_form_allowed_tags( $defaults ) {
	$defaults['comment_notes_after'] = '';
	return $defaults;
}

/** Customize the post meta function */
add_filter( 'genesis_post_meta', 'opensky_post_meta_filter' );
function opensky_post_meta_filter($post_meta) {
	if ( !is_page() ) {
		$post_meta = '[post_categories before="Filed Under: "] [post_tags before="Tagged: "]';
		return $post_meta;
	}
}

// Tell IE to display content in the highest mode available
add_action( 'genesis_meta', 'opensky_ie_display_mode' );
function opensky_ie_display_mode() {
	echo '<meta http-equiv="X-UA-Compatible" content="IE=edge">';
}

// Set default JPG quality for uploads
add_filter( 'jpeg_quality', create_function( '', 'return 90;' ) );

// Redirect Users to Home page on Redirect
add_filter('login_redirect', 'opensky_login_redirect_home', 10, 3);
function opensky_login_redirect_home( $redirect_to, $request, $user ) {
	if ( !is_admin() ) return home_url();
	else return $redirect_to;
}

/** Remove favicon */
remove_action('genesis_meta', 'genesis_load_favicon');


// Copyright Credit Shortcode
// Usage: [credits oscredit="false" title="" link="" link_title="" linebreak="true"]
add_filter('genesis_footer_creds_text', 'opensky_credits_function');
add_shortcode('credits', 'opensky_credits_function');
function opensky_credits_function( $atts, $content = null ) {
   extract( shortcode_atts( array(
      'oscredit' => 'true', //set true|false for link to Open Sky
      'title' => 'Open Sky Web Studio',
      'link' => 'http://www.openskywebstudio.com',
      'link_title' => 'Open Sky Web Studio | Clean, Effective Websites',
      'linebreak' => 'true',
      ), $atts ) );
	$creds = "<div id='credits'>Copyright &copy; " . date('Y') . " <a href='".get_option('home')."' title='".get_bloginfo('name')." | ".get_bloginfo('description')."'>".get_bloginfo('name')."</a>. All Rights Reserved. ";
	if (strtolower($oscredit) == "false")
		$creds .= "<!--";
	if (strtolower($linebreak) == "true")
		$creds .= "<br/>";
	$creds .= "Site Design: <a href='".$link."' title='".$link_title."' target='_blank'>".$title."</a> &middot; ";
	if (strtolower($oscredit) == "false")
		$creds .= "-->";
	$creds .= wp_loginout('', false) . wp_register(' &middot; ', '', false) . '</div>';
	return $creds;
}

// Gravity Forms Feature: Field Label Visibility
add_filter( 'gform_enable_field_label_visibility_settings', '__return_true' );
