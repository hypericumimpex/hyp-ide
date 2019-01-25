<?php 
/*
Plugin Name: HYP IDE
Version: 1.1.7
Plugin URI: https://github.com/hypericumimpex/hyp-ide
Description: The manager Plugin for the Instant IDE dev tool.
Author: Romeo C.
Author URI: https://github.com/hypericumimpex/
License: GPLv2 or later
License URI: http://www.opensource.org/licenses/gpl-license.php
*/

/**
 * @package Instant IDE Manager
 */
 
// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Define plugin constants.
 */
if ( ! defined( 'IIDEM_DIR' ) )
	define( 'IIDEM_DIR', plugin_dir_path( __FILE__ ) );
	
if ( ! defined( 'IIDEM_IDE_DIR' ) )
	define( 'IIDEM_IDE_DIR', IIDEM_DIR . 'ide' );
	
if ( ! defined( 'IIDEM_URL' ) )
	define( 'IIDEM_URL', plugin_dir_url( __FILE__ ) );
	
// Define the Cobalt Apps WP Admin Bar URL constant.
if ( ! defined( 'CAABM_URL' ) )
	define( 'CAABM_URL', IIDEM_URL );
	
if ( ! defined( 'IIDEM_VERSION' ) )
	define( 'IIDEM_VERSION', '1.1.7' );
	
if ( ! defined( 'IIDE_LATEST_VERSION' ) )
	define( 'IIDE_LATEST_VERSION', '1.1.7' );
	
// Require general functions.
require_once( IIDEM_DIR . 'lib/functions/general.php' );

if ( ! defined( 'IIDEM_IIDE_DIR' ) )
	define( 'IIDEM_IIDE_DIR', instant_ide_manager_iide_root_finder() );
	
if ( ! defined( 'IIDEM_IIDE_DIR_NAME' ) )
	define( 'IIDEM_IIDE_DIR_NAME', basename( IIDEM_IIDE_DIR ) );
	
if ( false !== IIDEM_IIDE_DIR ) {
	
	include_once( IIDEM_IIDE_DIR . '/iide-constants.php' );
	
	if ( ! defined( 'IIDE_CURRENT_VERSION' ) )
		define( 'IIDE_CURRENT_VERSION', IIDE_VERSION );
	
} else {
	
	if ( ! defined( 'IIDE_CURRENT_VERSION' ) )
		define( 'IIDE_CURRENT_VERSION', 'Not Installed' );
	
}

/**
 * Create globals only needed for admin and register activation hook.
 */
if ( is_admin() ) {
	
	// Run Instant IDE Manager activation function.
	register_activation_hook( IIDEM_DIR . 'lib/admin/update/update.php', 'instant_ide_manager_activate' );

}

add_action( 'after_setup_theme', 'instant_ide_manager_init', 15 );
/**
 * Localize and initialize the Instant IDE Manager Plugin.
 *
 * @since 1.0.0
 */
function instant_ide_manager_init() {
	
	// Localization.
	load_plugin_textdomain( 'instant-ide-manager', false, dirname( plugin_basename( __FILE__ ) ) . '/lib/languages' );

	// Include Instant IDE Manager files.
	require_once( IIDEM_DIR . 'lib/init.php' );
	
}
