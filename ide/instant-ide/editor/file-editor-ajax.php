<?php
/**
 * Builds the Parent/Child Theme File Editor Ajax functionality.
 *
 * @package Instant IDE
 */
 
// Include files.
require_once( dirname( __DIR__ ) . '/dev-path.php' );
require_once( dirname( __DIR__ ) . '/iide-constants.php' );
require_once( IIDE_DIR . '/session.php' );
require_once( IIDE_DIR . '/includes/helpers.php' );
require_once( IIDE_DIR . '/editor/file-editor-functions.php' );

if ( isset( $_SERVER['HTTP_IIDE_AJAX_TOKEN'] ) ) {

	if ( $_SERVER['HTTP_IIDE_AJAX_TOKEN'] !== $_SESSION['iide_ajax_token'] )
		exit( json_encode( ['error' => 'Wrong CSRF token.'] ) );
	
	if ( $_POST['action'] == 'instant_ide_file_tree_right_click_menu_action' )
		instant_ide_file_tree_right_click_menu_action();
	elseif ( $_POST['action'] == 'instant_ide_active_editor_write' )
		instant_ide_active_editor_write();
	elseif ( $_POST['action'] == 'instant_ide_one_click_install' )
		instant_ide_one_click_install();
	elseif ( $_POST['action'] == 'instant_ide_dev_path_write' )
		instant_ide_dev_path_write();
	elseif ( $_POST['action'] == 'instant_ide_file_tree_file_open' )
		instant_ide_file_tree_file_open();
	elseif ( $_POST['action'] == 'instant_ide_file_tree_folder_open' )
		instant_ide_file_tree_folder_open();
	elseif ( $_REQUEST['action'] == 'instant_ide_file_tree_upload_action' )
		instant_ide_file_tree_upload_action();
	elseif ( $_POST['save_action'] == 'file-editor-save' )
		instant_ide_file_editor_save();

} else {

	exit( json_encode( ['error' => 'No CSRF token.'] ) );

}

/**
 * Use ajax to manage file tree right-click events.
 *
 * @since 1.0.0
 */
