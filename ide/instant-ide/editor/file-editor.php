<?php
/*
 * Build the File Editor page.
 */

include_once( IIDE_DIR . '/templates/header.php' );
?>

	<div class="instant-ide-settings-wrap">
        <div id="instant-ide-file-editor-loading">
            <img width="200" height="200" src="<?php echo IIDE_URL; ?>assets/css/images/instant-ide-loading-logo.png">
        </div>
    	<div id="instant-ide-file-editor-loaded" style="display:none;">
            <div id="instant-ide-file-editor-top-border"></div>
            <?php instant_ide_file_editor_menu(); ?>
    		<div id="instant-ide-file-tree-editor-container">
    			<div id="instant-ide-file-tree-container-wrap">
	    			<i class="fa fa-cog instant-ide-file-tree-cog" aria-hidden="true"></i>
	    			<div id="instant-ide-file-tree-container">
				        <div id="instant-ide-file-tree-loaded">
				            <ul class="instant-ide-file-tree">
				                <?php $platform_name = substr( instant_ide_get_platform_folder_name(), strrpos( instant_ide_get_platform_folder_name(), '/' ) ); ?>
				                <li id="iideft-root-directory" class="iideft-directory" title="<?php echo $platform_name ?>">
				                    <i class="fa fa-caret-down iideft-directory-icon" aria-hidden="true"></i>
	                                <a href="#" class="iideft-directory-open"><?php echo $platform_name ?></a>
	                                <ul><?php echo instant_ide_file_tree( PLATFORM_DIR_DEV_PATH ); ?></ul>
				                </li>
				            </ul>
	                    </div>
	    			</div>
	    		</div>
    			<?php echo instant_ide_site_preview(); ?>
    			<div id="instant-ide-file-editor-tab-container"><i class="fa fa-cog instant-ide-file-editor-cog" aria-hidden="true"></i></div>
    			<div id="instant-ide-file-editor-container"></div>
    			<?php echo instant_ide_file_editor_console(); ?>
    		</div>
    		<?php echo instant_ide_file_editor_upload_form(); ?>
    		<?php echo instant_ide_file_editor_download_form(); ?>
    		<?php echo instant_ide_file_editor_image_view(); ?>
    		<?php echo instant_ide_file_editor_options(); ?>
    	</div>
	</div>

<?php
include_once( IIDE_DIR . '/templates/footer.php' );
