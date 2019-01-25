<?php

// Prevent direct access.
if ( ! defined( 'IIDE_DIR' ) ) exit;

if ( file_exists( IIDE_DIR . '/data/logins.php' ) )
    require_once( IIDE_DIR . '/data/logins.php' );
    
if ( file_exists( IIDE_DIR . '/data/lockouts.php' ) )
    require_once( IIDE_DIR . '/data/lockouts.php' );

if ( isset( $_POST['loginBtn'], $_POST['token'] ) ) {
    
    //validate the token
    if ( instant_ide_validate_token( $_POST['token'] ) ) {
        
        //process the form
        //array to hold errors
        $form_errors = array();

        //validate
        $required_fields = array( 'username', 'password' );
        $form_errors = array_merge( $form_errors, instant_ide_check_empty_fields( $required_fields ) );

        if ( empty( $form_errors ) ) {
            
            $locked_out = false;

            // Check if IP exists in the $iIDE_LOCKOUTS array.
            if ( in_array( instant_ide_get_ip(), $iIDE_LOCKOUTS ) ) {
                
                foreach( $iIDE_LOCKOUTS as $key => $value ) {
                    
                    if ( $value == instant_ide_get_ip() )
                        $lockout_time = strtotime( $key );
                    
                }
                
                $time_check = strtotime( '-15 minutes' );
                
                if ( $lockout_time >= $time_check ) {
                    
                    $locked_out = true;
                    
                } else {
                    
                    foreach( $iIDE_LOCKOUTS as $key => $value ) {
                        
                        if ( $value == instant_ide_get_ip() )
                            unset( $iIDE_LOCKOUTS[$key] );
                        
                    }
                    
                    instant_ide_rebuild_data_array( 'lockouts', $iIDE_LOCKOUTS );
                    
                }
                
            }
            
            if ( false === $locked_out ) {
                
                //collect form data
                $user = $_POST['username'];
                $password = $_POST['password'];
    
                isset( $_POST['remember'] ) ? $remember = $_POST['remember'] : $remember = '';
    
                // Check if user exists in the $iIDE_USERS array.
                if ( array_key_exists( $user, $iIDE_USERS ) ) {
                    
                    if ( password_verify( $password, $iIDE_USERS[$user] ) ) {
                        
                        $result = instant_ide_prep_login( $iIDE_USERS[$user], $user, $remember );
                        
                    } else {
                        
                        // Log failed login attempt.
                        $failed_login = array( date( 'Y-m-d H:i:s' ) => instant_ide_get_ip() );
                        $iIDE_FAILED_LOGINS = array_merge( $iIDE_FAILED_LOGINS, $failed_login );
                        instant_ide_rebuild_data_array( 'logins', $iIDE_FAILED_LOGINS );
                        
                        $login_failures = 0;
                        
                        // Check if IP exists in the $iIDE_FAILED_LOGINS array.
                        if ( in_array( instant_ide_get_ip(), $iIDE_FAILED_LOGINS ) ) {
                            
                           foreach( $iIDE_FAILED_LOGINS as $key => $value ) {

                                if ( $value == instant_ide_get_ip() )
                                    $login_failure_time = strtotime( $key );
                                    
                                $time_check = strtotime( '-15 minutes' );
                                
                                if ( $login_failure_time >= $time_check ) {
                                    
                                    $login_failures++;
                                    
                                } else {
                                    
                                    unset( $iIDE_FAILED_LOGINS[$key] );
                                    instant_ide_rebuild_data_array( 'logins', $iIDE_FAILED_LOGINS );
                                    
                                }
                                
                            }
                            
                        }
                        
                        if ( $login_failures > 5 ) {
                            
                            // Log lockout.
                            $lockout = array( date( 'Y-m-d H:i:s' ) => instant_ide_get_ip() );
                            $iIDE_LOCKOUTS = array_merge( $iIDE_LOCKOUTS, $lockout );
                            instant_ide_rebuild_data_array( 'lockouts', $iIDE_LOCKOUTS );
                            
                            $result = '<script type="text/javascript">
                            swal(\'Error\', \'You have entered an invalid password. You have also exceeded 5 failed login attempts in less than 15 minutes. Therefore you are locked out for the next 15 minutes.\', \'error\');
                            </script>';
                            
                        } else {
                            
                            $result = '<script type="text/javascript">
                            swal(\'Error\', \'You have entered an invalid password.\', \'error\');
                            </script>';
                            
                        }
                        
                    }
                    
                } else {
                    
                    // Log failed login attempt.
                    $failed_login = array( date( 'Y-m-d H:i:s' ) => instant_ide_get_ip() );
                    $iIDE_FAILED_LOGINS = array_merge( $iIDE_FAILED_LOGINS, $failed_login );
                    instant_ide_rebuild_data_array( 'logins', $iIDE_FAILED_LOGINS );
                    
                    $login_failures = 0;
                    
                    // Check if IP exists in the $iIDE_FAILED_LOGINS array.
                    if ( in_array( instant_ide_get_ip(), $iIDE_FAILED_LOGINS ) ) {
                        
                       foreach( $iIDE_FAILED_LOGINS as $key => $value ) {

                            if ( $value == instant_ide_get_ip() )
                                $login_failure_time = strtotime( $key );
                                
                            $time_check = strtotime( '-15 minutes' );
                            
                            if ( $login_failure_time >= $time_check ) {
                                
                                $login_failures++;
                                
                            } else {
                                
                                unset( $iIDE_FAILED_LOGINS[$key] );
                                instant_ide_rebuild_data_array( 'logins', $iIDE_FAILED_LOGINS );
                                
                            }
                            
                        }
                        
                    }
                    
                    if ( $login_failures > 5 ) {
                        
                        // Log lockout.
                        $lockout = array( date( 'Y-m-d H:i:s' ) => instant_ide_get_ip() );
                        $iIDE_LOCKOUTS = array_merge( $iIDE_LOCKOUTS, $lockout );
                        instant_ide_rebuild_data_array( 'lockouts', $iIDE_LOCKOUTS );
                        
                        $result = '<script type="text/javascript">
                        swal(\'Error\', \'You have entered an invalid username. You have also exceeded 5 failed login attempts in less than 15 minutes. Therefore you are locked out for the next 15 minutes.\', \'error\');
                        </script>';
                        
                    } else {
                        
                        $result = '<script type="text/javascript">
                        swal(\'Error\', \'You have entered an invalid username.\', \'error\');
                        </script>';
                        
                    }
                    
                }
                
            } else {
                
                $result = '<script type="text/javascript">
                swal(\'Error\', \'You are currently locked out due to too many failed login attempts! Try again in about 15 minutes.\', \'error\');
                </script>';
                
            }
            
        } else {
            
            if ( count( $form_errors ) == 1 )
                $result = instant_ide_flash_message( 'There was one error in the form.' );
            else
                $result = instant_ide_flash_message( 'There were ' . count( $form_errors ) . ' error in the form.' );
            
        }
        
    } else {
        
        //throw an error
        $result = '<script type="text/javascript">
        swal(\'Error\', \'This request originates from an unknown source.\', \'error\');
        </script>';
                  
    }

}

include_once( IIDE_DIR . '/templates/header-login.php' ); ?>

<div class="instant-ide-login-logo-container">
    <img width="200" height="200" src="<?php echo IIDE_URL; ?>assets/css/images/instant-ide-login-logo.png">
</div>

<div class="instant-ide-login-form-container">
    
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
	    	<label for="remember">
	    		<input type="checkbox" value="yes" name="remember"> Remember Me
	    	</label>
	    	<input type="hidden" name="token" value="<?php if ( function_exists( 'instant_ide_token' ) ) echo instant_ide_token(); ?>">
	    	<input type="submit" name="loginBtn" value="Log In">
	    </p>
	</form>
</div>

<?php
include_once( IIDE_DIR . '/templates/footer.php' );
