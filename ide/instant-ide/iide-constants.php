<?php

/**
 * Build the base URL of this file.
 *
 * @since 1.0.0
 * @return the base URL of this file.
 */
function instant_ide_url() {
    
    if ( ! empty( $_SERVER['HTTP_X_FORWARDED_PROTO'] ) ) {

        $url = $_SERVER['HTTP_X_FORWARDED_PROTO'] . '://';

    } else {

        if ( ! empty( $_SERVER['HTTPS'] ) )
            $url = $_SERVER['HTTPS'] !== 'off' ? 'https://' : 'http://';
        else
            $url = $_SERVER['SERVER_PORT'] == 443 ? 'https://' : 'http://';

    }
        
    $url .= $_SERVER['SERVER_NAME'];
    $url .= $_SERVER['REQUEST_URI'];
    // Strip out query strings.
    $url = strtok( $url,'?' );
    
    return $url;
    
}

/*
 * This is only necessary for the Instant IDE Manager Plugin
 * which includes this file directly and therefore this
 * constant does not get defined, resulting in a wp_debug error.
 */
if ( ! defined( 'IIDE_DEV_PATH' ) )
	define( 'IIDE_DEV_PATH', '' );

// Define constants.
if ( ! defined( 'PLATFORM_DIR' ) )
    define( 'PLATFORM_DIR', dirname( __DIR__ ) );
    
if ( ! defined( 'PLATFORM_DIR_DEV_PATH' ) )
    define( 'PLATFORM_DIR_DEV_PATH', dirname( __DIR__ ) . IIDE_DEV_PATH );
    
if ( ! defined( 'PLATFORM_URL' ) )
    define( 'PLATFORM_URL', dirname( instant_ide_url() ) );
    
if ( ! defined( 'PLATFORM_URL_DEV_PATH' ) )
    define( 'PLATFORM_URL_DEV_PATH', dirname( instant_ide_url() ) . IIDE_DEV_PATH );
    
if ( ! defined( 'IIDE_DIR' ) )
    define( 'IIDE_DIR', __DIR__ );
    
if ( ! defined( 'IIDE_DIR_NAME' ) )
    define( 'IIDE_DIR_NAME', basename( IIDE_DIR ) );
    
if ( ! defined( 'IIDE_URL' ) )
    define( 'IIDE_URL', instant_ide_url() );
    
if ( ! defined( 'IIDE_VERSION' ) )
    define( 'IIDE_VERSION', '1.1.7' );
