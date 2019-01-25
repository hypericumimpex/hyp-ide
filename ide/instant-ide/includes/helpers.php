<?php
/**
 * This file houses the general helper functions used
 * throughout the Instant IDE plugin.
 *
 * @package Instant IDE
 */
 
/**
 * Build Active File Editor drop-down list.
 *
 * @since 1.0.0
 * @return Active File Editor array.
 */
function instant_ide_active_file_editor_array() {
	
	$instant_ide_active_file_editor_array = array(
		'Ace Editor' => 'ace',
		'Monaco Editor' => 'monaco',
	);
	
	return $instant_ide_active_file_editor_array;
	
}

/**
 * Build Ace Editor Themes drop-down list.
 *
 * @since 1.0.0
 * @return Ace Editor Themes array.
 */
function instant_ide_monaco_editor_themes_array() {
	
	$instant_ide_monaco_editor_themes_array = array(
		'VS Light' => 'vs',
		'VS Dark' => 'vs-dark',
		'VS Dark (HC)' => 'hc-black',
		'Tomorrow Night iIDE' => 'tomorrow-night-iide',
	);
	
	return $instant_ide_monaco_editor_themes_array;
	
}

/**
 * Build Ace Editor Themes drop-down list.
 *
 * @since 1.0.0
 * @return Ace Editor Themes array.
 */
function instant_ide_ace_editor_themes_array() {
	
	$instant_ide_ace_editor_themes_array = array(
		'Ambiance' => 'ambiance',
		'Chaos' => 'chaos',
		'Chrome' => 'chrome',
		'Clouds' => 'clouds',
		'Clouds Midnight' => 'clouds_midnight',
		'Cobalt' => 'cobalt',
		'Crimson Editor' => 'crimson_editor',
		'Dawn' => 'dawn',
		'Dreamweaver' => 'dreamweaver',
		'Eclipse' => 'eclipse',
		'GitHub' => 'github',
		'Gob' => 'gob',
		'GruvBox' => 'gruvbox',
		'Idle Fingers' => 'idle_fingers',
		'iPlastic' => 'iplastic',
		'Katzenmilch' => 'katzenmilch',
		'KR Theme' => 'kr_theme',
		'Kurior' => 'kurior',
		'Merbivore' => 'merbivore',
		'Merbivore Soft' => 'merbivore_soft',
		'Mono Industrial' => 'mono_industrial',
		'Monokai' => 'monokai',
		'Pastel On Dark' => 'pastel_on_dark',
		'Solarized Dark' => 'solarized_dark',
		'Solarized Light' => 'solarized_light',
		'SQL Server' => 'sql_server',
		'Terminal' => 'terminal',
		'Textmate' => 'textmate',
		'Tomorrow' => 'tomorrow',
		'Tomorrow Night' => 'tomorrow_night',
		'Tomorrow Night Blue' => 'tomorrow_night_blue',
		'Tomorrow Night Bright' => 'tomorrow_night_bright',
		'Tomorrow Night Eighties' => 'tomorrow_night_eighties',
		'Twilight' => 'twilight',
		'Vibrant Ink' => 'vibrant_ink',
		'Xcode' => 'xcode',
	);
	
	return $instant_ide_ace_editor_themes_array;
	
}

/**
 * Build the Instant IDE select menu options.
 *
 * @since 1.0.0
 */
function instant_ide_build_select_menu_options( $options_array = array(), $selected = '' ) {
	
	foreach( $options_array as $key => $value ) {
		
		$option = '<option value="' . $value . '"';
			
		if ( $value == $selected )
			$option .= ' selected="selected"';

		$option .= '>' . $key . '</option>';
		
		echo $option;
		
	}
	
}

/**
 * Return proper size/unit info (used for image size info).
 *
 * @since 1.0.0
 * @return proper size/unit info.
 */
function instant_ide_format_size_units( $bytes ) {
	
	if ( $bytes >= 1073741824 )
		$bytes = number_format( $bytes / 1073741824, 2 ) . ' GB';
	elseif ( $bytes >= 1048576 )
		$bytes = number_format( $bytes / 1048576, 2 ) . ' MB';
	elseif ( $bytes >= 1024 )
		$bytes = number_format( $bytes / 1024, 2 ) . ' KB';
	elseif ( $bytes > 1 )
		$bytes = $bytes . ' bytes';
	elseif ( $bytes == 1 )
		$bytes = $bytes . ' byte';
	else
		$bytes = '0 bytes';
	
	return $bytes;
	
}

/**
 * Rebuild the multi-image file upload array to be
 * better suited for feeding into the image upload script.
 *
 * @since 1.0.0
 * @return a more usable image upload file array.
 */
function instant_ide_rearray_multi_image_upload( $file_post ) {

	$file_array = array();
	$file_count = count( $file_post['name'] );
	$file_keys = array_keys( $file_post );
	
	for ( $i=0; $i<$file_count; $i++ ) {
		
		foreach ( $file_keys as $key ) {
			
			$file_array[$i][$key] = $file_post[$key][$i];
			
		}
		
	}
	
	return $file_array;
	
}

/**
 * Return either the Parent Theme or Active/Child Theme folder name.
 *
 * @since 1.0.0
 * @return specified theme folder name.
 */
