<?php
/*
 * Build the File Editor functions.
 */
 
/**
 * Build the file editor menu.
 *
 * @since 1.0.0
 */
function instant_ide_file_editor_menu() {

?>
    <div id="instant-ide-file-editor-menu">
        <ul>
            <li>
                <span class="menu-heading-text">
                    <img class="instant-ide-menu-logo" width="16" height="24" src="<?php echo IIDE_URL; ?>assets/css/images/instant-ide-menu-logo.png" />Instant IDE
                </span>
                <ul class="sub-menu">
                	<li id="sub-menu-add-users">Add Users</li>
                	<li id="sub-menu-delete-users">Delete Users</li>
                	<li id="sub-menu-visit-home">Visit Site Home</li>
                    <li id="sub-menu-logout">Logout</li>
                </ul>
            </li>
            <li>
                <span class="menu-heading-text">File</span>
                <ul class="sub-menu">
                	<li id="sub-menu-open">Open Selected File</li>
    	            <li id="sub-menu-save">Save (cmd/ctrl+s)</li>
                </ul>
            </li>
            <li>
                <span class="menu-heading-text">Console</span>
                <ul class="sub-menu">
    	            <li id="sub-menu-console-open">Open</li>
    	            <li id="sub-menu-console-close">Close</li>
    	            <li id="sub-menu-console-restart">Restart</li>
                </ul>
            </li>
            <li>
                <span class="menu-heading-text">Site Preview</span>
                <ul class="sub-menu">
    	            <li id="sub-menu-site-preview-open">Open</li>
    	            <li id="sub-menu-site-preview-close">Close</li>
    	            <li id="sub-menu-site-preview-restart">Restart</li>
                </ul>
            </li>
            <li>
                <span class="menu-heading-text menu-heading-options">Options</span>
            </li>
        </ul>
        <img class="instant-ide-ajax-save-spinner" src="<?php echo IIDE_URL; ?>assets/css/images/ajax-save-in-progress.gif" />
        <span class="instant-ide-saved"></span>
        <span class="instant-ide-version-display"><?php echo IIDE_VERSION; ?></span>
        <span class="instant-ide-site-preview-icons-container" style="display:none;">
        	<span id="instant-ide-site-preview-icons-url-view">Preview URL: <span id="instant-ide-site-preview-icons-url-view-url"></span></span>
        	<span id="instant-ide-site-preview-icons-refresh"><i class="fa fa-refresh" aria-hidden="true" title="Refresh"></i></span>
        	<span id="instant-ide-site-preview-icons-desktop"><i class="fa fa-desktop" aria-hidden="true" title="100x100"></i></span>
        	<span id="instant-ide-site-preview-icons-tablet"><i class="fa fa-tablet" aria-hidden="true" title="720x1080"></i></span>
        	<span id="instant-ide-site-preview-icons-mobile"><i class="fa fa-mobile" aria-hidden="true" title="320x480"></i></span>
        	<span id="instant-ide-site-preview-icons-popout"><i class="fa fa-external-link" aria-hidden="true" title="Open In New Window"></i></span>
        </span>
    </div>
<?php
	
}

/**
 * Build the file editor upload form overlay.
 *
 * @since 1.0.0
 */
function instant_ide_file_editor_upload_form() {

?>
    <div id="instant-ide-file-editor-upload-form-overlay" style="display:none;"></div>
    <div id="instant-ide-file-editor-upload-form-container" style="display:none;">
        <form id="instant-ide-file-editor-upload-form" action="/" method="POST">
            <i class="fa fa-times-circle" aria-hidden="true"></i>
            <div id="instant-ide-file-upload-wrap">
                <p>Select file to upload: <input type="file" id="instant-ide-file-upload" name="uploads[]" multiple=""/></p>
            </div>
            <button id="instant-ide-upload-button" type="submit">Upload</button>
            <img class="instant-ide-ajax-save-spinner" src="<?php echo IIDE_URL; ?>assets/css/images/ajax-save-in-progress.gif" />
            <div id="instant-ide-file-upload-progress"></div>
        </form>
    </div>
<?php
	
}

/**
 * Build the file editor download form overlay.
 *
 * @since 1.0.0
 */
function instant_ide_file_editor_download_form() {
    
    instant_ide_cleanup_dir( IIDE_DIR . '/tmp/' ); ?>
    <a id="instant-ide-file-editor-download-link" href="" style="display:none;"></a><?php
	
}

/**
 * Build the file editor image view overlay.
 *
 * @since 1.0.0
 */
function instant_ide_file_editor_image_view() {

?>
    <div id="instant-ide-file-editor-image-view-overlay" style="display:none;"></div>
    <div id="instant-ide-file-editor-image-view-container" style="display:none;">
        <div id="instant-ide-file-editor-image-view">
            <i class="fa fa-times-circle instant-ide-file-editor-image-view-close" aria-hidden="true"></i>
            <h3><code id="instant-ide-image-view-info-name"></code></h3>
			<p id="instant-ide-file-editor-image-view-info">
				<span>
				    Width: <code id="instant-ide-image-view-info-width"></code>
				    Height: <code id="instant-ide-image-view-info-height"></code>
				</span>
				<span>
				    File Size: <code id="instant-ide-image-view-info-size"></code>
				</span>
				<span>
				    <a id="instant-ide-image-view-info-link" href="" target="_blank"><i class="fa fa-link" aria-hidden="true"></i></a>
				</span>
			</p>
			<div id="instant-ide-image-file-preview">
				<img src="">
			</div>
        </div>
    </div>
<?php
	
}

