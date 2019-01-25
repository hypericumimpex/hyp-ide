<?php
/**
 * Handles the auto-update and license key functionality.
 *
 * @package Instant IDE Manager
 */

// this is the URL our updater / license checker pings. This should be the URL of the site with EDD installed
define( 'IIDEM_COBALT_APPS_URL', 'https://cobaltapps.com' );

// the name of your product. This should match the download name in EDD exactly
define( 'IIDEM_INSTANT_IDE_MANAGER', 'Instant IDE Manager' );

if ( ! class_exists( 'EDD_SL_Plugin_Updater' ) ) {
	
	// load our custom updater
	include( dirname( __FILE__ ) . '/EDD_SL_Plugin_Updater.php' );
	
}

add_action( 'admin_init', 'instant_ide_manager_sl_plugin_updater', 0 );
/**
 * Create a new instance of the EDD_SL_Theme_Updater class with a unique set of values.
 *
 * @since 1.0.0
 */
function instant_ide_manager_sl_plugin_updater() {
	
	// retrieve our license key from the DB
	$license_key = trim( get_option( 'instant_ide_manager_license_key' ) );

	// setup the updater
	$edd_updater = new EDD_SL_Plugin_Updater( IIDEM_COBALT_APPS_URL, IIDEM_DIR . 'instant-ide-manager.php', array(
			'version' 	=> IIDEM_VERSION, 				// current version number
			'license' 	=> $license_key, 		// license key (used get_option above to retrieve from DB)
			'item_name' => IIDEM_INSTANT_IDE_MANAGER, 	// name of this plugin
			'author' 	=> 'Cobalt Apps'  // author of this plugin
		)
	);
	
}

/**
 * Build the License Options admin section.
 *
 * @since 1.0.0
 */
function instant_ide_manager_license_options() {
	return true;

	?>
	<div class="bg-box bg-box-licenses">
		
		<?php $license = get_option( 'instant_ide_manager_license_key' ); ?>
		<?php $status = get_option( 'instant_ide_manager_license_status' ); ?>
		
		<form method="post" action="options.php">
			<?php settings_fields( 'instant_ide_manager_license' ); ?>
			<p>
				<?php _e( 'Instant IDE Manager Version: ', 'instant-ide-manager' ); ?><b><code><?php echo esc_attr( IIDEM_VERSION ) ?></code></b> <a href="<?php echo IIDEM_URL . 'CHANGELOG.md'; ?>" target="_blank">(Change Log)</a><br /><?php _e( ' License Key', 'instant-ide-manager' ); ?>
				<input id="instant_ide_manager_license_key" name="instant_ide_manager_license_key" type="password" class="regular-text" value="<?php echo esc_attr_e( $license ); ?>" style="width:100%; max-width:160px;"/>
	
				<?php if ( false !== $license && $license != '' ) { ?>
					<?php if ( $status !== false && $status == 'valid' ) { ?>
						<span style="color:green;"><?php _e('active', 'instant-ide-manager' ); ?></span>
						<?php wp_nonce_field( 'edd_instant_ide_manager_nonce', 'edd_instant_ide_manager_nonce' ); ?>
						<input type="submit" class="button" name="instant_ide_manager_license_deactivate" value="<?php _e('Deactivate License', 'instant-ide-manager' ); ?>" style="margin-top:-9px !important;"/>
					<?php } else { ?>
						<span style="color:red;"><?php _e('inactive', 'instant-ide-manager' ); ?></span>
						<?php wp_nonce_field( 'edd_instant_ide_manager_nonce', 'edd_instant_ide_manager_nonce' ); ?>
						<input type="submit" class="button" name="instant_ide_manager_license_activate" value="<?php _e('Activate License', 'instant-ide-manager' ); ?>" style="margin-top:-9px !important;"/>
					<?php } ?>
				<?php } ?>
	
				<input type="submit" name="submit" id="submit" class="button" value="<?php _e( 'Save Changes', 'instant-ide-manager' ); ?>" style="margin-top:-9px !important;"/>
			</p>
		</form>
		
	</div>
	<?php
	
}

add_action( 'admin_init', 'instant_ide_manager_register_license_option' );
/**
 * Register the instant_ide_manager_license setting.
 *
 * @since 1.0.0
 */
function instant_ide_manager_register_license_option() {
	
	// creates our settings in the options table
	register_setting( 'instant_ide_manager_license', 'instant_ide_manager_license_key', 'instant_ide_manager_sanitize_license' );
	
}

/**
 * Sanatize the Cobalt License option.
 *
 * @since 1.0.0
 */
