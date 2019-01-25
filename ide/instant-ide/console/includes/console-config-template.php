<?php
/**
 * Instant IDE web console configuration file.
 */
 
/**
 * Disable login (i.e.. don't ask for credentials). *NOT SECURE*
 * Only use this on private servers (e.g.. local dev servers, etc..).
 */
define( 'IIDE_NL_CON_PASS', 'no_login_con_pass' );
$NO_LOGIN = hash( 'md5', IIDE_NL_CON_PASS );

/**
 * Create single-user login credentials to securely access the console.
 */
define( 'IIDE_CON_USER', 'your_username' );
define( 'IIDE_CON_PASS', 'your_password' );

/**
 * Create multi-user login credentials to securely access the console.
 * e.g.. $ACCOUNTS = array('user1' => 'password1', 'user2' => 'password2');
 */
$ACCOUNTS = array();

/**
 * Set a password hash algorithm (or leave blank for no hash).
 * If set then the above $PASSWORD value must be appropriately hashed.
 * e.g.. $PASSWORD_HASH_ALGORITHM = 'sha256';
 * e.g.. $PASSWORD_HASH_ALGORITHM = 'md5';
 */
$PASSWORD_HASH_ALGORITHM = 'sha256';

// Home directory (multi-user mode supported)
// Example: $HOME_DIRECTORY = '/tmp';
//          $HOME_DIRECTORY = array('user1' => '/home/user1', 'user2' => '/home/user2');

/**
 * Set the home directory for the console (multi-user mode supported).
 * NOTE: This value should always begin with ../../ as that sets the dir to root.
 * Root = $HOME_DIRECTORY = '../../';
 * Single-user mode example: $HOME_DIRECTORY = '../../wp-content';
 * Multi-user mode example: $HOME_DIRECTORY = array('user1' => '/home/user1', 'user2' => '/home/user2');
 */
$DEV_PATH = IIDE_DEV_PATH == '' ? '/' : IIDE_DEV_PATH;
$HOME_DIRECTORY = '../..' . $DEV_PATH;