function instant_ide_file_tree_right_click_menu_action() {
	
	if ( $_POST['context_menu_key'] == 'open_file' ) {
		
		instant_ide_cleanup_dir( IIDE_DIR . '/tmp/' );
		
		$dir = PLATFORM_DIR_DEV_PATH . '/' . $_POST['rel_path'];
		$zip_file = $dir . $_POST['zip_file_name'];
		
		// Get real path for our folder
		$rootPath = realpath( $dir );
		
		// Initialize archive object
		$zip = new ZipArchive();
		
		$res = $zip->open( $zip_file );
		
		if ( $res === TRUE ) {
			
			$zip->extractTo( IIDE_DIR . '/tmp/' );
			$zip->close();
			
			$scanned_dir = array_diff( scandir( IIDE_DIR . '/tmp/' ), array( '..', '.' ) );
			$new_file_name = $scanned_dir[2];
			$count = 1;
			
			if ( is_dir( IIDE_DIR . '/tmp/' . $new_file_name ) ) {
				
				$type = 'folder';
				
				while( file_exists( $dir . $new_file_name ) ) {
					
					$new_file_name = $new_file_name . '-' . $count;
					$count++;
					
				}
				
			} else {
				
				$type = 'file';
				$ext = strpos( $scanned_dir[2], '.' ) !== false ? '.' . substr( $scanned_dir[2], strrpos( $scanned_dir[2], '.' ) + 1 ) : '';
				$file_partial = rtrim( $new_file_name, $ext );
				
				while( file_exists( $dir . $new_file_name ) ) {
					
					$new_file_name = $file_partial . '-' . $count . $ext;
					$count++;
					
				}
				
			}
			
			rename( IIDE_DIR . '/tmp/' . $scanned_dir[2], $dir . $new_file_name );
			
			echo $type . '|' . $new_file_name;
				
			instant_ide_cleanup_dir( IIDE_DIR . '/tmp/' );
			
		} else {
			
			echo 'Unzip Error!';
			
		}
		
	} else if ( $_POST['context_menu_key'] == 'download_file' || $_POST['context_menu_key'] == 'download_folder' ) {
		
		instant_ide_cleanup_dir( IIDE_DIR . '/tmp/' );
		
		$dir = PLATFORM_DIR_DEV_PATH . '/' . $_POST['rel_path'];
		
		if ( substr( $_POST['file_name'], -4 ) != '.zip' ) {
		
			$zip_file = IIDE_DIR . '/tmp/' . $_POST['file_name'] . '.zip';
			$zip_file_url = IIDE_URL . '/tmp/' . $_POST['file_name'] . '.zip';
			
			// Get real path for our folder
			$rootPath = realpath( $dir );
			
			// Initialize archive object
			$zip = new ZipArchive();
			
			if ( $_POST['context_menu_key'] == 'download_folder' ) {
				
				$zip->open( $zip_file, ZipArchive::CREATE | ZipArchive::OVERWRITE );
				
				// Create recursive directory iterator
				/** @var SplFileInfo[] $files */
				$files = new RecursiveIteratorIterator(
				    new RecursiveDirectoryIterator( $rootPath ),
				    RecursiveIteratorIterator::LEAVES_ONLY
				);
				
				foreach( $files as $name => $file ) {
					
				    // Skip directories (they would be added automatically)
				    if ( ! $file->isDir() ) {
				    	
				        // Get real and relative path for current file
				        $filePath = $file->getRealPath();
				        $relativePath = substr( $filePath, strlen( $rootPath ) + 1 );
				
				        // Add current file to archive
				        $zip->addFile( $filePath, $relativePath );
				        
				    }
				}
				
			} else {
				
				$zip->open( $zip_file, ZipArchive::CREATE );
				$zip->addFile( $dir, $_POST['file_name'] );
				
			}
		
		} else {
			
			instant_ide_copy_dir( $dir, IIDE_DIR . '/tmp/' . $_POST['file_name'] );
			
		}
		
		echo 'File Download Is Ready!';
		
	} elseif ( $_POST['context_menu_key'] == 'rename_file' ) {
		
		$file_path = PLATFORM_DIR_DEV_PATH . '/' . $_POST['rel_path'];
		$actual_rel_path = substr( $file_path, 0, strrpos( $file_path, '/' ) );
		$file_to_rename = $_POST['old_name'];
		$old_file_ext = substr( $file_to_rename, strrpos( $file_to_rename, '.' ) + 1 );
		$new_file_ext = substr( $_POST['new_name'], strrpos( $_POST['new_name'], '.' ) + 1 );
		
		if ( $_POST['file_open'] == 'true' ) {
			
			echo 'Rename Error: Cannot Rename Open Files|' . $file_to_rename;
		
		} else {
		
			if ( ! file_exists( $actual_rel_path . '/' . $_POST['new_name'] ) ) {
				
				rename( $file_path, $actual_rel_path . '/' . $_POST['new_name'] );
				echo 'File Renamed';
				
			} else {
				
				echo 'Rename Error: File Exists|' . $file_to_rename;

			}
			
		}
		
	} elseif ( $_POST['context_menu_key'] == 'rename_image' ) {
		
		$file_path = PLATFORM_DIR_DEV_PATH . '/' . $_POST['rel_path'];
		$actual_rel_path = substr( $file_path, 0, strrpos( $file_path, '/' ) );
		$file_to_rename = $_POST['old_name'];
		$old_file_ext = substr( $file_to_rename, strrpos( $file_to_rename, '.' ) + 1 );
		$new_file_ext = substr( $_POST['new_name'], strrpos( $_POST['new_name'], '.' ) + 1 );
		
		if ( $_POST['file_open'] == 'true' ) {
			
			echo 'Rename Error: Cannot Rename Open Files|' . $file_to_rename;
		
		} elseif ( $old_file_ext == $new_file_ext ) {
		
			if ( ! file_exists( $actual_rel_path . '/' . $_POST['new_name'] ) ) {
				
				rename( $file_path, $actual_rel_path . '/' . $_POST['new_name'] );
				echo 'File Renamed';
				
			} else {
				
				echo 'Rename Error: File Exists|' . $file_to_rename;

			}
			
		} else {
			
			echo 'Rename Error: Cannot Change Image Extensions|' . $file_to_rename;
			
		}
		
	} elseif ( $_POST['context_menu_key'] == 'delete_file' ) {
		
		if ( is_array( $_POST['rel_path'] ) ) {
			
			$files_exist = true;
			foreach( $_POST['rel_path'] as $rel_path ) {
				
				if ( ! file_exists( PLATFORM_DIR_DEV_PATH . '/' . $rel_path ) )
					$files_exist = false;
				
			}
			
			if ( $files_exist ) {
				
				foreach( $_POST['rel_path'] as $rel_path )
					unlink( PLATFORM_DIR_DEV_PATH . '/' . $rel_path );
					
				echo 'Files Deleted';
				
			} else {
			
				echo 'Delete Error: One Or More Files Do Not Exist';
			
			}
			
		} else {
			
			$file_path = PLATFORM_DIR_DEV_PATH . '/' . $_POST['rel_path'];
			
			if ( file_exists( $file_path ) ) {
				
				unlink( $file_path );
				
				echo 'File Deleted';
				
			} else {
				
				echo 'Delete Error: File Does Not Exist';
				
			}
			
		}
		
	} elseif ( $_POST['context_menu_key'] == 'paste_file' || $_POST['context_menu_key'] == 'paste_folder' ) {
		
		$copy_path = PLATFORM_DIR_DEV_PATH . '/' . $_POST['copy_path'];
		$paste_path = PLATFORM_DIR_DEV_PATH . '/' . $_POST['paste_path'];
		$paste_action = $_POST['paste_action'];
		$paste_source = $_POST['paste_source'];
		$paste_name = $_POST['paste_name'];
		$action_name = $_POST['action_name'];
		$new_file_name = $paste_name;
		$count = 1;
		
		if ( $_POST['context_menu_key'] == 'paste_folder' )
			$actual_rel_path = str_replace( '//', '/', $paste_path . '/' );
		else
			$actual_rel_path = rtrim( $paste_path, $action_name );

		if ( $paste_source == 'file' ) {
			
			if ( $paste_action == 'copy' || $copy_path != ( $actual_rel_path . $new_file_name ) ) {
				
				$paste_ext = $_POST['paste_ext'];
				$file_partial = rtrim( $paste_name, '.' . $paste_ext );
				
				while( file_exists( $actual_rel_path . $new_file_name ) ) {
					
					$new_file_name = $file_partial . '-' . $count . '.' . $paste_ext;
					$count++;
					
				}
				
			}
			
			if ( copy( $copy_path, $actual_rel_path . $new_file_name ) )
				echo 'File Pasted|' . $new_file_name;
				
			if ( $paste_action == 'cut' && $copy_path != ( $actual_rel_path . $new_file_name ) )
				unlink( $copy_path );
			
		} elseif ( $paste_source == 'folder' ) {
			
			if ( $paste_action == 'copy' || $copy_path != ( $actual_rel_path . $new_file_name ) ) {
				
				while( true == instant_ide_dir_check( $actual_rel_path . $new_file_name, $check_only = true ) ) {
					
					$new_file_name = $paste_name . '-' . $count;
					$count++;
					
				}
				
			}
			
			instant_ide_copy_dir( $copy_path, $actual_rel_path . $new_file_name );
			
			if ( $paste_action == 'cut' && $copy_path == ( $actual_rel_path . $new_file_name ) )
				echo 'Paste Error|' . $new_file_name;
			else
				echo 'Folder Pasted|' . $new_file_name;
			
			if ( $paste_action == 'cut' && $copy_path != ( $actual_rel_path . $new_file_name ) )
				instant_ide_delete_dir( $copy_path );
			
		}
		
	} elseif ( $_POST['context_menu_key'] == 'duplicate_file' ) {
		
		$file_path = PLATFORM_DIR_DEV_PATH . '/' . $_POST['rel_path'];
		$file_name = $_POST['name'];
		$actual_rel_path = rtrim( $file_path, $file_name );
		$file_ext = $_POST['ext'];
		$file_partial = rtrim( $file_name, '.' . $file_ext );
		$new_file_name = $file_partial . '-1.' . $file_ext;
		$count = 1;
		
		while( file_exists( $actual_rel_path . $new_file_name ) ) {
			
			$new_file_name = $file_partial . '-' . $count . '.' . $file_ext;
			$count++;
			
		}
		
		if ( copy( $file_path, $actual_rel_path . $new_file_name ) )
			echo 'File Duplicate Created|' . $new_file_name;
		
	} elseif ( $_POST['context_menu_key'] == 'create_file' || $_POST['context_menu_key'] == 'folder_create_file' ) {
		
		$new_file_name = $_POST['file_name'];
			
		if ( $_POST['context_menu_key'] == 'create_file' )
			$new_file_path_extended = '';
		else
			$new_file_path_extended = $_POST['name'] . '/';
		
		$new_file_path = PLATFORM_DIR_DEV_PATH . '/' . $_POST['rel_path'] . $new_file_path_extended .  $new_file_name;
		
		if ( file_exists( $new_file_path ) ) {
			
			echo 'Error: File Exists|' . $new_file_name;
			
		} else {
			
			instant_ide_write_file( $new_file_path, 'New file...' );
			echo 'File Created|' . $new_file_name;
			
		}
		
	} elseif ( $_POST['context_menu_key'] == 'rename_folder' ) {
		
		$folder_to_rename = $_POST['old_name'];
		
		if ( $_POST['new_name'] != '' ) {
			
			$folder_path = PLATFORM_DIR_DEV_PATH . '/' . $_POST['rel_path'];
			$actual_rel_path = substr( $folder_path, 0, strrpos( $folder_path, '/' ) );

			if ( false == instant_ide_dir_check( $actual_rel_path . '/' . $_POST['new_name'], $check_only = true ) ) {
				
				rename( $folder_path, $actual_rel_path . '/' . $_POST['new_name'] );
				echo 'Folder Renamed';
				
			} else {
				
				echo 'Rename Error: Folder Exists|' . $folder_to_rename;

			}
			
		} else {
			
			echo 'Rename Error: Unsupported Folder Name|' . $folder_to_rename;
			
		}
		
	} elseif ( $_POST['context_menu_key'] == 'delete_folder' ) {
		
		$folder_path = PLATFORM_DIR_DEV_PATH . '/' . $_POST['rel_path'];
		
		if ( file_exists( $folder_path ) ) {
			
			instant_ide_delete_dir( $folder_path );
			
			echo 'Folder Deleted';
			
		} else {
			
			echo 'Delete Error: Folder Does Not Exist';
			
		}
		
	} elseif ( $_POST['context_menu_key'] == 'duplicate_folder' ) {
		
		$folder_path = PLATFORM_DIR_DEV_PATH . '/' . $_POST['rel_path'];
		$folder_name = $_POST['name'];
		$actual_rel_path = rtrim( $folder_path, $folder_name );
		$new_folder_name = $folder_name . '-1';
		$count = 1;
		
		while( true == instant_ide_dir_check( $actual_rel_path . $new_folder_name, $check_only = true ) ) {
			
			$new_folder_name = $folder_name . '-' . $count;
			$count++;
			
		}
		
		instant_ide_copy_dir( $folder_path, $actual_rel_path . $new_folder_name );
		echo 'Folder Duplicate Created|' . $new_folder_name;
		
	} elseif ( $_POST['context_menu_key'] == 'create_folder' || $_POST['context_menu_key'] == 'folder_create_folder' ) {

		$new_folder_name = $_POST['folder_name'];
		
		if ( $_POST['context_menu_key'] == 'create_folder' )
			$new_folder_path_extended = '';
		else
			$new_folder_path_extended = $_POST['name'] . '/';
		
		$new_folder_path = PLATFORM_DIR_DEV_PATH . '/' . $_POST['rel_path'] . $new_folder_path_extended .  $new_folder_name;
		
		if ( file_exists( $new_folder_path ) ) {
			
			echo 'Error: Folder Exists|' . $new_folder_name;
			
		} else {
			
			instant_ide_dir_check( $new_folder_path );
			echo 'Folder Created|' . $new_folder_name;
			
		}
		
	}
	
	exit();
	
}