function instant_ide_get_platform_folder_name() {
	
	$platform_dir_explode = explode( '/', PLATFORM_DIR_DEV_PATH );
	$platform_folder_name = array_pop( $platform_dir_explode );
	
	return $platform_folder_name;
	
}

/**
 * Check if directory exists and try and create it if it does not.
 *
 * @since 1.0.0
 * @return true or false based on the findings of the function.
 */
function instant_ide_dir_check( $dir, $check_only = false ) {
	
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
 * Scan a specified directory and return the names of the directories inside it.
 *
 * @since 1.0.0
 * @return the names of directories inside a specified directory.
 */
function instant_ide_get_dir_names( $dir ) {
	
	if ( ! is_dir( $dir ) )
		return;
	
	$directories = scandir( $dir );
	$directory_array = array();
	
	foreach( $directories as $directory ) {
		
	    if ( $directory === '.' or $directory === '..' )
	    	continue;
	
	    if ( is_dir( $dir . '/' . $directory ) )
			$directory_array[] = $directory;
			
	}
	
	return $directory_array;
	
}

/**
 * Count the number of files inside a specified directory.
 *
 * @since 1.0.0
 */
function instant_ide_count_files( $source ) {
	
	if ( is_dir( $source ) ) {

		$files = scandir( $source );
		$filecount = 0;
		foreach( $files as $file ) {
			
			if ( substr( $file, 0, 1 ) != '.' && ! is_dir( $source . '/' . $file ) )
				$filecount++;
			
		}
		
		return $filecount;
		
	} else {
		
		return false;
		
	}
	
}

/**
 * Get the hashed console file name.
 *
 * @since 1.0.0
 * @return the hashed console file name.
 */
function instant_ide_get_console_file( $source ) {
	
	if ( is_dir( $source ) ) {

		$files = scandir( $source );
		$console_file = '';
		foreach( $files as $file ) {
			
			if ( substr( $file, 0, 8 ) == 'console-' )
				$console_file = $file;
			
		}
		
		return $console_file;
		
	} else {
		
		return false;
		
	}
	
}

/**
 * Recursively copy all files and folders from one location to another.
 *
 * @since 1.0.0
 */
function instant_ide_copy_dir( $source, $destination ) {
	
	if ( is_dir( $source ) ) {
		
		if ( ! is_dir( $destination ) )
			@mkdir( $destination, 0755, true );

		$handle = opendir( $source );
		while( false !== ( $readdirectory = readdir( $handle ) ) ) {
			
			if ( $readdirectory == '.' || $readdirectory == '..' )
				continue;

			$pathdir = $source . '/' . $readdirectory; 
			if ( is_dir( $pathdir ) ) {
				
				instant_ide_copy_dir( $pathdir, $destination . '/' . $readdirectory );
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
function instant_ide_delete_dir( $dir ) {
	
	if ( ! is_dir( $dir ) )
		return;
	
	$handle = opendir( $dir );
	while( false !== ( $file = readdir( $handle ) ) ) {
		
		if ( is_dir( $dir . '/' . $file ) ) {
			
			if ( ( $file != '.' ) && ( $file != '..' ) )
				instant_ide_delete_dir( $dir . '/' . $file );

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
function instant_ide_cleanup_dir( $dir ) {
	
	instant_ide_delete_dir( $dir );
	instant_ide_dir_check( $dir );
	
}

/**
 * Delete a specified directory and all contents within it and then
 * add the root folder back in, including a blank index.html file.
 *
 * @since 1.0.0
 */
function instant_ide_cleanup_html_dir( $dir ) {
	
	instant_ide_delete_dir( $dir );
	instant_ide_dir_check( $dir );
	instant_ide_write_file( $dir . 'index.html', '<!-- Empty File -->' );
	
}

/**
 * Return the entire line of text where a specified string exists.
 *
 * @since 1.0.0
 * @return entire line of text where specified string exists.
 */
function instant_ide_get_line_of_text( $file_contents, $string ) {
	
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
function instant_ide_replace_line_of_text( $content, $old_line, $new_line ) {

	$new_content = str_replace( $old_line, $new_line, $content );
	
	return $new_content;
	
}

function instant_ide_get_string_between( $string, $start, $end ) {
	
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
function instant_ide_sanatize_string( $string = '', $underscore = false ) {
	
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
 * Get the IP address of the current visitor.
 *
 * @since 1.0.0
 *
 */
function instant_ide_get_ip() {

	if ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
		
		$ip = $_SERVER['HTTP_CLIENT_IP'];
		
	} elseif ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
		
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		
	} else {
		
		$ip = $_SERVER['REMOTE_ADDR'];
		if ( $ip == '::1' )
			$ip = '127.0.1.6';
		
	}
	
	return $ip;

}

/**
 * Write to a file or create it if it does not exist.
 *
 * @since 1.0.0
 */
function instant_ide_write_file( $path, $code, $stripslashes = true ) {
	
	$handle = @fopen( $path, 'w+' );
	
	if ( false == $stripslashes )
		@fwrite( $handle, $code );
	else
		@fwrite( $handle, stripslashes( $code ) );
		
	@fclose( $handle );
	
}
