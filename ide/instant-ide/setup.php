<?php
/**
 * Instant IDE initial setup file.
 */
 
// Prevent direct access.
if ( ! defined( 'IIDE_DIR' ) ) exit;

$htaccess_content = '
# deny all except those indicated here
#<Limit GET POST>
#	Order Allow,Deny
#	Allow from 12.345.67.890
#	Allow from 09.876.54.321
#</Limit>

# password protect iIDE directory
#AuthName "Admins Only"
#AuthUserFile /server/path/to/.htpasswd
#AuthGroupFile /dev/null
#AuthType basic
#Require valid-user

# force https
#RewriteEngine On
#RewriteCond %{HTTPS} off
#RewriteRule (.*) https://%{HTTP_HOST}%{REQUEST_URI}

# whitelist the iIDE directory
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteCond %{REQUEST_FILENAME} !/.*
</IfModule>

# disable indexing of directories
Options -Indexes';

instant_ide_write_file( IIDE_DIR . '/.htaccess', $htaccess_content, $stripslashes = false );

$active_editor_content = "<?php
// Define the current active file editor.
define( 'IIDE_ACTIVE_EDITOR', 'monaco' );
";

instant_ide_write_file( IIDE_DIR . '/active-editor.php', $active_editor_content, $stripslashes = false );

$dev_path_content = "<?php
// Define the relative path for development.
define( 'IIDE_DEV_PATH', '' );
";

instant_ide_write_file( IIDE_DIR . '/dev-path.php', $dev_path_content, $stripslashes = false );

$console_config_content = htmlentities( file_get_contents( IIDE_DIR . '/console/includes/console-config-template.php' ) );

$old_nl_con_pass = instant_ide_get_line_of_text( $console_config_content, 'no_login_con_pass' );
$new_nl_con_pass = "define( 'IIDE_NL_CON_PASS', '" . uniqid() . "' );";
$console_config_content = instant_ide_replace_line_of_text( $console_config_content, $old_nl_con_pass[0], $new_nl_con_pass );

$old_con_user = instant_ide_get_line_of_text( $console_config_content, 'your_username' );
$new_con_user = "define( 'IIDE_CON_USER', '" . uniqid() . "' );";
$console_config_content = instant_ide_replace_line_of_text( $console_config_content, $old_con_user[0], $new_con_user );

$old_con_pass = instant_ide_get_line_of_text( $console_config_content, 'your_password' );
$new_con_pass = "define( 'IIDE_CON_PASS', '" . hash( 'sha256', uniqid() ) . "' );";
$console_config_content = instant_ide_replace_line_of_text( $console_config_content, $old_con_pass[0], $new_con_pass );

instant_ide_write_file( IIDE_DIR . '/console/includes/console-config.php', html_entity_decode( $console_config_content ) );

require_once( IIDE_DIR . '/console/includes/console-creator.php' );

$users_content = '<?php
$iIDE_USERS = array();
';

instant_ide_write_file( IIDE_DIR . '/data/users.php', $users_content, $stripslashes = false );

$logins_content = '<?php
$iIDE_FAILED_LOGINS = array();
';

instant_ide_write_file( IIDE_DIR . '/data/logins.php', $logins_content, $stripslashes = false );

$lockouts_content = '<?php
$iIDE_LOCKOUTS = array();
';

instant_ide_write_file( IIDE_DIR . '/data/lockouts.php', $lockouts_content, $stripslashes = false );

instant_ide_redirect_to( IIDE_URL );
