<?php
/*
 * Plugin Name: APS Arena Products 
 * Plugin URI: http://www.webstudio55.com/plugins/arena/
 * Description: Add Products attributes website in less than 5 minutes, without knowledge of php and WordPress coding.
 * Version: 1.2
 * Text Domain: aps-text
 * Author: Shahzad Anjum
 * Author URI: http://www.webstudio55.com/
*/
	
	// define common constants
	define( 'APS_VER', 1.2 );
	define( 'APS_NAME', 'APS Arena Products' );
	define( 'APS_URL', WP_PLUGIN_URL .'/' .str_replace(basename( __FILE__), '', plugin_basename(__FILE__)) );
	define( 'APS_DIR', WP_PLUGIN_DIR .'/' .str_replace(basename( __FILE__), '', plugin_basename(__FILE__)) );
	
	// register activation hook
	register_activation_hook( __FILE__, 'aps_plugin_activate' );
	
	// register deactivation hook
	register_deactivation_hook( __FILE__, 'aps_plugin_deactivate' );
	
	// get ready for localization
	add_action('plugins_loaded', 'aps_localization_init');
	
	function aps_localization_init() {
		load_plugin_textdomain( 'aps-text', false, APS_DIR .'/langs/' ); 
	}
	
	// include APS post type
	include(APS_DIR .'/inc/aps-post.php');
	
	// include APS attributes
	include(APS_DIR .'/inc/aps-attributes.php');
	
	// include APS functions
	include(APS_DIR .'/inc/aps-functions.php');
	
	// include APS settings
	include(APS_DIR .'/inc/aps-settings.php');
	
	// include APS image resizing
	include(APS_DIR .'/inc/aps-image.php');
	
	// include APS widgets
	include(APS_DIR .'/inc/aps-widgets.php');

	// include APS Control Panel
	include(APS_DIR .'/inc/aps-control.php');

	// include APS Shortcodes
	include(APS_DIR .'/inc/aps-shortcodes.php');
	
	// add menu page
	add_action('admin_menu', 'register_aps_menu_pages');

	function register_aps_menu_pages() {
		$groups_page = add_submenu_page('edit.php?post_type=aps-products', 'APS Groups', 'APS Groups', 'manage_options', 'aps-groups', 'build_aps_groups_page');
		$attributes_page = add_submenu_page('edit.php?post_type=aps-products', 'APS Attributes', 'APS Attributes', 'manage_options', 'aps-attributes', 'build_aps_attributes_page');
		$filters_page = add_submenu_page('edit.php?post_type=aps-products', 'APS Filters', 'APS Filters', 'manage_options', 'aps-filters', 'build_aps_filters_page');
		$settings_page = add_submenu_page('edit.php?post_type=aps-products', 'APS Settings', 'APS Settings', 'manage_options', 'aps-settings', 'build_aps_settings_page');
		add_action( 'admin_print_scripts-' .$groups_page, 'aps_add_scripts_to_groups' );
		add_action( 'admin_print_scripts-' .$attributes_page, 'aps_add_scripts_to_groups' );
		add_action( 'admin_print_scripts-' .$filters_page, 'aps_add_scripts_to_groups' );
		add_action( 'admin_print_scripts-' .$settings_page, 'aps_add_scripts_to_settings' );
	}
	
	function aps_add_scripts_to_groups() {
		// enqueue jquery ui sortable
		wp_enqueue_script( 'jquery-ui-sortable' );
		
		// enqueue admin css styles
		wp_enqueue_style( 'aps-admin-style', APS_URL .'css/aps-admin.css' );
	}
	
	function aps_add_scripts_to_settings() {
		// enqueue jquery ui sortable
		wp_enqueue_script( 'jquery-ui-sortable' );
		
		// enqueue admin css styles
		wp_enqueue_style( 'aps-admin-style', APS_URL .'css/aps-admin.css' );
		
		// enqueue new wp color picker css
		wp_enqueue_style( 'wp-color-picker' );
		
		// enqueue new wp color picker script
		wp_enqueue_script( 'wp-color-picker' );
		wp_enqueue_media();
	}

	// add an option on activation
	function aps_plugin_activate() {
		$installed = get_option('aps_installed');
		
		if (!$installed) {
			// save default plugin settings
			aps_load_default_settings();
			
			// assign sidebar widgets
			aps_sidebar_widgets_setup();
			
			// update option insatlled = true
			update_option('aps_installed', true);
		}
	}
	
	function aps_add_settings_link( $links ) {
		$settings_link = '<a href="edit.php?post_type=aps-products&page=aps-settings">' .__( 'Settings' ) . '</a>';
		array_push( $links, $settings_link );
		return $links;
	}
	$plugin = plugin_basename(__FILE__);
	add_filter( "plugin_action_links_$plugin", 'aps_add_settings_link' );
	
	// add featured image support
	function aps_add_thumbnail_support() {
		if (!get_theme_support('post-thumbnails')) {
			add_theme_support('post-thumbnails');
		}
	}
	add_action('after_setup_theme', 'aps_add_thumbnail_support', 11);
	
	// load single aps-products template
	function load_aps_single_template($template) {
		global $post;
		
		if ($post->post_type == 'aps-products') {
			$template = APS_DIR .'/inc/aps-single.php';
		} elseif ($post->post_type == 'aps-comparisons') {
			$template = APS_DIR .'/inc/aps-compare.php';
		}
		return $template;
	}
	add_filter('single_template', 'load_aps_single_template');
	
	// load aps-brands archive template
	function aps_brands_template($template) {
		if (is_tax('aps-brands')) {
			$template = APS_DIR .'/inc/aps-archive.php';
		}
		return $template;
	}
	add_filter('archive_template', 'aps_brands_template');
	
	// set posts per page on brands archive
	function aps_archive_posts_per_page( $default ) {
		
		if (is_tax('aps-brands') || is_search()) {
			$settings = get_aps_settings();
			return ($num = $settings['num-products']) ? $num : 12;
		}
		return $default;
	}
	add_filter( 'option_posts_per_page', 'aps_archive_posts_per_page' );

	// load reviews template for aps-products
	function aps_reviews_template( $template ) {
		global $post;
		
		if (is_single() && $post->post_type == 'aps-products') {
			$template = APS_DIR .'/inc/aps-reviews.php';
		}
		return $template;
	}
	add_filter( 'comments_template', 'aps_reviews_template' );
	
	// load index page template for home page
	function aps_index_page_template( $template ) {
		global $post;
		
		$settings = get_aps_settings();
		$page = (int) $settings['index-page'];
		
		if (is_page($page)) {
			$template = APS_DIR .'/inc/aps-index.php';
		}
		return $template;
	}
	add_filter( 'page_template', 'aps_index_page_template' );
	
	// load compare page template for Compare page
	function aps_compare_page_template( $template ) {
		global $post;
		$settings = get_aps_settings();
		
		if (is_page($settings['comp-page'])) {
			$template = APS_DIR .'/inc/aps-compare.php';
		} else if (is_page($settings['comp-list'])) {
			$template = APS_DIR .'/inc/aps-comparisons.php';
		}
		return $template;
	}
	add_filter( 'page_template', 'aps_compare_page_template' );
	
	// search templates for aps-products
	function aps_products_search_template($template) {
		global $wp_query;
		if ($wp_query->is_search) {
			$post_type = get_query_var('post_type');
			
			if ($post_type == 'aps-products' ) {
				$template = APS_DIR .'/inc/aps-search.php';
			}
		}		
		return $template;   
	}
	add_filter('search_template', 'aps_products_search_template');