<?php
/*
 * Build the header template file.
 */
?>

<!doctype html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <meta name="iide-ajax-token" content="<?php echo $_SESSION['iide_ajax_token'] ?>">
    
    <title>Instant IDE - <?php echo PLATFORM_URL ?></title>
    
    <link rel="shortcut icon" href="<?php echo IIDE_URL; ?>assets/css/images/favicon.ico" type="image/x-icon" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="<?php echo IIDE_URL; ?>assets/css/style.css?ver=<?php echo IIDE_VERSION; ?>">
    <link rel="stylesheet" href="<?php echo IIDE_URL; ?>assets/js/contextMenu/jquery.contextMenu.min.css?ver=<?php echo IIDE_VERSION; ?>">
    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script type="text/javascript">
        var iide_active_editor = '<?php echo IIDE_ACTIVE_EDITOR; ?>';
        var iide_dev_path = '<?php echo IIDE_DEV_PATH; ?>';
        var platform_url = '<?php echo PLATFORM_URL; ?>';
        var platform_url_dev_path = '<?php echo PLATFORM_URL_DEV_PATH; ?>';
        var iide_url = '<?php echo IIDE_URL; ?>';
    </script>
    <script type="text/javascript" src="<?php echo IIDE_URL; ?>assets/js/file-editor.min.js?ver=<?php echo IIDE_VERSION; ?>"></script>
    <script type="text/javascript" src="<?php echo IIDE_URL; ?>assets/js/contextMenu/jquery.contextMenu.min.js"></script>
    <?php if ( IIDE_ACTIVE_EDITOR == 'ace' ) { ?>
    <script type="text/javascript" src="<?php echo IIDE_URL; ?>assets/js/ace/ace.js?ver=<?php echo IIDE_VERSION; ?>"></script>
    <script type="text/javascript" src="<?php echo IIDE_URL; ?>assets/js/ace/ext-language_tools.js?ver=<?php echo IIDE_VERSION; ?>"></script>
    <?php } ?>
    <script type="text/javascript" src="<?php echo IIDE_URL; ?>assets/js/sweetalert2.min.js?ver=<?php echo IIDE_VERSION; ?>"></script>
    <?php
    if ( ! empty( $_GET['sitePreview'] ) ) {
    	?><script type="text/javascript">
			jQuery(document).ready(function($) {
				$('#sub-menu-site-preview-open').click();
			});
    	</script><?php
    }
    ?>
</head>

<body class="instant-ide-loading instant-ide-<?php echo IIDE_ACTIVE_EDITOR; ?>-editor-active instant-ide-theme-dark">
    
<?php
