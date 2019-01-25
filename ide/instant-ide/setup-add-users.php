<?php

// Prevent direct access.
if ( ! defined( 'IIDE_DIR' ) ) exit;

// Process the setup form.
if ( isset( $_POST['addUserBtn'], $_POST['token'] ) ) {

    if ( instant_ide_validate_token( $_POST['token'] ) ) {
            
        //process the form
        //initialize an array to store any error message from the form
        $form_errors = array();

        //Form validation
        $required_fields = array( 'username', 'password' );

        //call the function to check empty field and merge the return data into form_error array
        $form_errors = array_merge( $form_errors, instant_ide_check_empty_fields( $required_fields ) );
        
        //call the function to check for matching passwords and merge the return data into form_error array
        $form_errors = array_merge( $form_errors, instant_ide_check_password_mismatch() );

        //Fields that requires checking for minimum length
        $fields_to_check_length = array( 'username' => 4, 'password' => 8 );

        //call the function to check minimum required length and merge the return data into form_error array
        $form_errors = array_merge( $form_errors, instant_ide_check_min_length( $fields_to_check_length ) );

        //collect form data and store in variables
        $username = $_POST['username'];
        $password = $_POST['password'];
        
	    // Duplicate username check / merge the return data into form_error array
	    $form_errors = array_merge( $form_errors, instant_ide_check_duplicate_entries( $username, $iIDE_USERS ) );

        if ( empty( $form_errors ) ) {
            
            //hashing the password
            $hashed_password = password_hash( $password, PASSWORD_DEFAULT );

        	$users_content = '<?php' . "\n" . '$iIDE_USERS = array(' . "\n";
        	
        	foreach( $iIDE_USERS as $key => $value )
        	    $users_content .= "\t" . "'" . $key . "' => '" . $value . "'," . "\n";
        	
        	$users_content .= "\t" . "'" . $username . "' => '" . $hashed_password . "'," . "\n" . ");";
            
            instant_ide_write_file( IIDE_DIR . '/data/users.php', $users_content, $stripslashes = false );
            
            if ( ! isset( $_SESSION['iide_username'] ) ) {
                
                $result = '<script type="text/javascript">
                                swal({
                                title: \'Congratulations ' . $username . '!\',
                                text: \'Registration Completed Successfully!\',
                                type: \'success\',
                                timer: 3000,
                                showConfirmButton: false });
                                setTimeout(function() {
                                    window.location = \'' . IIDE_URL . '\';
                                }, 3000);
                            </script>';
                            
            } else {

                $result = '<script type="text/javascript">
                            swal({
                                title: \'Success!\',
                                text: \'New User Account Created!\',
                                type: \'success\'
                            })
                            .then((result) => {
                    			swal({
                    				title: \'Add More Users?\',
                    				text: \'Would you like to add another user?\',
                    				type: \'info\',
                    				showCancelButton: true,
                    				confirmButtonText: \'Yes\',
                    				cancelButtonText: \'No\'
                    			}).then((result) => {
                    				if (result.value) {
                    					window.location = \'' . IIDE_URL . '?enable_setup=true&add_users=true\';
                    				} else {
                    				    window.location = \'' . IIDE_URL . '\';
                    				}
                    			})
                            })
                            </script>';
                        
            }
            
        } else {
            
            if ( count( $form_errors ) == 1 )
                $result = instant_ide_flash_message( 'There was 1 error in the form:<br>' );
            else
                $result = instant_ide_flash_message( 'There were ' . count( $form_errors ) . ' errors in the form:<br>' );
            
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
    <?php if ( isset( $_SESSION['iide_username'] ) ) { ?>
	    <h3>Add An Instant IDE user account.</h3>
	<?php } else { ?>
	    <h3>Create your Instant IDE user account.</h3>
	<?php } ?>
	
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
	
	<form method="post" action="">
	    <p>
	    	<label for="username">Username<br>
	    		<input type="text" value="" name="username">
	    	</label><br><br>
	    	<label for="password">Password<br>
	    		<input type="password" value="" name="password">
	    	</label><br><br>
	    	<label for="password_confirm">Confirm Password<br>
	    		<input type="password" value="" name="password_confirm">
	    	</label><br><br>
	    	<input type="hidden" name="token" value="<?php echo instant_ide_token(); ?>">
	    	<input type="submit" name="addUserBtn" value="Register">
	    	<?php if ( isset( $_SESSION['iide_username'] ) ) { ?>
	    	    <span id="instant-ide-form-back-button" onClick="window.location = '<?php echo IIDE_URL; ?>';">Go Back</span>
	    	<?php } ?>
	    </p>
	</form>
</div>

<?php
include_once( IIDE_DIR . '/templates/footer.php' );