/**
 * Build the file editor options overlay.
 *
 * @since 1.0.0
 */
function instant_ide_file_editor_options() {

?>
    <div id="instant-ide-file-editor-options-overlay" style="display:none;"></div>
    <div id="instant-ide-file-editor-options-container" style="display:none;">
        <div id="instant-ide-file-editor-options">
            <i class="fa fa-times-circle" aria-hidden="true"></i>
			<p id="instant-ide-file-editor-options-dev-path">
				<span><img class="instant-ide-menu-logo" width="16" height="24" src="<?php echo IIDE_URL; ?>assets/css/images/instant-ide-menu-logo.png" />DEV Path: </span>
                <input id="instant-ide-dev-path" type="text" name="dev_path"> <span>(e.g.. /wp-content/themes) blank = root</span>
			</p>
			<p id="instant-ide-file-editor-options-file-tree-width" class="instant-ide-col-two">
				<span><img class="instant-ide-menu-logo" width="16" height="24" src="<?php echo IIDE_URL; ?>assets/css/images/instant-ide-menu-logo.png" />File Tree Width: </span>
                <input type="radio" name="file_tree_width" value="sm"> <span>Sm</span>
                <input type="radio" name="file_tree_width" value="md"> <span>Md</span>
                <input type="radio" name="file_tree_width" value="lg"> <span>Lg</span>
			</p>
			<p id="instant-ide-file-editor-options-console-height" class="instant-ide-col-two">
				<span><img class="instant-ide-menu-logo" width="16" height="24" src="<?php echo IIDE_URL; ?>assets/css/images/instant-ide-menu-logo.png" />Console Height: </span>
                <input type="radio" name="console_height" value="sm"> <span>Sm</span>
                <input type="radio" name="console_height" value="md"> <span>Md</span>
                <input type="radio" name="console_height" value="lg"> <span>Lg</span>
			</p>
			<p id="instant-ide-file-editor-options-site-preview-width" class="instant-ide-col-two">
				<span><img class="instant-ide-menu-logo" width="16" height="24" src="<?php echo IIDE_URL; ?>assets/css/images/instant-ide-menu-logo.png" />Site Preview Width: </span>
                <input type="radio" name="site_preview_width" value="sm"> <span>Sm</span>
                <input type="radio" name="site_preview_width" value="md"> <span>Md</span>
                <input type="radio" name="site_preview_width" value="lg"> <span>Lg</span>
			</p>
			<p id="instant-ide-file-editor-options-iide-theme" class="instant-ide-col-two">
				<span><img class="instant-ide-menu-logo" width="16" height="24" src="<?php echo IIDE_URL; ?>assets/css/images/instant-ide-menu-logo.png" />Instant IDE Theme: </span>
                <input type="radio" name="iide_theme" value="light"> <span>Light</span>
                <input type="radio" name="iide_theme" value="dark"> <span>Dark</span>
			</p>
			<p id="instant-ide-file-editor-options-active-editor" class="instant-ide-col-two">
				<span><img class="instant-ide-menu-logo" width="16" height="24" src="<?php echo IIDE_URL; ?>assets/css/images/instant-ide-menu-logo.png" />Active Editor: </span>
				<select id="instant-ide-active-editor" class="instant-ide-settings-select-menu" name="instant-ide-active-editor" size="1">
					<?php instant_ide_build_select_menu_options( instant_ide_active_file_editor_array(), 'tomorrow-night-iide' ); ?>
				</select>
			</p>
			<?php if ( IIDE_ACTIVE_EDITOR == 'monaco' ) { ?>
				<p class="instant-ide-col-two">
					<span><img class="monaco-editor-options-icon" width="17" height="17" src="<?php echo IIDE_URL; ?>assets/css/images/vs-logo.png" />Editor Theme: </span>
					<select id="instant-ide-monaco-editor-theme" class="instant-ide-settings-select-menu" name="instant-ide-monaco-editor-theme" size="1">
						<?php instant_ide_build_select_menu_options( instant_ide_monaco_editor_themes_array(), 'tomorrow-night-iide' ); ?>
					</select>
				</p>
				<div id="instant-ide-monaco-editor-theme-preview">
					<img src="<?php echo IIDE_URL; ?>assets/css/images/monaco-themes/placeholder.png">
				</div>
			<?php } else  { ?>
				<p class="instant-ide-col-two">
					<span><img class="ace-editor-options-icon" width="26" height="17" src="<?php echo IIDE_URL; ?>assets/css/images/ace-editor-icon.png" />Editor Theme: </span>
					<select id="instant-ide-ace-editor-theme" class="instant-ide-settings-select-menu" name="instant-ide-ace-editor-theme" size="1">
						<?php instant_ide_build_select_menu_options( instant_ide_ace_editor_themes_array(), 'tomorrow-night-eighties' ); ?>
					</select>
				</p>
				<div id="instant-ide-ace-editor-theme-preview">
					<img src="<?php echo IIDE_URL; ?>assets/css/images/ace-themes/placeholder.png">
				</div>
			<?php } ?>
        </div>
    </div>
<?php
	
}
 
