<?php
/**
 * This is the initialization file for Instant IDE Manager,
 * defining constants, globaling database option arrays
 * and requiring other function files.
 *
 * @package Instant IDE Manager
 */

// Require admin files.
if ( is_admin() ) {
	
	require_once( IIDEM_DIR . 'lib/admin/build-menu.php' );
	require_once( IIDEM_DIR . 'lib/admin/settings.php' );
	require_once( IIDEM_DIR . 'lib/admin/update/edd-updater.php' );
	require_once( IIDEM_DIR . 'lib/admin/update/update.php' );
	
}

// If iIDE is installed then add styles, scripts, and WP admin bar nodes.
if ( ! is_admin() && IIDE_CURRENT_VERSION !== 'Not Installed' ) {
	
	add_action( 'wp_enqueue_scripts', 'instant_ide_manager_scripts' );
	/*
	 * Enqueue Instant IDE Manager styles and scripts.
	 */
	function instant_ide_manager_scripts() {

		wp_enqueue_script( 'instant-ide-manager-scripts', IIDEM_URL . 'lib/js/scripts.js', array( 'jquery' ), IIDEM_VERSION );
		
	}
	
}

// Require the Cobalt Apps Admin Bar Menu code.
require_once( IIDEM_DIR . 'lib/functions/admin-bar-menu.php' );