/**
 * Use ajax to write the updated active file edtor constant.
 *
 * @since 1.0.0
 */
function instant_ide_active_editor_write() {

	$code = "<?php
// Define the current active file editor.
define( 'IIDE_ACTIVE_EDITOR', '" . $_POST['active_editor'] . "' );
";
	
	instant_ide_write_file( IIDE_DIR . '/active-editor.php', $code );
	
	echo 'Active Editor Constant Has Been Updated!';
	exit();
	
}

/**
 * Use ajax to perform a one-click install to the specified directory.
 *
 * @since 1.0.4
 */
function instant_ide_one_click_install() {
	
	$app = $_POST['context_menu_key'];
	
	if ( $app == 'install_wordpress' ) {
		
		$app_name = 'wordpress';
		$app_nicename = 'WordPress';
		file_put_contents( IIDE_DIR . '/tmp/wordpress.zip', fopen( 'https://wordpress.org/latest.zip', 'r' ) );
		
	} elseif ( $app == 'install_october' ) {
		
		$app_name = 'october';
		$app_nicename = 'October';
		file_put_contents( IIDE_DIR . '/tmp/october.zip', fopen( 'https://octobercms.com/download', 'r' ) );
		
	}
	
	if ( file_exists( IIDE_DIR . '/tmp/' . $app_name . '.zip' ) ) {
		
		// Initialize archive object
		$zip = new ZipArchive();
		
		$res = $zip->open( IIDE_DIR . '/tmp/' . $app_name . '.zip' );
		
		if ( $res === TRUE ) {
			
			$zip->extractTo( IIDE_DIR . '/tmp/' );
			$zip->close();
			
			if ( glob( IIDE_DIR . '/tmp/' . $app_name . '*' ) ) {
				
				$files = scandir( IIDE_DIR . '/tmp' );
				$app_file = '';
				foreach( $files as $file ) {
					
					if ( strpos( $file, '.zip' ) !== false )
						unlink( IIDE_DIR . '/tmp/' . $file );
					elseif ( strpos( $file, $app_name ) !== false )
						$app_file = $file;
					
				}
				
				$folder_name = $_POST['name'] != '' ? '/' . $_POST['name'] : '';
				
				if ( $app == 'install_october' )
					instant_ide_copy_dir( IIDE_DIR . '/tmp/install-master/' . $app_file, PLATFORM_DIR_DEV_PATH . '/' . $_POST['rel_path'] );
				else
					instant_ide_copy_dir( IIDE_DIR . '/tmp/' . $app_file, PLATFORM_DIR_DEV_PATH . '/' . $_POST['rel_path'] );

				
				echo $app_nicename . ' Install Successful!';
				
			} else {
				
				echo 'Install Error: ' . $app_nicename . ' ZIP File Could Not Be Unzipped';
				
			}
			
		} else {
			
			echo 'Install Error: ' . $app_nicename . ' ZIP File Could Not Be Unzipped';
			
		}
	
	} else {
		
		echo 'Install Error: ' . $app_nicename . ' Could Not Be Installed';
		
	}
	
	instant_ide_cleanup_dir( IIDE_DIR . '/tmp/' );
	
	exit();
	
}

