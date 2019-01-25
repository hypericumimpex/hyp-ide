<?php

/**
 * @param $required_fields_array, an array containing the list of all required fields
 * @return array, containing all errors
 */
function instant_ide_check_empty_fields( $required_fields_array ) {
    
    //initialize an array to store error messages
    $form_errors = array();
    //loop through the required fields array snd popular the form error array
    foreach( $required_fields_array as $name_of_field ) {
        
        if ( ! isset( $_POST[$name_of_field] ) || $_POST[$name_of_field] == NULL )
            $form_errors[] = $name_of_field . ' is a required field.';
            
    }
    return $form_errors;
    
}

/**
 * @param $password_fields_array, an array containing the password and confirm password
 * @return array, containing the password and confirm password
 */
function instant_ide_check_password_mismatch() {
    
    //initialize an array to store error messages
    $form_errors = array();

    if ( $_POST['password'] !== $_POST['password_confirm'] )
        $form_errors[] = 'Passwords do not match.';

    return $form_errors;
    
}

/**
 * @param $fields_to_check_length, an array containing the name of fields
 * for which we want to check min required length e.g array('username' => 4, 'email' => 12)
 * @return array, containing all errors
 */
function instant_ide_check_min_length( $fields_to_check_length ) {
    
    //initialize an array to store error messages
    $form_errors = array();
    foreach( $fields_to_check_length as $name_of_field => $minimum_length_required ) {
        
        if ( strlen( trim( $_POST[$name_of_field] ) ) < $minimum_length_required && $_POST[$name_of_field] != NULL )
            $form_errors[] = $name_of_field . ' is too short, must be ' . $minimum_length_required . ' characters long.';
    }
    return $form_errors;
    
}

/**
 * @param $form_errors_array, the array holding all
 * errors which we want to loop through
 * @return string, list containing all error messages
 */
function instant_ide_show_errors( $form_errors_array ) {
    
    $errors = '<p><ul>';

    //loop through error array and display all items in a list
    foreach( $form_errors_array as $the_error )
        $errors .= '<li>' . $the_error . '</li>';
        
    $errors .= '</ul></p>';
    return $errors;
    
}

/**
 * @param $message, message to display
 * @param string $passOrFail, test condition to determine message type
 * @return string, returns the message
 */
function instant_ide_flash_message( $message, $passOrFail = 'Fail' ) {
    
    if ( $passOrFail === 'Pass')
        $data = '<p>' . $message . '</p>';
    else
        $data = '<p>' . $message . '</p>';

    return $data;
    
}

/**
 * @param $page, redirect user to page specified
 */
function instant_ide_redirect_to( $page ) {
    
    header( 'Location: ' . $page );
    exit;
    
}

/**
 * @param $table, table that we want to search
 * @param $column_name, the column name
 * @param $value, the data collected from the form
 * @param $db, database object
 * @return bool, returns true if record exist else false
 */
function instant_ide_check_duplicate_entries( $username, $user_array ) {
    
    // Initialize an array to store error messages.
    $form_errors = array();
    
    if ( in_array( $username, $user_array ) )
		$form_errors[] = 'The username "' . $username . '" is already in use.';
		
	return $form_errors;
	
}

/**
 * @param $user_id
 */
function instant_ide_remember_me( $user_id ) {
    
    $encryptCookieData = base64_encode( 'UaQteh5i4y3dntstemYODEC' . $user_id );
    // Cookie set to expire in about 30 days
    setcookie( 'iide_remember_me', $encryptCookieData, time()+60*60*24*100, '/' );
    
}

/**
 * checked if the cookie used is same with the encrypted cookie
 * @param $db, database connection link
 * @return bool, true if the user cookie is valid
 */
function instant_ide_is_cookie_valid( $users ) {
    
    $isValid = false;
    if ( isset( $_COOKIE['iide_remember_me'] ) ) {

        /**
         * Decode cookies and extract user ID
         */
        $decryptCookieData = base64_decode( $_COOKIE['iide_remember_me'] );
        $user_id = explode( 'UaQteh5i4y3dntstemYODEC', $decryptCookieData );
        $userID = $user_id[1];

        /**
         * check if id retrieved from the cookie exist in the iIDE Users array
         * */
        if ( in_array( $userID, $users ) ) {

            /**
             * Create the user session variable
             */
            $_SESSION['iide_id'] = $userID;
            $_SESSION['iide_username'] = array_search( $userID, $users );
            $isValid = true;
            
        } else {
            
            /**
             * cookie ID is invalid destroy session and logout user
             */
            $isValid = false;
            instant_ide_signout();
            
        }
        
    }
    return $isValid;
    
}

/**
 * Kill all sessions, cookies, and regenrate session ID,
 * then redirect to the root page.
 */
function instant_ide_signout() {

    // Unset all of the session variables.
    $_SESSION = array();
    
    // If iIDE "Remember Me" cookie is set, unset it.
    if ( isset( $_COOKIE['iide_remember_me'] ) ) {
       
        unset( $_COOKIE['iide_remember_me'] );
        setcookie( 'iide_remember_me', null, -1, '/' );
        
    }
    
    // Delete the session cookie.
    if ( ini_get( 'session.use_cookies' ) ) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params['path'], $params['domain'],
            $params['secure'], $params['httponly']
        );
    }
    
    // Destroy the session.
    session_destroy();
    
    // Finally, cleanup the ./sessions directory.
    instant_ide_cleanup_html_dir( IIDE_DIR . '/.sessions/' );
    
    instant_ide_redirect_to( './' );
    
}

function instant_ide_token() {
    
    $randonToken = base64_encode( openssl_random_pseudo_bytes( 32 ) );

    return $_SESSION['iide_token'] = $randonToken;
    
}

function instant_ide_validate_token( $requestToken ) {
    
    if ( isset( $_SESSION['iide_token'] ) && $requestToken === $_SESSION['iide_token'] ) {
        
        unset( $_SESSION['iide_token'] );
        return true;
        
    }
    
    return false;
    
}

function instant_ide_rebuild_data_array( $type, $array ) {
    
    if ( $type == 'lockouts' )
        $variable = '$iIDE_LOCKOUTS';
    elseif ( $type == 'logins' )
        $variable = '$iIDE_FAILED_LOGINS';
    else
        $variable = '$iIDE_USERS';
    
	$array_content = '<?php' . "\n" . $variable . ' = array(' . "\n";
	
	foreach( $array as $key => $value )
	    $array_content .= "\t" . "'" . $key . "' => '" . $value . "'," . "\n";
    
    instant_ide_write_file( IIDE_DIR . '/data/' . $type . '.php', $array_content . ');' . "\n", $stripslashes = false );
    
}

function instant_ide_prep_login( $id, $username, $remember ) {
    
    $_SESSION['iide_id'] = $id;
    $_SESSION['iide_username'] = $username;
    $user_agent = ! empty( $_SERVER['HTTP_USER_AGENT'] ) ? $_SERVER['HTTP_USER_AGENT'] : uniqid();

    $fingerprint = md5( $_SERVER['REMOTE_ADDR'] . $user_agent );
    $_SESSION['iide_last_active'] = time();
    $_SESSION['iide_fingerprint'] = $fingerprint;

    if ( $remember === 'yes' )
        instant_ide_remember_me( $id );
    
    // Call sweet alert.
    return '<script type="text/javascript">
        swal({
        title: \'Hello ' . $username . '!\',
        text: \'You are being logged in...\',
        type: \'success\',
        timer: 3000,
        showConfirmButton: false });
        setTimeout(function() {
            window.location.href = \'./\';
        }, 3000);
    </script>';
    
}
