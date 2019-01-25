<?php
/**
 * Create and hook in the Instant IDE Manager admin menus.
 *
 * @package Instant IDE Manager
 */
 
add_action( 'admin_enqueue_scripts', 'instant_ide_manager_enqueue_admin_scripts' );
/*
 * Enqueue admin styles and scripts.
 */
function instant_ide_manager_enqueue_admin_scripts() {
    
    // Enqueue the iIDE icons for the admin menu.
	wp_enqueue_style( 'instant-ide-manager-icons', IIDEM_URL . 'lib/css/icons.css', array(), IIDEM_VERSION );
	
}

add_action( 'admin_menu', 'instant_ide_manager_admin_menu' );
/**
 * Create the Instant IDE Manager admin sub menus.
 *
 * @since 1.0.0
 */
function instant_ide_manager_admin_menu() {
	
	add_menu_page( __( 'Instant IDE', 'instant-ide-manager' ), __( 'Instant IDE', 'instant-ide-manager' ), 'manage_options', 'instant-ide-manager-dashboard', 'instant_ide_manager_settings', '', 59 );
	
	$_instant_ide_manager_settings = add_submenu_page( 'instant-ide-manager-dashboard', __( 'Settings', 'instant-ide-manager' ), __( 'Settings', 'instant-ide-manager' ), 'manage_options', 'instant-ide-manager-dashboard', 'instant_ide_manager_settings' );
	
	add_action( 'admin_print_styles-' . $_instant_ide_manager_settings, 'instant_ide_manager_admin_styles' );

}

add_action( 'admin_init', 'instant_ide_manager_admin_init' );
/**
 * Register styles and scripts for the Instant IDE Manager admin menus.
 *
 * @since 1.0.0
 */
function instant_ide_manager_admin_init() {
	
	wp_register_style( 'instant_ide_manager_admin_styles', IIDEM_URL . 'lib/css/admin.css', array(), IIDEM_VERSION );
	
	wp_register_script( 'instant_ide_manager_admin', IIDEM_URL . 'lib/js/admin-options.js', array(), IIDEM_VERSION );
	
}

/**
 * Enqueue styles and scripts for the Instant IDE Manager admin menus.
 *
 * @since 1.0.0
 */
function instant_ide_manager_admin_styles() {
	
	wp_enqueue_style( 'instant_ide_manager_admin_styles' );
	
	wp_enqueue_script( 'instant_ide_manager_admin' );
	
	$vars = array(
		'iideAjaxNonce' => wp_create_nonce( 'iide-ajax-nonce' ),
		'homeUrl' => get_home_url(),
		'iideDirName' => IIDEM_IIDE_DIR_NAME,
	);
	
	wp_localize_script( 'instant_ide_manager_admin', 'iidemAdminL10n', $vars );
	
}