/**
 * Use ajax to write the updated dev path to the appropriate file.
 *
 * @since 1.0.0
 */
function instant_ide_dev_path_write() {

	$code = "<?php
// Define the relative path for development.
define( 'IIDE_DEV_PATH', '" . $_POST['dev_path'] . "' );
";
	
	instant_ide_write_file( IIDE_DIR . '/dev-path.php', $code );
	
	echo 'Dev Path Has Been Updated!';
	exit();
	
}

/**
 * Use ajax to update a specific theme file based on the posted values.
 *
 * @since 1.0.0
 */
function instant_ide_file_tree_file_open() {

	$handle = file_get_contents( PLATFORM_DIR_DEV_PATH . '/' . $_POST['file_rel_path'] );
	
	echo $handle;
	exit();
	
}

/**
 * Use ajax to open a specific folder based on the posted values.
 *
 * @since 1.0.0
 */
function instant_ide_file_tree_folder_open() {

	echo instant_ide_file_tree( PLATFORM_DIR_DEV_PATH . '/' . $_POST['folder_rel_path'] );
	exit();
	
}

/**
 * Use ajax to upload files.
 *
 * @since 1.0.0
 */
function instant_ide_file_tree_upload_action() {

	if ( isset( $_FILES['uploads']['error'] ) ) {
		
		$upload_error = false;
		
		foreach( $_FILES['uploads']['error'] as $key => $error ) {
			
			if ( $error == UPLOAD_ERR_OK ) {

				if ( file_exists( PLATFORM_DIR_DEV_PATH . '/' . $_REQUEST['rel_path'] . '/' . $_FILES['uploads']['name'][$key] ) )
					unlink( PLATFORM_DIR_DEV_PATH . '/' . $_REQUEST['rel_path'] . '/' . $_FILES['uploads']['name'][$key] );
					
				move_uploaded_file( $_FILES['uploads']['tmp_name'][$key], PLATFORM_DIR_DEV_PATH . '/' . $_REQUEST['rel_path'] . '/' . $_FILES['uploads']['name'][$key] );
				
			} else {
				
				$upload_error = true;
				
			}
			
		}
		
		if ( $upload_error )
			echo 'File Upload Failed';
		else
			echo 'Files Uploaded!';
		
	} else {
		
		echo 'Upload Error: No Files Selected';
		
	}
	
	exit();
	
}

/**
 * Use ajax to update a specific theme file based on the posted values.
 *
 * @since 1.0.0
 */
function instant_ide_file_editor_save() {
	
	instant_ide_write_file( $path = PLATFORM_DIR_DEV_PATH . '/' . $_POST['file_rel_path'], $code = $_POST['iide']['file'], $stripslashes = false );
	
	echo 'File Updated';
	
	exit();
	
}