function instant_ide_manager_sanitize_license( $new ) {
	
	$old = get_option( 'instant_ide_manager_license_key' );
	if ( $old && $old != $new )
		delete_option( 'instant_ide_manager_license_status' ); // new license has been entered, so must reactivate

	return $new;
	
}

/************************************
* this illustrates how to activate
* a license key
*************************************/

add_action( 'admin_init', 'instant_ide_manager_activate_license' );
/**
 * Attempt to activate the currently set license option value.
 *
 * @since 1.0.0
 */
function instant_ide_manager_activate_license() {
	
	// listen for our activate button to be clicked
	if ( isset( $_POST['instant_ide_manager_license_activate'] ) ) {
		
		// run a quick security check
	 	if ( ! check_admin_referer( 'edd_instant_ide_manager_nonce', 'edd_instant_ide_manager_nonce' ) )
			return; // get out if we didn't click the Activate button

		// retrieve the license from the database
		$license = trim( get_option( 'instant_ide_manager_license_key' ) );

		// data to send in our API request
		$api_params = array(
			'edd_action'=> 'activate_license',
			'license' 	=> $license,
			'item_name' => urlencode( IIDEM_INSTANT_IDE_MANAGER ), // the name of our product in EDD
			'url'       => home_url()
		);
		
		// Call the custom API.
		$response = wp_remote_post( IIDEM_COBALT_APPS_URL, array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ) );

		// make sure the response came back okay
		if ( is_wp_error( $response ) )
			return false;

		// decode the license data
		$license_data = json_decode( wp_remote_retrieve_body( $response ) );
		
		// $license_data->license will be either "active" or "inactive"

		update_option( 'instant_ide_manager_license_status', $license_data->license );
		
	}
	
}

/***********************************************
* Illustrates how to deactivate a license key.
* This will descrease the site count
***********************************************/

add_action( 'admin_init', 'instant_ide_manager_deactivate_license' );
/**
 * Deactivate the currently active license key.
 *
 * @since 1.0.0
 */
function instant_ide_manager_deactivate_license() {
	
	// listen for our activate button to be clicked
	if ( isset( $_POST['instant_ide_manager_license_deactivate'] ) ) {
		
		// run a quick security check
	 	if ( ! check_admin_referer( 'edd_instant_ide_manager_nonce', 'edd_instant_ide_manager_nonce' ) )
			return; // get out if we didn't click the Activate button

		// retrieve the license from the database
		$license = trim( get_option( 'instant_ide_manager_license_key' ) );
			
		// data to send in our API request
		$api_params = array(
			'edd_action'=> 'deactivate_license',
			'license' 	=> $license,
			'item_name' => urlencode( IIDEM_INSTANT_IDE_MANAGER ), // the name of our product in EDD
			'url'       => home_url()
		);
		
		// Call the custom API.
		$response = wp_remote_post( IIDEM_COBALT_APPS_URL, array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ) );

		// make sure the response came back okay
		if ( is_wp_error( $response ) )
			return false;

		// decode the license data
		$license_data = json_decode( wp_remote_retrieve_body( $response ) );
		
		// $license_data->license will be either "deactivated" or "failed"
		if ( $license_data->license == 'deactivated' )
			delete_option( 'instant_ide_manager_license_status' );
			
	}
	
}

add_action( 'admin_init', 'instant_ide_manager_check_license' );
/**
 * Check the current Instant IDE Manager license key with the CobaltApps.com
 * "Manage Sites" status and update the local license status accordingly.
 *
 * @since 1.0.0.2
 */
function instant_ide_manager_check_license() {
	
	if ( ! empty( $_POST['instant_ide_manager_license_key'] ) )
		return; // Don't fire when saving settings
	
	$status = get_transient( 'instant_ide_manager_license_check' );

	// Run the license check a maximum of once per day
	if ( false === $status ) {
		
		// retrieve the license from the database
		$license = trim( get_option( 'instant_ide_manager_license_key' ) );

		// data to send in our API request
		$api_params = array(
			'edd_action'=> 'check_license',
			'license' 	=> $license,
			'item_name' => urlencode( IIDEM_INSTANT_IDE_MANAGER ),
			'url'       => home_url()
		);

		// Call the custom API.
		$response = wp_remote_post( IIDEM_COBALT_APPS_URL, array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ) );

		// make sure the response came back okay
		if ( is_wp_error( $response ) )
			return false;

		$license_data = json_decode( wp_remote_retrieve_body( $response ) );

		if ( $license_data->license !== false && $license_data->license == 'valid' )
			update_option( 'instant_ide_manager_license_status', 'valid' );
		else
			update_option( 'instant_ide_manager_license_status', 'invalid' );

		set_transient( 'instant_ide_manager_license_check', $license, DAY_IN_SECONDS );

		$status = $license;
		
	}

	return $status;
	
}
