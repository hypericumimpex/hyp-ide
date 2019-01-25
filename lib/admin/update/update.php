<?php
/**
 * Handles both the activation and update functionality.
 *
 * @package Instant IDE Manager
 */

add_action( 'admin_init', 'instant_ide_manager_update' );
/**
 * Perform Instant IDE Manager updates based on current version number.
 *
 * @since 1.0.0
 */
function instant_ide_manager_update() {
	
	// Initialize the update sequence.
	instant_ide_manager_activate_pre();
	
	// Don't do anything if we're on the latest version.
	if ( version_compare( get_option( 'instant_ide_manager_version_number' ), IIDEM_VERSION, '>=' ) )
		return;

	// Update to Instant IDE Manager 1.0.1
	if ( version_compare( get_option( 'instant_ide_manager_version_number' ), '1.0.1', '<' ) )
		update_option( 'instant_ide_manager_version_number', '1.0.1' );
		
	// Update to Instant IDE Manager 1.0.2
	if ( version_compare( get_option( 'instant_ide_manager_version_number' ), '1.0.2', '<' ) )
		update_option( 'instant_ide_manager_version_number', '1.0.2' );
		
	// Update to Instant IDE Manager 1.0.3
	if ( version_compare( get_option( 'instant_ide_manager_version_number' ), '1.0.3', '<' ) ) {
		
		// "tmp" was used to backup htaccess but no longer needed since backup location moved.
		if ( file_exists( IIDEM_IDE_DIR . '/tmp/' ) )
			instant_ide_manager_delete_dir( IIDEM_IDE_DIR . '/tmp/' );
		
		update_option( 'instant_ide_manager_version_number', '1.0.3' );
		
	}
	
	// Update to Instant IDE Manager 1.0.4
	if ( version_compare( get_option( 'instant_ide_manager_version_number' ), '1.0.4', '<' ) )
		update_option( 'instant_ide_manager_version_number', '1.0.4' );
		
	// Update to Instant IDE Manager 1.1.0
	if ( version_compare( get_option( 'instant_ide_manager_version_number' ), '1.1.0', '<' ) )
		update_option( 'instant_ide_manager_version_number', '1.1.0' );
		
	// Update to Instant IDE Manager 1.1.1
	if ( version_compare( get_option( 'instant_ide_manager_version_number' ), '1.1.1', '<' ) )
		update_option( 'instant_ide_manager_version_number', '1.1.1' );
		
	// Update to Instant IDE Manager 1.1.2
	if ( version_compare( get_option( 'instant_ide_manager_version_number' ), '1.1.2', '<' ) )
		update_option( 'instant_ide_manager_version_number', '1.1.2' );
		
	// Update to Instant IDE Manager 1.1.3
	if ( version_compare( get_option( 'instant_ide_manager_version_number' ), '1.1.3', '<' ) )
		update_option( 'instant_ide_manager_version_number', '1.1.3' );
		
	// Update to Instant IDE Manager 1.1.4
	if ( version_compare( get_option( 'instant_ide_manager_version_number' ), '1.1.4', '<' ) )
		update_option( 'instant_ide_manager_version_number', '1.1.4' );
		
	// Update to Instant IDE Manager 1.1.5
	if ( version_compare( get_option( 'instant_ide_manager_version_number' ), '1.1.5', '<' ) )
		update_option( 'instant_ide_manager_version_number', '1.1.5' );
		
	// Update to Instant IDE Manager 1.1.6
	if ( version_compare( get_option( 'instant_ide_manager_version_number' ), '1.1.6', '<' ) )
		update_option( 'instant_ide_manager_version_number', '1.1.6' );
		
	// Update to Instant IDE Manager 1.1.7
	if ( version_compare( get_option( 'instant_ide_manager_version_number' ), '1.1.7', '<' ) )
		update_option( 'instant_ide_manager_version_number', '1.1.7' );
	
	// Finalize the update sequence.
	instant_ide_manager_activate_post();
	
}

/**
 * Perform Instant IDE Manager (pre) activation actions.
 *
 * @since 1.0.0
 */
function instant_ide_manager_activate_pre() {
	
	if ( ! get_option( 'instant_ide_manager_access_pin' ) )
		update_option( 'instant_ide_manager_access_pin', '' );
		
	if ( ! get_option( 'instant_ide_manager_access_status' ) )
		update_option( 'instant_ide_manager_access_status', 'locked' );
	
	if ( file_exists( IIDEM_IIDE_DIR . '/iide-constants.php' ) ) {
		
		include_once( IIDEM_IIDE_DIR . '/iide-constants.php' );
		
		if ( version_compare( IIDE_VERSION, IIDE_LATEST_VERSION, '>=' ) )
			return;
			
			$current_iide_dir = IIDEM_IIDE_DIR;
			instant_ide_manager_backup_files();
			instant_ide_manager_delete_dir( IIDEM_IIDE_DIR );
			instant_ide_manager_copy_dir( IIDEM_IDE_DIR . '/instant-ide', $current_iide_dir );
			instant_ide_manager_restore_files();
		
	}
		
}

/**
 * Perform Instant IDE Manager (post) activation actions.
 *
 * @since 1.0.0
 */
function instant_ide_manager_activate_post() {

	if ( ! get_option( 'instant_ide_manager_version_number' ) )
		update_option( 'instant_ide_manager_version_number', IIDEM_VERSION );
		
	instant_ide_manager_dir_check( instant_ide_manager_get_uploads_path() );

}

/**
 * Perform Instant IDE Manager activation actions.
 *
 * @since 1.0.0
 */
function instant_ide_manager_activate() {
	
	instant_ide_manager_activate_pre();
	instant_ide_manager_activate_post();
	
}
