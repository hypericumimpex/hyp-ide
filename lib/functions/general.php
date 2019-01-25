<?php
/**
 * This file houses the general helper functions used
 * throughout the Instant IDE Manager plugin.
 *
 * @package Instant IDE Manager
 */
 
/**
 * Get the Instant IDE Manager WP uploads path.
 *
 * @since 1.0.3
 * @return the Instant IDE Manager WP uploads path.
 */
function instant_ide_manager_get_uploads_path() {

    $uploads_dir = wp_upload_dir();
    $dir = $uploads_dir['basedir'] . '/instant-ide-manager';

	return apply_filters( 'instant_ide_manager_get_uploads_path', $dir );
	
}
 
/**
 * Find the root directory of Instant IDE, regardless of its name.
 *
 * @since 1.0.0
 * @return the root directory of Instant IDE.
 */
function instant_ide_manager_iide_root_finder() {
	
	$dirs = glob( ABSPATH . '*' , GLOB_ONLYDIR );
	$wp_dirs = array( 'wordpress', 'wp-admin', 'wp-content', 'wp-includes', );
	
	foreach( $dirs as $dir ) {
		
		if ( ! in_array( $dir, $wp_dirs ) ) {
			
			if ( file_exists( $dir . '/iide-constants.php' ) )
				return $dir;
			
		}
		
	}
	
	return false;
		
}

/**
 * Check if directory exists and try and create it if it does not.
 *
 * @since 1.0.0
 * @return true or false based on the findings of the function.
 */
function instant_ide_manager_dir_check( $dir, $check_only = false ) {
	
	if ( ! is_dir( $dir ) && $check_only == false ) {
		
		mkdir( $dir );
		@chmod( $dir, 0755 );
		
	}
	
	if ( is_dir( $dir ) )
		return true;
	else
		return false;
		
}

/**
 * Recursively copy all files and folders from one location to another.
 *
 * @since 1.0.0
 */
function instant_ide_manager_copy_dir( $source, $destination ) {
	
	if ( is_dir( $source ) ) {
		
		instant_ide_manager_dir_check( $destination );

		$handle = opendir( $source );
		while( false !== ( $readdirectory = readdir( $handle ) ) ) {
			
			if ( $readdirectory == '.' || $readdirectory == '..' )
				continue;

			$pathdir = $source . '/' . $readdirectory; 
			if ( is_dir( $pathdir ) ) {
				
				instant_ide_manager_copy_dir( $pathdir, $destination . '/' . $readdirectory );
				continue;
				
			}
			copy( $pathdir, $destination . '/' . $readdirectory );
			
		}
		closedir( $handle );
		
	} else {
		
		copy( $source, $destination );
		
	}
	
}

/**
 * Recursively delete specific folders.
 *
 * @since 1.0.0
 */
function instant_ide_manager_delete_dir( $dir ) {
	
	if ( ! is_dir( $dir ) )
		return;
	
	$handle = opendir( $dir );
	while( false !== ( $file = readdir( $handle ) ) ) {
		
		if ( is_dir( $dir . '/' . $file ) ) {
			
			if ( ( $file != '.' ) && ( $file != '..' ) )
				instant_ide_manager_delete_dir( $dir . '/' . $file );

		} else {
			
			unlink( $dir . '/' . $file );
			
		}
	}
	closedir( $handle );
	rmdir( $dir );
	
}

/**
 * Delete a specified directory and all contents within it
 * and then add the root folder back in.
 *
 * @since 1.0.0
 */
function instant_ide_manager_cleanup_dir( $dir ) {
	
	instant_ide_manager_delete_dir( $dir );
	instant_ide_manager_dir_check( $dir );
	
}

/**
 * Backup Instant IDE files before update.
 *
 * @since 1.0.0
 */
function instant_ide_manager_backup_files() {
	
	$iide_file_backup_path = IIDEM_IDE_DIR;
	
	$iide_file_paths = array(
		'.htaccess' => '',
		'active-editor.php' => '',
		'dev-path.php' => '',
		'console-config.php' => 'console/includes/',
		'lockouts.php' => 'data/',
		'logins.php' => 'data/',
		'users.php' => 'data/',
	);
	
	foreach( $iide_file_paths as $file => $path ) {
		
		if ( file_exists( IIDEM_IIDE_DIR . '/' . $path . $file ) )
			copy( IIDEM_IIDE_DIR . '/' . $path . $file, $iide_file_backup_path . '/' . $file );
		
	}
	
}

/**
 * Restore Instant IDE files after update.
 *
 * @since 1.0.0
 */
function instant_ide_manager_restore_files() {
	
	$iide_file_restore_path = IIDEM_IIDE_DIR;
	
	$iide_file_paths = array(
		'.htaccess' => '',
		'active-editor.php' => '',
		'dev-path.php' => '',
		'console-config.php' => 'console/includes/',
		'lockouts.php' => 'data/',
		'logins.php' => 'data/',
		'users.php' => 'data/',
	);
	
	foreach( $iide_file_paths as $file => $path ) {
		
		if ( file_exists( IIDEM_IDE_DIR . '/' . $file ) )
			rename( IIDEM_IDE_DIR . '/' . $file, $iide_file_restore_path . '/' . $path . $file );
		
	}
	
}

/**
 * Return the entire line of text where a specified string exists.
 *
 * @since 1.0.0
 * @return entire line of text where specified string exists.
 */
function instant_ide_manager_get_line_of_text( $file_contents, $string ) {
	
	$pattern = '/^.*\b' . $string . '\b.*$/m';
	$matches = array();
	preg_match( $pattern, $file_contents, $matches );
	
	return $matches;
	
}

/**
 * Return the contents of a string with specified line replaced.
 *
 * @since 1.0.0
 * @return the contents of a string with specified line replaced.
 */
function instant_ide_manager_replace_line_of_text( $content, $old_line, $new_line ) {

	$new_content = str_replace( $old_line, $new_line, $content );
	
	return $new_content;
	
}

function instant_ide_manager_get_string_between( $string, $start, $end ) {
	
	$string = ' ' . $string;
	$ini = strrpos( $string, $start );
	if ( $ini == 0 )
		return '';
		
	$ini += strlen( $start );
	$len = strrpos( $string, $end, $ini ) - $ini;
	
	return substr( $string, $ini, $len );
	
}

/**
 * Sanatize strings of text.
 *
 * @since 1.0.0
 */
function instant_ide_manager_sanatize_string( $string = '', $underscore = false ) {
	
    //lower case everything
    $string = strtolower( $string );
    //make alphaunermic
    $string = preg_replace( "/[^a-z0-9_\s-]/", "", $string );
    //Clean multiple dashes or whitespaces
    $string = preg_replace( "/[\s-]+/", " ", $string );
    if ( false != $underscore ) {
    	
	    // Convert whitespaces and dashes to underscore
	    $string = preg_replace( "/[\s-]/", "_", $string );
	    
    } else {
    	
	    // Convert whitespaces and underscore to dash
	    $string = preg_replace( "/[\s_]/", "-", $string );
	    
	}
    return $string;
    
}

/**
 * Write to a file or create it if it does not exist.
 *
 * @since 1.0.0
 *
 */
function instant_ide_manager_write_file( $path, $code, $stripslashes = true ) {
	
	$handle = @fopen( $path, 'w' );
	
	if ( false == $stripslashes )
		@fwrite( $handle, $code );
	else
		@fwrite( $handle, stripslashes( $code ) );
		
	@fclose( $handle );
	
}