/**
 * Build a file tree based on a specified directory.
 *
 * @since 1.0.0
 * @return a file tree based on a specified directory.
 */
function instant_ide_file_tree( $directory ) {

	if ( substr( $directory, -1 ) == '/' )
		$directory = substr( $directory, 0, strlen( $directory ) - 1 );

	$code = instant_ide_file_tree_dir( $directory );
	
	return $code;
	
}

/**
 * Recursively list directories/files based on a specified directory.
 *
 * @since 1.0.0
 * @return a list directories/files based on a specified directory.
 */
function instant_ide_file_tree_dir( $directory ) {
	
	// Get and sort directories and files.
	if ( ! is_dir( $directory ) )
		return '<ul id="instant-ide-dev-path-error"><li><strong style="color:red;">Error:</strong> The current DEV Path is not a valid directory.</li></ul>';
		
	$file = scandir( $directory );
	natcasesort( $file );
	$files = $dirs = array();
	foreach( $file as $this_file ) {
		
		if ( is_dir( $directory . '/' . $this_file ) && $directory . '/' . $this_file != PLATFORM_DIR . '/' . IIDE_DIR_NAME )
			$dirs[] = $this_file;
		elseif ( ! is_dir( $directory . '/' . $this_file ) )
			$files[] = $this_file;
		
	}
	$file = array_merge( $dirs, $files );
	$file_tree = '';
	
	if ( count( $file ) > 2 ) { // Use 2 instead of 0 to account for . and .. "directories"
	
		$platform_name = substr( instant_ide_get_platform_folder_name(), strrpos( instant_ide_get_platform_folder_name(), '/' ) );

		foreach( $file as $this_file ) {
		    
		    if ( $this_file != '.' && $this_file != '..' ) {
		        
		        if ( substr( $this_file, 0, 1 ) == '.' )
		            $hidden_class = ' iideft-hidden';
		        else
		            $hidden_class = '';
				
				if ( is_dir( $directory . '/' . $this_file ) ) {
                    
                    $dir_unique_id = str_replace( array( '/', ' ', '.' ), '-', substr( $directory . '/' . $this_file, strlen( PLATFORM_DIR_DEV_PATH ) ) );
                    
					$file_tree .= '<li id="iideft-directory' . $dir_unique_id . '" class="iideft-directory' . $hidden_class . '" title="' . $this_file . '"><i class="fa fa-caret-right iideft-directory-icon" aria-hidden="true"></i><a href="#">' . htmlspecialchars( $this_file ) . '</a><ul style="display: none;"></ul></li>';
					
				} else {

                    $supported_image_ext_array = array( 'gif', 'jpg', 'jpeg', 'png', 'svg', 'swf', 'psd', 'bmp', 'tiff', 'ico' );
					$file_ext = substr( $this_file, strrpos( $this_file, '.' ) + 1 );
					$ext = 'ext-' . $file_ext;
						
					if ( in_array( $file_ext, $supported_image_ext_array ) )
						$file_edit_class = 'iideft-file-image ';
					elseif ( $file_ext == 'zip' )
						$file_edit_class = 'iideft-file-zip ';
					else
						$file_edit_class = 'iideft-file-edit ';

					$file_tree .= '<li class="iideft-file ' . $file_edit_class . strtolower( $ext ) . $hidden_class . '" title="' . $this_file . '"><a href=#>' . htmlspecialchars( $this_file ) . '</a></li>';
						
				}
				
			}
			
		}

	}
	
	return $file_tree;
	
}

/**
 * Build the file editor console iFrame.
 *
 * @since 1.0.0
 */
function instant_ide_file_editor_console() {
	
	// Include the console configuration file.
	if ( file_exists( IIDE_DIR . '/console/includes/console-config.php' ) )
		require_once( IIDE_DIR . '/console/includes/console-config.php' );
    
    $console_file = instant_ide_get_console_file( IIDE_DIR . '/console' );
?>
    <div id="instant-ide-file-editor-console-container">
        <script type="text/javascript">
            var console_iframe = '<iframe id="instant-ide-file-editor-console" src="<?php echo IIDE_URL; ?>console/<?php echo $console_file; ?>?<?php echo IIDE_NL_CON_PASS; ?>"></iframe>';
        </script>
    </div>
<?php
	
}

/**
 * Build the site preview iFrame.
 *
 * @since 1.0.0
 */
function instant_ide_site_preview() {
    
?>
    <div id="instant-ide-site-preview-container"></div>
<?php
	
}

