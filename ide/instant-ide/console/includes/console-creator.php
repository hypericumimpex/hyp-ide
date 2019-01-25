<?php

// Prevent direct access.
if ( ! defined( 'IIDE_DIR' ) ) exit;

instant_ide_console_creator();
/**
 * Create a uniquely named console file if one does not exist.
 *
 * @since 1.0.0
 */
function instant_ide_console_creator() {
    
    $htaccess_content = '# prevent directory indexing and obfuscate/deny file access
Options -Indexes
RewriteEngine On
RewriteCond %{HTTP_REFERER} !' . parse_url( PLATFORM_URL_DEV_PATH, PHP_URL_HOST ) . '/' . IIDE_DIR_NAME . '/ [NC]
RewriteRule ^.*$ - [R=404,L]';

    instant_ide_write_file( IIDE_DIR . '/console/.htaccess', $htaccess_content, $stripslashes = false );
    
    $console_file = instant_ide_get_console_file( IIDE_DIR . '/console' );
    if ( file_exists( IIDE_DIR . '/console/' . $console_file ) && $console_file != '' )
        return;
        
    $console_content = htmlentities( file_get_contents( IIDE_DIR . '/console/includes/console-template.php' ) );
    $console_content = instant_ide_get_string_between( $console_content, '/* Console Content Start */', '/* Console Content End */' );
    instant_ide_write_file( IIDE_DIR . '/console/console-' . hash( 'md5', time() ) . '.php', '<?php' . html_entity_decode( $console_content ), $stripslashes = false );

}
