<?php
/**
 * Start new or resume existing session.
 */
 
// Session lifetime of 30 days
ini_set( 'session.gc_maxlifetime', 2592000 );

// Enable session garbage collection
ini_set( 'session.gc_probability', 1 );
ini_set( 'session.gc_divisor', 100 );

// Set a custom session save path
session_save_path( IIDE_DIR . '/.sessions' );

$iIDE_COOKIE_PARAMS = session_get_cookie_params();
$iide_parsed_url = parse_url( IIDE_URL );
session_set_cookie_params( $iIDE_COOKIE_PARAMS['lifetime'], substr( __DIR__, strlen( $_SERVER['DOCUMENT_ROOT'] ) ) . '/', $iide_parsed_url['host'], $iIDE_COOKIE_PARAMS['secure'], $iIDE_COOKIE_PARAMS['httponly'] );
session_start();

if ( empty( $_SESSION['iide_ajax_token'] ) ) {

    if ( function_exists( 'mcrypt_create_iv' ) )
        $_SESSION['iide_ajax_token'] = bin2hex( mcrypt_create_iv( 32, MCRYPT_DEV_URANDOM ) );
    else
        $_SESSION['iide_ajax_token'] = bin2hex( openssl_random_pseudo_bytes( 32 ) );

}
