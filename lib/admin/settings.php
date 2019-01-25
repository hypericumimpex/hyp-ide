<?php
/**
 * Builds the Settings admin page.
 *
 * @package Instant IDE Manager
 */
 
/**
 * Build the Instant IDE Manager Settings admin page.
 *
 * @since 1.0.0
 */
function instant_ide_manager_settings() {
	
	$iide_site_url = is_multisite() ? network_site_url() : get_home_url() . '/';
	// Change to true if you forgot your PIN and need temp access to change it.
	$temp_access = false;
	
	if ( false == $temp_access && get_option( 'instant_ide_manager_access_pin' ) != '' && get_option( 'instant_ide_manager_access_status' ) == 'locked' )
		$iide_manager_locked = true;
	else
		$iide_manager_locked = false;
		
	if ( get_option( 'instant_ide_manager_access_status' ) == 'unlocked' )
		update_option( 'instant_ide_manager_access_status', 'locked' );
		
?>
	<div class="wrap">
		
		<div id="icon-options-general" class="icon32"></div>
		
		<h2 id="instant-ide-manager-admin-heading"><?php _e( 'Instant IDE Manager', 'instant-ide-manager' ); ?></h2>

		<?php if ( false === $iide_manager_locked && IIDE_CURRENT_VERSION !== 'Not Installed' ) { ?>
		<div id="instant-ide-manager-htaccess-open-button">
			<span><?php _e( '.htaccess', 'instant-ide-manager' ); ?></span>
			<img class="instant-ide-manager-ajax-save-spinner" src="<?php echo site_url() ?>/wp-admin/images/spinner-2x.gif" />
		</div>
		<?php } ?>
		
		<div id="instant-ide-manager-admin-wrap">
			
			<div class="instant-ide-manager-settings-wrap">
				<?php
				if ( false === $iide_manager_locked ) {
					require_once( IIDEM_DIR . 'lib/admin/boxes/settings.php' );
				} else {
					require_once( IIDEM_DIR . 'lib/admin/boxes/admin-access.php' );
				} ?>
			</div>
			
		</div>
	</div> <!-- Close Wrap -->
<?php

}

add_action( 'wp_ajax_instant_ide_manager_iide_install', 'instant_ide_manager_iide_install' );
/**
 * Use ajax to install Instant IDE and then reload the page.
 *
 * @since 1.0.0
 */
function instant_ide_manager_iide_install() {
	
	check_ajax_referer( 'iide-ajax-nonce', 'security' );
	
	instant_ide_manager_copy_dir( IIDEM_IDE_DIR . '/instant-ide', get_home_path() . $_POST['dir'] );
	
	update_option( 'instant_ide_manager_access_status', 'unlocked' );
	
	echo 'Instant IDE Installed';
	exit();
	
}

add_action( 'wp_ajax_instant_ide_manager_iide_uninstall', 'instant_ide_manager_iide_uninstall' );
/**
 * Use ajax to uninstall Instant IDE and then reload the page.
 *
 * @since 1.0.0
 */
function instant_ide_manager_iide_uninstall() {
	
	check_ajax_referer( 'iide-ajax-nonce', 'security' );
	
	instant_ide_manager_delete_dir( IIDEM_IIDE_DIR );
	
	update_option( 'instant_ide_manager_access_status', 'unlocked' );
		
	echo 'Instant IDE Uninstalled';
	exit();
	
}

add_action( 'wp_ajax_instant_ide_manager_htaccess_open', 'instant_ide_manager_htaccess_open' );
/**
 * Use ajax to open the Instant IDE .htaccess file based on the current values.
 *
 * @since 1.0.0
 */
function instant_ide_manager_htaccess_open() {
	
	check_ajax_referer( 'iide-ajax-nonce', 'security' );
	
	if ( file_exists( IIDEM_IIDE_DIR . '/.htaccess' ) )
		echo file_get_contents( IIDEM_IIDE_DIR . '/.htaccess' );
		
	exit();
	
}

add_action( 'wp_ajax_instant_ide_manager_htaccess_restore', 'instant_ide_manager_htaccess_restore' );
/**
 * Use ajax to restore the Instant IDE .htaccess file based on the last saved version.
 *
 * @since 1.0.0
 */
function instant_ide_manager_htaccess_restore() {
	
	check_ajax_referer( 'iide-ajax-nonce', 'security' );
	
	if ( file_exists( instant_ide_manager_get_uploads_path() . '/.htaccess' ) )
		echo file_get_contents( instant_ide_manager_get_uploads_path() . '/.htaccess' );
		
	exit();
	
}

add_action( 'wp_ajax_instant_ide_manager_htaccess_save', 'instant_ide_manager_htaccess_save' );
/**
 * Use ajax to update the Instant IDE .htaccess file based on the posted values.
 *
 * @since 1.0.0
 */
function instant_ide_manager_htaccess_save() {
	
	check_ajax_referer( 'iide-ajax-nonce', 'security' );
	
	// Write saved content to .htaccess file.
	instant_ide_manager_write_file( IIDEM_IIDE_DIR . '/.htaccess', $_POST['iide']['htaccess'] );
	
	// Create a backup of last saved version of file.
	instant_ide_manager_write_file( instant_ide_manager_get_uploads_path() . '/.htaccess', $_POST['iide']['htaccess'] );
	
	echo 'File Updated';
	exit();
	
}

add_action( 'wp_ajax_instant_ide_manager_admin_access_pin', 'instant_ide_manager_admin_access_pin' );
/**
 * Use ajax to save the admin access pin based on the posted values.
 *
 * @since 1.1.0
 */
function instant_ide_manager_admin_access_pin() {
	
	check_ajax_referer( 'iide-ajax-nonce', 'security' );
	
	update_option( 'instant_ide_manager_access_pin', $_POST['iidem']['access_pin'] );
	
	echo 'Pin Saved!';
	exit();
	
}

add_action( 'wp_ajax_instant_ide_manager_admin_access_check', 'instant_ide_manager_admin_access_check' );
/**
 * Use ajax to compare pin numbers and grant access to the Instant IDE Manager admin area.
 *
 * @since 1.1.0
 */
function instant_ide_manager_admin_access_check() {
	
	check_ajax_referer( 'iide-ajax-nonce', 'security' );
	
	if ( $_POST['iidem']['access_pin'] == get_option( 'instant_ide_manager_access_pin' ) ) {
		
		update_option( 'instant_ide_manager_access_status', 'unlocked' );
		echo 'Pin Verified!';
		
	} else {
		
		echo 'Incorrect Pin';
		
	}
		
	exit();
	
}
