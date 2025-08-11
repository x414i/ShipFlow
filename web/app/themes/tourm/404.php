<?php
/**
 * @Packge     : Tourm
 * @Version    : 1.0
 * @Author     : Themeholy
 * @Author URI : https://themeforest.net/user/themeholy
 *
 */

    // Block direct access
    if( !defined( 'ABSPATH' ) ){
        exit();
    }

    if( class_exists( 'ReduxFramework' ) ) {
        $tourm404title     = tourm_opt( 'tourm_error_title' );
        $tourm404subtitle     = tourm_opt( 'tourm_error_subtitle' );
        $tourm404description  = tourm_opt( 'tourm_error_description' );
        $tourm404btntext      = tourm_opt( 'tourm_error_btn_text' );
    } else {
        $tourm404title     = __( 'Oops! Page Not Found', 'tourm' );
        $tourm404subtitle     = __( 'This page seems to have slipped through a time portal', 'tourm' );
        $tourm404description  = __( 'We appologize for any distruction to the space-time continuum. Feel free to journey back to our homepage', 'tourm' );
        $tourm404btntext      = __( 'Go Back Home', 'tourm');

    }

    // get header //
    get_header(); 
        echo '<section class="space bg-smoke">';
            echo '<div class="container">';
                echo '<div class="row flex-row-reverse align-items-center">';
                    echo '<div class="col-lg-6">';
                        echo '<div class="error-img">';
                            if(!empty(tourm_opt('tourm_error_img', 'url' ) )){
                                echo '<img src="'.esc_url( tourm_opt('tourm_error_img', 'url' ) ).'" alt="'.esc_attr__('404 image', 'tourm').'">';
                            }else{
                                echo '<img src="'.get_template_directory_uri().'/assets/img/error.svg" alt="'.esc_attr__('404 image', 'tourm').'">';
                            }
                        echo '</div>';
                    echo '</div>';
                   echo ' <div class="col-lg-6">';
                        echo '<div class="error-content">';
                            if(!empty($tourm404title)){
                                echo '<h2 class="error-title">'.esc_html( $tourm404title ).'</h2>';
                            }
                            if(!empty($tourm404subtitle)){
                                echo '<h4 class="error-subtitle">'.esc_html( $tourm404subtitle ).'</h4>';
                            }
                            if(!empty($tourm404description)){
                                echo '<p class="error-text">'.esc_html( $tourm404description ).'</p>';
                            }
                            echo '<a href="'.esc_url( home_url('/') ).'" class="th-btn style3"><img src="'.get_template_directory_uri().'/assets/img/right-arrow2.svg" alt="">'.esc_html( $tourm404btntext ).'</a>';
                        echo '</div>';
                    echo '</div>';
                echo '</div>';
            echo '</div>';
        echo '</section>';
    //footer
    get_footer();