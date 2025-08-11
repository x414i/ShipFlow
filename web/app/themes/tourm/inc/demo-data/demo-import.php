<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
    exit( );
}
/**
 * @Packge    : tourm
 * @version   : 1.0
 * @Author    : Themeholy
 * @Author URI: https://themeforest.net/user/themeholy
 */

// demo import file
function tourm_import_files() {

	$demoImg = '<img src="'. TOURM_DEMO_DIR_URI  .'screen-image.png" alt="'.esc_attr__('Demo Preview Imgae','tourm').'" />';

    return array(
        array(
            'import_file_name'             => esc_html__('Tourm Demo','tourm'),
            'local_import_file'            =>  TOURM_DEMO_DIR_PATH  . 'tourm-demo.xml',
            'local_import_widget_file'     =>  TOURM_DEMO_DIR_PATH  . 'tourm-widgets-demo.json',
            'local_import_redux'           => array(
                array(
                    'file_path'   =>  TOURM_DEMO_DIR_PATH . 'redux_options_demo.json',
                    'option_name' => 'tourm_opt',
                ),
            ),
            'import_notice' => $demoImg,
        ),
    );
}
add_filter( 'pt-ocdi/import_files', 'tourm_import_files' );

// demo import setup
function tourm_after_import_setup() {
	// Assign menus to their locations.

	$primary_menu  		= get_term_by( 'name', 'Primary Menu', 'nav_menu' );
	$header_top_menu  		= get_term_by( 'name', 'Header Top', 'nav_menu' );

	set_theme_mod( 'nav_menu_locations', array(
			'primary-menu'   	=> $primary_menu->term_id, 
			'header-top-menu'   	=> $header_top_menu->term_id, 
		)
	);

	// Assign front page and posts page (blog page).
	$front_page_id 	= get_page_by_title( 'Home Travel' );
	$blog_page_id  	= get_page_by_title( 'Blog' );

	update_option( 'show_on_front', 'page' );
	update_option( 'page_on_front', $front_page_id->ID );
	update_option( 'page_for_posts', $blog_page_id->ID );




	// Elementor setting update
	update_option( 'elementor_global_image_lightbox', 'yes' );
	update_option( 'elementor_disable_color_schemes', 'yes' );
	update_option( 'elementor_disable_typography_schemes', 'yes' );
	update_option( 'elementor_container_width', 1344 );
	update_option( 'elementor_container_padding', 0 );
	update_option( 'elementor_space_between_widgets', 0 );
	update_option( 'elementor_experiment-e_font_icon_svg', 'inactive' );

	// Element kit
	$kit_page_id = get_page_by_title( 'Default Kit', OBJECT, 'elementor_library' );
	update_option( 'elementor_active_kit', $kit_page_id->ID );

    
}
add_action( 'pt-ocdi/after_import', 'tourm_after_import_setup' );


//disable the branding notice after successful demo import
add_filter( 'pt-ocdi/disable_pt_branding', '__return_true' );

//change the location, title and other parameters of the plugin page
function tourm_import_plugin_page_setup( $default_settings ) {
	$default_settings['parent_slug'] = 'themes.php';
	$default_settings['page_title']  = esc_html__( 'Tourm Demo Import' , 'tourm' );
	$default_settings['menu_title']  = esc_html__( 'Import Demo Data' , 'tourm' );
	$default_settings['capability']  = 'import';
	$default_settings['menu_slug']   = 'tourm-demo-import';

	return $default_settings;
}
add_filter( 'pt-ocdi/plugin_page_setup', 'tourm_import_plugin_page_setup' );

// Enqueue scripts
function tourm_demo_import_custom_scripts(){
	if( isset( $_GET['page'] ) && $_GET['page'] == 'tourm-demo-import' ){
		// style
		wp_enqueue_style( 'tourm-demo-import', TOURM_DEMO_DIR_URI.'css/tourm.demo.import.css', array(), '1.0', false );
	}
}
add_action( 'admin_enqueue_scripts', 'tourm_demo_import_custom_scripts' );