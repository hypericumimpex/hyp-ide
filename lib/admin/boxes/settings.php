<?php
/**
 * Builds the Instant IDE Manager Plugin Info admin content.
 *
 * @package Instant IDE Manager
 */
?>

<div id="instant-ide-manager-settings-nav-info-box" class="instant-ide-manager-optionbox-outer-1col instant-ide-manager-all-options instant-ide-manager-options-display">
	<div class="instant-ide-manager-optionbox-2col-left-wrap">
		
		<div class="instant-ide-manager-optionbox-outer-2col">
			<div class="instant-ide-manager-optionbox-inner-2col">
				<h4><?php _e( 'Version/License Information', 'instant-ide-manager' ); ?></h4>
				<div class="bg-box">
					<p>
						<?php _e( 'PHP Version:', 'instant-ide-manager' ); ?> <b><code><?php echo PHP_VERSION ?></code></b>
					</p>
					
					<p>
						<?php _e( 'WordPress Version:', 'instant-ide-manager' ); ?> <b><code><?php echo bloginfo('version') ?></code></b>
					</p>
					
					<p>
						<?php _e( 'Instant IDE Versions:', 'instant-ide-manager' ); ?> (<?php _e( 'Current ', 'instant-ide-manager' ); ?><b><code><?php echo IIDE_CURRENT_VERSION ?></code></b>) (<?php _e( 'Latest ', 'instant-ide-manager' ); ?><b><code><?php echo IIDE_LATEST_VERSION ?></code></b>) <a href="<?php echo $iide_site_url . IIDEM_IIDE_DIR_NAME . '/CHANGELOG.md'; ?>" target="_blank">(Change Log)</a>
					</p>
					
					<?php instant_ide_manager_license_options(); ?>
				</div>
			</div>
		</div>
		
		<?php if ( IIDE_CURRENT_VERSION === 'Not Installed' ) { ?>
		
			<div class="instant-ide-manager-optionbox-outer-2col">
				<div class="instant-ide-manager-optionbox-inner-2col">
					<h4 style="margin:0; border:0;"><?php _e( 'Install Instant IDE', 'instant-ide-manager' ); ?></h4>
					<div class="bg-box">
						<div class="bg-box bg-box-licenses">
							<p id="instant-ide-manager-ajax-install-spinner-container">
								<span>
									<?php _e('Instant IDE Directory Name:', 'instant-ide-manager' ); ?><br>
	    							<span id="instant-ide-manager-install-directory-name" contenteditable="true"><?php echo 'iide-' . hash( 'md5', time() ); ?></span>
								</span>
								<img class="instant-ide-manager-ajax-save-spinner" src="<?php echo site_url() ?>/wp-admin/images/spinner-2x.gif" />
								<button id="instant-ide-manager-install-button" class="button"/>
									<?php _e('Install Instant IDE', 'instant-ide-manager' ); ?>
								</button>
							</p>
						</div>
					</div>
				</div>
			</div>
		
		<?php } else { ?>
		
			<div class="instant-ide-manager-optionbox-outer-2col">
				<div class="instant-ide-manager-optionbox-inner-2col">
					<h4 style="margin:0; border:0;"><?php _e( 'Uninstall Instant IDE', 'instant-ide-manager' ); ?></h4>
					<div class="bg-box">
						<div class="bg-box bg-box-licenses">
							<p id="instant-ide-manager-ajax-uninstall-spinner-container">
								<span>
									<?php _e('Instant IDE Directory Name:', 'instant-ide-manager' ); ?><br>
	    							<span id="instant-ide-manager-uninstall-directory-name"><?php echo IIDEM_IIDE_DIR_NAME; ?></span>
								</span>
								<img class="instant-ide-manager-ajax-save-spinner" src="<?php echo site_url() ?>/wp-admin/images/spinner-2x.gif" />
								<button id="instant-ide-manager-uninstall-button" class="button"/>
									<?php _e('Uninstall Instant IDE', 'instant-ide-manager' ); ?>
								</button>
							</p>
						</div>
					</div>
				</div>
			</div>
		
		<?php } ?>
		
		<div class="instant-ide-manager-optionbox-outer-2col">
			<div class="instant-ide-manager-optionbox-inner-2col">
				<h4 style="margin:0; border:0;"><?php _e( 'Secure Instant IDE Manager Admin', 'instant-ide-manager' ); ?></h4>
				<div class="bg-box">
					<div class="bg-box bg-box-licenses" style="padding:10px 15px;">
						<form action="/" id="instant-ide-manager-admin-access-pin-form">
							<input type="hidden" name="action" value="instant_ide_manager_admin_access_pin" />
							<input type="hidden" name="security" value="<?php echo wp_create_nonce( 'iide-ajax-nonce' ); ?>" />
							
							<div id="instant-ide-manager-admin-access-pin-form-input-container">
								<?php _e('Pin Number', 'instant-ide-manager' ); ?>
								<input id="instant_ide_manager_access_pin" name="iidem[access_pin]" type="password" class="regular-text" value="<?php echo get_option( 'instant_ide_manager_access_pin' ); ?>" style="width:100%; max-width:105px;"/>
								<?php _e('(blank = disabled)', 'instant-ide-manager' ); ?>
								<input type="submit" name="Submit" class="button" value="<?php _e( 'Save Changes', 'instant-ide-manager' ); ?>"/>
								<img class="instant-ide-manager-ajax-save-spinner" src="<?php echo site_url() ?>/wp-admin/images/spinner-2x.gif" />
								<span class="instant-ide-manager-saved"></span>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
		
		<div class="instant-ide-manager-optionbox-outer-2col">
			<div class="instant-ide-manager-optionbox-inner-2col">
				<h4><?php _e( 'Links & Resources', 'instant-ide-manager' ); ?></h4>
				<div class="resource-box">
					<p>
						<?php _e( 'Support & Resources:', 'instant-ide-manager' ); ?> <a href="https://cobaltapps.com/my-account/" target="_blank">https://cobaltapps.com/my-account/</a>
					</p>
					<p>
						<?php _e( 'Documentation:', 'instant-ide-manager' ); ?> <a href="http://docs.cobaltapps.com/" target="_blank">http://docs.cobaltapps.com/</a>
					</p>
					<p>
						<?php _e( 'Community Forum:', 'instant-ide-manager' ); ?> <a href="https://cobaltapps.com/community/" target="_blank">https://cobaltapps.com/community/</a>
					</p>
					<p>
						<?php _e( 'Affiliates:', 'instant-ide-manager' ); ?> <a href="https://cobaltapps.com/affiliates/" target="_blank">https://cobaltapps.com/affiliates/</a>
					</p>
				</div>
			</div>
		</div>
	
	</div>

	<div class="instant-ide-manager-optionbox-2col-right-wrap">

		<div class="instant-ide-manager-optionbox-outer-2col">
			<div class="instant-ide-manager-optionbox-inner-2col">
				<h4><?php _e( 'Instant IDE Viewer', 'instant-ide-manager' ); ?></h4>
				
				<div class="bg-box">
					<?php if ( IIDE_CURRENT_VERSION === 'Not Installed' ) { ?>
						<p style="text-align:center;"><?php _e( 'Instant IDE is not currently installed.', 'instant-ide-manager' ); ?></p>
					<?php } else { ?>
						<iframe id="instant-ide-manager-iide-viewer-iframe" src="<?php echo $iide_site_url . IIDEM_IIDE_DIR_NAME; ?>/"></iframe>
					<?php } ?>
				</div>
			</div>
		</div>

	</div>
