<?php
/*
 * Build the footer template file.
 */
?>

    <?php if ( defined( 'IIDE_ACTIVE_EDITOR' ) && IIDE_ACTIVE_EDITOR == 'monaco' ) { ?>
    <script type="text/javascript" src="<?php echo IIDE_URL; ?>assets/js/vs/loader.js?ver=<?php echo IIDE_VERSION; ?>"></script>
    <?php } ?>
</body>
</html>
    
<?php
