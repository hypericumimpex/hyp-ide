<?php
/**
 * This file houses the Cobalt Apps Admin Bar Menu code
 * that is shared by all flagship Cobalt Apps DEV tools.
 *
 * @package Instant IDE Manager
 */
 
if ( ! function_exists( 'cobalt_apps_admin_bar_menu' ) ) :
/**
 * Build the Cobalt Apps Admin Bar Menu.
 *
 * @since 1.0.0
 */
function cobalt_apps_admin_bar_menu() {
	
	add_action( 'admin_bar_menu', function() {
	    
	    if ( ! current_user_can( 'administrator' ) || is_admin() )
	        return;
		
		global $wp_admin_bar;
		
        $wp_admin_bar->add_node( array(
    		'id' => 'cobalt-apps-wp-admin-bar',
    		'title' => 'Cobalt Apps',
        ) );
        
        if ( defined( 'DYN_FONT_AWESOME_VERSION' ) ) {
        
            $wp_admin_bar->add_node( array(
            	'parent' => 'cobalt-apps-wp-admin-bar',
        		'id' => 'dynamik-wp-admin-bar',
        		'title' => 'Dynamik',
        		'href' => admin_url( 'admin.php?page=dynamik-settings' ),
            ) );
            
            $wp_admin_bar->add_node( array(
            	'parent' => 'dynamik-wp-admin-bar',
        		'id' => 'dynamik-wp-admin-bar-settings',
        		'title' => 'Settings',
        		'href' => admin_url( 'admin.php?page=dynamik-settings' ),
            ) );
            
            $wp_admin_bar->add_node( array(
            	'parent' => 'dynamik-wp-admin-bar',
        		'id' => 'dynamik-wp-admin-bar-design',
        		'title' => 'Design',
        		'href' => admin_url( 'admin.php?page=dynamik-design' ),
            ) );
            
            $wp_admin_bar->add_node( array(
            	'parent' => 'dynamik-wp-admin-bar',
        		'id' => 'dynamik-wp-admin-bar-design-preview',
        		'title' => 'Design Preview',
        		'href' => admin_url( 'admin.php?page=dynamik-design&iframe=active' ),
            ) );
            
            $wp_admin_bar->add_node( array(
            	'parent' => 'dynamik-wp-admin-bar',
        		'id' => 'dynamik-wp-admin-bar-custom',
        		'title' => 'Custom',
        		'href' => admin_url( 'admin.php?page=dynamik-custom' ),
            ) );
            
            $wp_admin_bar->add_node( array(
            	'parent' => 'dynamik-wp-admin-bar',
        		'id' => 'dynamik-wp-admin-bar-custom-preview',
        		'title' => 'Custom Preview',
        		'href' => admin_url( 'admin.php?page=dynamik-custom&iframe=active' ),
            ) );
        
        }
        
        if ( defined( 'EXTPRO_VERSION' ) ) {
            
            $wp_admin_bar->add_node( array(
            	'parent' => 'cobalt-apps-wp-admin-bar',
        		'id' => 'extender-pro-wp-admin-bar',
        		'title' => 'Extender Pro',
        		'href' => admin_url( 'admin.php?page=extender-pro-dashboard' ),
            ) );
            
            $wp_admin_bar->add_node( array(
            	'parent' => 'extender-pro-wp-admin-bar',
        		'id' => 'extender-pro-wp-admin-bar-settings',
        		'title' => 'Settings',
        		'href' => admin_url( 'admin.php?page=extender-pro-dashboard' ),
            ) );
            
            $wp_admin_bar->add_node( array(
            	'parent' => 'extender-pro-wp-admin-bar',
        		'id' => 'extender-pro-wp-admin-bar-custom',
        		'title' => 'Custom',
        		'href' => admin_url( 'admin.php?page=extender-pro-custom' ),
            ) );
            
            $wp_admin_bar->add_node( array(
            	'parent' => 'extender-pro-wp-admin-bar',
        		'id' => 'extender-pro-wp-admin-bar-custom-preview',
        		'title' => 'Custom Preview',
        		'href' => admin_url( 'admin.php?page=extender-pro-custom&iframe=active' ),
            ) );
            
            $wp_admin_bar->add_node( array(
            	'parent' => 'extender-pro-wp-admin-bar',
        		'id' => 'extender-pro-wp-admin-bar-image-manager',
        		'title' => 'Image Manager',
        		'href' => admin_url( 'admin.php?page=extender-pro-image-manager' ),
            ) );
            
            $wp_admin_bar->add_node( array(
            	'parent' => 'extender-pro-wp-admin-bar',
        		'id' => 'extender-pro-wp-admin-bar-import-export',
        		'title' => 'Import/Export',
        		'href' => admin_url( 'admin.php?page=extender-pro-import-export' ),
            ) );
        
        }
        
        if ( defined( 'FDEVKIT_VERSION' ) ) {
            
            $wp_admin_bar->add_node( array(
            	'parent' => 'cobalt-apps-wp-admin-bar',
        		'id' => 'freelancer-devkit-wp-admin-bar',
        		'title' => 'Freelancer DevKit',
        		'href' => admin_url( 'admin.php?page=freelancer-devkit-dashboard' ),
            ) );
            
            $wp_admin_bar->add_node( array(
            	'parent' => 'freelancer-devkit-wp-admin-bar',
        		'id' => 'freelancer-devkit-wp-admin-bar-settings',
        		'title' => 'Settings',
        		'href' => admin_url( 'admin.php?page=freelancer-devkit-dashboard' ),
            ) );
            
            if ( file_exists( get_stylesheet_directory() . '/devkit-init.php' ) ) {

                $wp_admin_bar->add_node( array(
                	'parent' => 'freelancer-devkit-wp-admin-bar',
            		'id' => 'freelancer-devkit-wp-admin-bar-design',
            		'title' => 'Design',
            		'href' => admin_url( 'admin.php?page=freelancer-devkit-design-options' ),
                ) );
                
                $wp_admin_bar->add_node( array(
                	'parent' => 'freelancer-devkit-wp-admin-bar',
            		'id' => 'freelancer-devkit-wp-admin-bar-design-preview',
            		'title' => 'Design Preview',
            		'href' => admin_url( 'admin.php?page=freelancer-devkit-design-options&iframe=expanded' ),
                ) );

                $wp_admin_bar->add_node( array(
                	'parent' => 'freelancer-devkit-wp-admin-bar',
            		'id' => 'freelancer-devkit-wp-admin-bar-custom',
            		'title' => 'Custom',
            		'href' => admin_url( 'admin.php?page=freelancer-devkit-custom-options' ),
                ) );
                
                $wp_admin_bar->add_node( array(
                	'parent' => 'freelancer-devkit-wp-admin-bar',
            		'id' => 'freelancer-devkit-wp-admin-bar-custom-preview',
            		'title' => 'Custom Preview',
            		'href' => admin_url( 'admin.php?page=freelancer-devkit-custom-options&iframe=expanded' ),
                ) );
                
                $wp_admin_bar->add_node( array(
                	'parent' => 'freelancer-devkit-wp-admin-bar',
            		'id' => 'freelancer-devkit-wp-admin-bar-image-manager',
            		'title' => 'Image Manager',
            		'href' => admin_url( 'admin.php?page=freelancer-devkit-image-manager' ),
                ) );
            
            }
            
            $wp_admin_bar->add_node( array(
            	'parent' => 'freelancer-devkit-wp-admin-bar',
        		'id' => 'freelancer-devkit-wp-admin-bar-export',
        		'title' => 'Theme Creator',
        		'href' => admin_url( 'admin.php?page=freelancer-devkit-export' ),
            ) );
        
        }
        
        if ( defined( 'GDEVKIT_VERSION' ) ) {
            
            $wp_admin_bar->add_node( array(
            	'parent' => 'cobalt-apps-wp-admin-bar',
        		'id' => 'genesis-devkit-wp-admin-bar',
        		'title' => 'Genesis DevKit',
        		'href' => admin_url( 'admin.php?page=genesis-devkit-dashboard' ),
            ) );
            
            $wp_admin_bar->add_node( array(
            	'parent' => 'genesis-devkit-wp-admin-bar',
        		'id' => 'genesis-devkit-wp-admin-bar-settings',
        		'title' => 'Settings',
        		'href' => admin_url( 'admin.php?page=genesis-devkit-dashboard' ),
            ) );
            
            if ( file_exists( get_stylesheet_directory() . '/devkit-init.php' ) ) {

                $wp_admin_bar->add_node( array(
                	'parent' => 'genesis-devkit-wp-admin-bar',
            		'id' => 'genesis-devkit-wp-admin-bar-design',
            		'title' => 'Design',
            		'href' => admin_url( 'admin.php?page=genesis-devkit-design-options' ),
                ) );
                
                $wp_admin_bar->add_node( array(
                	'parent' => 'genesis-devkit-wp-admin-bar',
            		'id' => 'genesis-devkit-wp-admin-bar-design-preview',
            		'title' => 'Design Preview',
            		'href' => admin_url( 'admin.php?page=genesis-devkit-design-options&iframe=expanded' ),
                ) );

                $wp_admin_bar->add_node( array(
                	'parent' => 'genesis-devkit-wp-admin-bar',
            		'id' => 'genesis-devkit-wp-admin-bar-custom',
            		'title' => 'Custom',
            		'href' => admin_url( 'admin.php?page=genesis-devkit-custom-options' ),
                ) );
                
                $wp_admin_bar->add_node( array(
                	'parent' => 'genesis-devkit-wp-admin-bar',
            		'id' => 'genesis-devkit-wp-admin-bar-custom-preview',
            		'title' => 'Custom Preview',
            		'href' => admin_url( 'admin.php?page=genesis-devkit-custom-options&iframe=expanded' ),
                ) );
                
                $wp_admin_bar->add_node( array(
                	'parent' => 'genesis-devkit-wp-admin-bar',
            		'id' => 'genesis-devkit-wp-admin-bar-image-manager',
            		'title' => 'Image Manager',
            		'href' => admin_url( 'admin.php?page=genesis-devkit-image-manager' ),
                ) );
            
            }
            
            $wp_admin_bar->add_node( array(
            	'parent' => 'genesis-devkit-wp-admin-bar',
        		'id' => 'genesis-devkit-wp-admin-bar-export',
        		'title' => 'Theme Creator',
        		'href' => admin_url( 'admin.php?page=genesis-devkit-export' ),
            ) );
        
        }
        
        if ( defined( 'THMRPRO_VERSION' ) ) {
            
            $wp_admin_bar->add_node( array(
            	'parent' => 'cobalt-apps-wp-admin-bar',
        		'id' => 'themer-pro-wp-admin-bar',
        		'title' => 'Themer Pro',
        		'href' => admin_url( 'admin.php?page=themer-pro-dashboard' ),
            ) );
            
            $wp_admin_bar->add_node( array(
            	'parent' => 'themer-pro-wp-admin-bar',
        		'id' => 'themer-pro-wp-admin-bar-settings',
        		'title' => 'Settings',
        		'href' => admin_url( 'admin.php?page=themer-pro-dashboard' ),
            ) );
            
            if ( is_child_theme() ) {
                
                if ( themer_pro_get_settings( 'enable_parent_theme_editor' ) ) {
            
                    $wp_admin_bar->add_node( array(
                    	'parent' => 'themer-pro-wp-admin-bar',
                		'id' => 'themer-pro-wp-admin-bar-parent-editor',
                		'title' => 'Parent Editor',
                		'href' => admin_url( 'admin.php?page=themer-pro-parent-editor' ),
                    ) );
                
                }
                
                if ( themer_pro_get_settings( 'enable_child_theme_editor' ) ) {
                
                    $wp_admin_bar->add_node( array(
                    	'parent' => 'themer-pro-wp-admin-bar',
                		'id' => 'themer-pro-wp-admin-bar-child-editor',
                		'title' => 'Child Editor',
                		'href' => admin_url( 'admin.php?page=themer-pro-child-editor' ),
                    ) );
                    
                    $wp_admin_bar->add_node( array(
                    	'parent' => 'themer-pro-wp-admin-bar',
                		'id' => 'themer-pro-wp-admin-bar-child-editor-full',
                		'title' => 'Child Editor Full',
                		'href' => admin_url( 'admin.php?page=themer-pro-child-editor&activefile=functions-php&subdir&fullscreen=1' ),
                    ) );
                
                }
                
                if ( themer_pro_get_settings( 'enable_child_image_manager' ) ) {
                
                    $wp_admin_bar->add_node( array(
                    	'parent' => 'themer-pro-wp-admin-bar',
                		'id' => 'themer-pro-wp-admin-bar-image-manager',
                		'title' => 'Image Manager',
                		'href' => admin_url( 'admin.php?page=themer-pro-image-manager' ),
                    ) );
                
                }
            
            }
            
            $wp_admin_bar->add_node( array(
            	'parent' => 'themer-pro-wp-admin-bar',
        		'id' => 'themer-pro-wp-admin-bar-export',
        		'title' => 'Theme Creator',
        		'href' => admin_url( 'admin.php?page=themer-pro-export' ),
            ) );
        
        }
	    
	    if ( defined( 'IIDE_CURRENT_VERSION' ) ) {

            $wp_admin_bar->add_node( array(
            	'parent' => 'cobalt-apps-wp-admin-bar',
        		'id' => 'instant-ide-wp-admin-bar',
        		'title' => 'Instant IDE',
        		'href' => admin_url( 'admin.php?page=instant-ide-manager-dashboard' ),
            ) );
            
            $wp_admin_bar->add_node( array(
            	'parent' => 'instant-ide-wp-admin-bar',
        		'id' => 'instant-ide-wp-admin-bar-settings',
        		'title' => 'Settings',
        		'href' => admin_url( 'admin.php?page=instant-ide-manager-dashboard' ),
            ) );
            
            $wp_admin_bar->add_node( array(
            	'parent' => 'instant-ide-wp-admin-bar',
        		'id' => 'instant-ide-wp-admin-bar-iide',
        		'title' => 'Instant IDE',
        		'href' => get_home_url() . '/' . IIDEM_IIDE_DIR_NAME . '/',
            ) );
    	    
	        
	        if ( IIDE_CURRENT_VERSION !== 'Not Installed' ) {
	            
                $wp_admin_bar->add_node( array(
                	'parent' => 'instant-ide-wp-admin-bar',
            		'id' => 'instant-ide-wp-admin-bar-iide-preview',
            		'title' => 'Instant IDE Preview',
            		'href' => get_home_url() . '/' . IIDEM_IIDE_DIR_NAME . '/?sitePreview=true',
                ) );
	            
	        }
	        
	    }
	    
        if ( defined( 'FREELANCER_PARENT_THEME_VERSION' ) ) {
            
            $wp_admin_bar->add_node( array(
            	'parent' => 'cobalt-apps-wp-admin-bar',
        		'id' => 'freelancer-wp-admin-bar',
        		'title' => 'Freelancer',
        		'href' => admin_url( 'admin.php?page=freelancer-settings' ),
            ) );
        
        }
	    
	}, 999 );
	
	add_action( 'wp_enqueue_scripts', function() {

	    if ( is_admin_bar_showing() )
			wp_enqueue_style( 'cobalt-apps-icons', CAABM_URL . '/lib/css/icons.css', array(), '1.0.0' );
		
	});
	
}

if ( ! defined( 'DISABLE_CA_ADMIN_BAR' ) )
    cobalt_apps_admin_bar_menu();
    
endif; // End of cobalt_apps_admin_bar_menu.