</div>

<form action="/" id="instant-ide-manager-htaccess-form">
	<input type="hidden" name="action" value="instant_ide_manager_htaccess_save" />
	<input type="hidden" name="security" value="<?php echo wp_create_nonce( 'iide-ajax-nonce' ); ?>" />
	
	<div id="instant-ide-manager-htaccess-overlay">
		<div id="instant-ide-manager-htaccess-outer-wrap">
			<h5 id="instant-ide-manager-htaccess-title">
				<?php _e( 'Instant IDE <code>.htaccess</code> File -', 'instant-ide-manager' ); ?>
				<a id="instant-ide-manager-htaccess-use-last-save"><?php _e( '(Use Last Saved Version)', 'instant-ide-manager' ); ?></a>
				<img class="instant-ide-manager-ajax-save-spinner" src="<?php echo site_url() ?>/wp-admin/images/spinner-2x.gif" />
			</h5>
			<span id="instant-ide-manager-htaccess-close">X</span>
			<div id="instant-ide-manager-htaccess-wrap">
				<textarea wrap="off" id="instant-ide-manager-htaccess" name="iide[htaccess]" rows="20">
					<?php if ( file_exists( IIDEM_IIDE_DIR . '/.htaccess' ) ) { echo file_get_contents( IIDEM_IIDE_DIR . '/.htaccess' ); } ?>
				</textarea>
			</div>
			<div id="instant-ide-manager-htaccess-button-container">
				<input type="submit" name="Submit" value="<?php _e( 'Save Changes', 'instant-ide-manager' ); ?>" style="margin:0 !important; float:left !important;" class="button"/>
				<img class="instant-ide-manager-ajax-save-spinner" src="<?php echo site_url() ?>/wp-admin/images/spinner-2x.gif" />
				<span class="instant-ide-manager-saved"></span>
			</div>
			<div style="clear:both;"></div>
		</div>
	</div>
</form>
