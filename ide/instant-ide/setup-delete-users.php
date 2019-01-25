<?php

// Prevent direct access.
if ( ! defined( 'IIDE_DIR' ) ) exit;

// Process the setup form.
if ( isset( $_POST['deleteUserBtn'], $_POST['token'] ) ) {
    
    if ( instant_ide_validate_token( $_POST['token'] ) ) {
    
        // Prevent logged in user from deleting their own account.
        if ( $_SESSION['iide_username'] != $_POST['username'] ) {

            // Check if user exists in the $iIDE_USERS array.
            if ( array_key_exists( $_POST['username'], $iIDE_USERS ) ) {
                
                foreach( $iIDE_USERS as $key => $value ) {
                    
                    if ( $key == $_POST['username'] )
                        unset( $iIDE_USERS[$key] );
                        
                    instant_ide_rebuild_data_array( 'users', $iIDE_USERS );
                    
                }

                $result = '<script type="text/javascript">
                            swal({
                                title: \'Success!\',
                                text: \'User Account Deleted!\',
                                type: \'success\'
                            })
                            .then((result) => {
                    			swal({
                    				title: \'Delete More Users?\',
                    				text: \'Would you like to delete another user?\',
                    				type: \'info\',
                    				showCancelButton: true,
                    				confirmButtonText: \'Yes\',
                    				cancelButtonText: \'No\'
                    			}).then((result) => {
                    				if (result.value) {
                    					window.location = \'' . IIDE_URL . '?enable_setup=true&delete_users=true\';
                    				} else {
                    				    window.location = \'' . IIDE_URL . '\';
                    				}
                    			})
                            })
                            </script>';
                
            } else {
                
                instant_ide_redirect_to( IIDE_URL . '?enable_setup=true&delete_users=true&iide_account_delete_username_not_exist' );
                
            }
            
        } else {
            
            instant_ide_redirect_to( IIDE_URL . '?enable_setup=true&delete_users=true&iide_account_delete_username_same' );
            
        }
        
    } else {
        
        //display error
        $result = '<script type="text/javascript">
                    swal(\'Form Validation Error\', \'Please try again...\', \'error\' );
                    </script>';
                      
    }

}

include_once( IIDE_DIR . '/templates/header-login.php' ); ?>

<div class="instant-ide-login-logo-container">
    <img width="200" height="200" src="<?php echo IIDE_URL; ?>assets/css/images/instant-ide-login-logo.png">
</div>

<div class="instant-ide-login-form-container instant-ide-setup-form-container">
	<h3>Delete Instant IDE user accounts.</h3>
	
	<?php if ( isset( $result ) && ! empty( $form_errors ) ) { ?>
		<div class="instant-ide-login-error-message">
			<?php echo $result . instant_ide_show_errors( $form_errors ); ?>
		</div>
	<?php } ?>
	
	<?php if ( isset( $result ) && empty( $form_errors ) ) { ?>
		<div class="instant-ide-login-successful-message">
			<?php echo $result; ?>
		</div>
	<?php } ?>
	
	<?php if ( isset( $_GET['iide_account_delete_username_not_exist'] ) ) { ?>
		<div class="instant-ide-login-error-message">
			Error: This user account does not exist!
		</div>
	<?php } elseif ( isset( $_GET['iide_account_delete_username_same'] ) ) { ?>
		<div class="instant-ide-login-error-message">
			Error: You cannot delete the currently logged-in user!
		</div>
	<?php } ?>
	
	<form method="post" action="">
	    <p>
            <label for="username">Username<br>
	    		<input type="text" value="" name="username">
	    	</label><br><br>
	    	<input type="hidden" name="token" value="<?php echo instant_ide_token(); ?>">
	    	<input type="submit" name="deleteUserBtn" value="Delete Account" onClick='return confirm("Are you sure your want to delete this Instant IDE User Account?")'>
	        <span id="instant-ide-form-back-button" onClick="window.location = '<?php echo IIDE_URL; ?>';">Go Back</span>
	    </p>
	</form>
</div>

<?php
include_once( IIDE_DIR . '/templates/footer.php' );
    