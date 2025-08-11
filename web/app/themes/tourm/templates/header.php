<?php
/**
 * @Packge     : Tourm
 * @Version    : 1.0
 * @Author     : Themeholy
 * @Author URI : https://themeforest.net/user/themeholy
 *
 */

    // Block direct access
    if( ! defined( 'ABSPATH' ) ){
        exit();
    }

    if( class_exists( 'ReduxFramework' ) && defined('ELEMENTOR_VERSION') ) {
        if( is_page() || is_page_template('template-builder.php') ) {
            $tourm_post_id = get_the_ID();

            // Get the page settings manager
            $tourm_page_settings_manager = \Elementor\Core\Settings\Manager::get_settings_managers( 'page' );

            // Get the settings model for current post
            $tourm_page_settings_model = $tourm_page_settings_manager->get_model( $tourm_post_id );

            // Retrieve the color we added before
            $tourm_header_style = $tourm_page_settings_model->get_settings( 'tourm_header_style' );
            $tourm_header_builder_option = $tourm_page_settings_model->get_settings( 'tourm_header_builder_option' );

            if( $tourm_header_style == 'header_builder'  ) {

                if( !empty( $tourm_header_builder_option ) ) {
                    $tourmheader = get_post( $tourm_header_builder_option );
                    echo '<header class="header">';
                        echo \Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $tourmheader->ID );
                    echo '</header>';
                }
            } else {
                // global options
                $tourm_header_builder_trigger = tourm_opt('tourm_header_options');
                if( $tourm_header_builder_trigger == '2' ) {
                    echo '<header>';
                    $tourm_global_header_select = get_post( tourm_opt( 'tourm_header_select_options' ) );
                    $header_post = get_post( $tourm_global_header_select );
                    echo \Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $header_post->ID );
                    echo '</header>';
                } else {
                    // wordpress Header
                    tourm_global_header_option();
                }
            }
        } else {
            $tourm_header_options = tourm_opt('tourm_header_options');
            if( $tourm_header_options == '1' ) {
                tourm_global_header_option();
            } else {
                $tourm_header_select_options = tourm_opt('tourm_header_select_options');
                $tourmheader = get_post( $tourm_header_select_options );
                echo '<header class="header">';
                    echo \Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $tourmheader->ID );
                echo '</header>';
            }
        }
    } else {
        tourm_global_header_option();
    }