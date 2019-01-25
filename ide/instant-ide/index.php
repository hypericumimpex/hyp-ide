<?php
/*
 * Build the main index file.
 */
 
// Include necessary pre-init files.
if ( file_exists( __DIR__ . '/dev-path.php' ) )
    require_once( __DIR__ . '/dev-path.php' );
else
    define( 'IIDE_DEV_PATH', '' );
    
require_once( __DIR__ . '/iide-constants.php' );
require_once( IIDE_DIR . '/session.php' );
require_once( IIDE_DIR . '/includes/helpers.php' );
require_once( IIDE_DIR . '/includes/login-tools.php' );

if ( file_exists( IIDE_DIR . '/data/users.php' ) )
    require_once( IIDE_DIR . '/data/users.php' );

// Conditionally setup and run the program.
if ( ! isset( $_SESSION['iide_username'] ) || ( isset( $_SESSION['iide_username'] ) && isset( $_GET['enable_setup'] ) ) ) {
    
    if ( ! isset( $iIDE_USERS ) )
        require_once( IIDE_DIR . '/setup.php' );
    elseif ( empty( $iIDE_USERS ) || ( isset( $_SESSION['iide_username'] ) && isset( $_GET['add_users'] ) ) )
        require_once( IIDE_DIR . '/setup-add-users.php' );
    elseif ( isset( $_SESSION['iide_username'] ) && isset( $_GET['delete_users'] ) )
        require_once( IIDE_DIR . '/setup-delete-users.php' );
    else
        require_once( IIDE_DIR . '/login.php' );
    
} elseif ( isset( $_SESSION['iide_username'] ) && isset( $iIDE_USERS ) ) {
    
    instant_ide_is_cookie_valid( $iIDE_USERS );
    
    if ( file_exists( IIDE_DIR . '/active-editor.php' ) )
        require_once( IIDE_DIR . '/active-editor.php' );
    
    require_once( IIDE_DIR . '/console/includes/console-creator.php' );
    require_once( IIDE_DIR . '/editor/file-editor-functions.php' );
    require_once( IIDE_DIR . '/editor/file-editor.php' );
    
} else {
    
    instant_ide_signout();
    
}
