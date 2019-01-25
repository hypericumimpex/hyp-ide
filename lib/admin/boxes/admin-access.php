<?php
/**
 * Builds the Instant IDE Manager Admin Access admin content.
 *
 * @package Instant IDE Manager
 */
?>

<div id="instant-ide-manager-settings-nav-info-box" class="instant-ide-manager-optionbox-outer-1col instant-ide-manager-all-options instant-ide-manager-options-display">
	<div class="instant-ide-manager-optionbox-2col-left-wrap" style="width:100%;max-width:600px;">
		
		<div class="instant-ide-manager-optionbox-outer-2col">
			<div class="instant-ide-manager-optionbox-inner-2col">
				<h4 style="margin:0;border:0;"><?php _e( 'Pin Number Required For Admin Access', 'instant-ide-manager' ); ?></h4>
				<div class="bg-box">
					<div class="bg-box bg-box-licenses">
						
						<form action="/" id="instant-ide-manager-admin-access-check-form">
							<input type="hidden" name="action" value="instant_ide_manager_admin_access_check" />
							<input type="hidden" name="security" value="<?php echo wp_create_nonce( 'iide-ajax-nonce' ); ?>" />
							
							<div id="instant-ide-manager-admin-access-check-form-input-container">
								<?php _e( 'Please Enter Your Pin', 'instant-ide-manager' ); ?>
								<input id="instant_ide_manager_access_pin" name="iidem[access_pin]" type="password" class="regular-text" value="" style="width:100%; max-width:105px;"/>
								<a href="http://docs.cobaltapps.com/article/417-what-to-do-if-you-forget-your-admin-access-pin-number" target="_blank"><?php _e( 'Forgot?', 'instant-ide-manager' ); ?></a>
								<input type="submit" name="Submit" class="button" value="<?php _e( 'Submit', 'instant-ide-manager' ); ?>"/>
								<img class="instant-ide-manager-ajax-save-spinner" src="<?php echo site_url() ?>/wp-admin/images/spinner-2x.gif" />
								<span class="instant-ide-manager-saved"></span>
							</div>
						</form>
						
					</div>
				</div>
			</div>
		</div>
	
	</div>
</div>
