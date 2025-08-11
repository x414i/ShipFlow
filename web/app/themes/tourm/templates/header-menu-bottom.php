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

    if( defined( 'CMB2_LOADED' )  ){
        if( !empty( tourm_meta('page_breadcrumb_area') ) ) {
            $tourm_page_breadcrumb_area  = tourm_meta('page_breadcrumb_area');
        } else {
            $tourm_page_breadcrumb_area = '1';
        }
    }else{
        $tourm_page_breadcrumb_area = '1';
    }
    
    $allowhtml = array(
        'p'         => array(
            'class'     => array()
        ),
        'span'      => array(
            'class'     => array(),
        ),
        'a'         => array(
            'href'      => array(),
            'title'     => array()
        ),
        'br'        => array(),
        'em'        => array(),
        'strong'    => array(),
        'b'         => array(),
        'sub'       => array(),
        'sup'       => array(),
    );
    
    if(  is_page() || is_page_template( 'template-builder.php' )  ) {
        if( $tourm_page_breadcrumb_area == '1' ) {
            echo '<!-- Page title 2 -->';
            
            if( class_exists( 'ReduxFramework' ) ){
                $ex_class = '';
            }else{
                $ex_class = ' th-breadcumb';   
            }
            echo '<div class="breadcumb-wrapper '. esc_attr($ex_class).'">';
                echo '<div class="container">';
                    echo '<div class="row justify-content-center">';
                        echo '<div class="col-xl-9">';
                            echo '<div class="breadcumb-content">';
                                if( defined('CMB2_LOADED') || class_exists('ReduxFramework') ) {
                                    if( !empty( tourm_meta('page_breadcrumb_settings') ) ) {
                                        if( tourm_meta('page_breadcrumb_settings') == 'page' ) {
                                            $tourm_page_title_switcher = tourm_meta('page_title');
                                        } else {
                                            $tourm_page_title_switcher = tourm_opt('tourm_page_title_switcher');
                                        }
                                    } else {
                                        $tourm_page_title_switcher = '1';
                                    }
                                } else {
                                    $tourm_page_title_switcher = '1';
                                }

                                if( $tourm_page_title_switcher ){
                                    if( class_exists( 'ReduxFramework' ) ){
                                        $tourm_page_title_tag    = tourm_opt('tourm_page_title_tag');
                                    }else{
                                        $tourm_page_title_tag    = 'h1';
                                    }

                                    if( defined( 'CMB2_LOADED' )  ){
                                        if( !empty( tourm_meta('page_title_settings') ) ) {
                                            $tourm_custom_title = tourm_meta('page_title_settings');
                                        } else {
                                            $tourm_custom_title = 'default';
                                        }
                                    }else{
                                        $tourm_custom_title = 'default';
                                    }

                                    if( $tourm_custom_title == 'default' ) {
                                        echo tourm_heading_tag(
                                            array(
                                                "tag"   => esc_attr( $tourm_page_title_tag ),
                                                "text"  => esc_html( get_the_title( ) ),
                                                'class' => 'breadcumb-title'
                                            )
                                        );
                                    } else {
                                        echo tourm_heading_tag(
                                            array(
                                                "tag"   => esc_attr( $tourm_page_title_tag ),
                                                "text"  => esc_html( tourm_meta('custom_page_title') ),
                                                'class' => 'breadcumb-title'
                                            )
                                        );
                                    }

                                }
                                if( defined('CMB2_LOADED') || class_exists('ReduxFramework') ) {

                                    if( tourm_meta('page_breadcrumb_settings') == 'page' ) {
                                        $tourm_breadcrumb_switcher = tourm_meta('page_breadcrumb_trigger');
                                    } else {
                                        $tourm_breadcrumb_switcher = tourm_opt('tourm_enable_breadcrumb');
                                    }

                                } else {
                                    $tourm_breadcrumb_switcher = '1';
                                }

                                if( $tourm_breadcrumb_switcher == '1' && (  is_page() || is_page_template( 'template-builder.php' ) )) {
                                        tourm_breadcrumbs(
                                            array(
                                                'breadcrumbs_classes' => '',
                                            )
                                        );
                                }
                            echo '</div>';
                        echo '</div>';
                    echo '</div>';
                echo '</div>';

            echo '</div>';
            echo '<!-- End of Page title -->';
            
        }
    } else {
        echo '<!-- Page title 3 -->';
         if( class_exists( 'ReduxFramework' ) ){
            $ex_class = '';
            if (class_exists( 'woocommerce' ) && is_shop()){
            $breadcumb_bg_class = 'custom-woo-class';
            }elseif(is_404()){
                $breadcumb_bg_class = 'custom-error-class';
            }elseif(is_search()){
                $breadcumb_bg_class = 'custom-search-class';
            }elseif(is_archive()){
                $breadcumb_bg_class = 'custom-archive-class';
            }else{
                $breadcumb_bg_class = '';
            }


            $blog_page_title = tourm_opt('tourm_breadcrumb_bp_title') ? tourm_opt('tourm_breadcrumb_bp_title') : 'Latest News';
            $search_page_title = tourm_opt('tourm_breadcrumb_sr_title') ? tourm_opt('tourm_breadcrumb_sr_title') : 'Search Result';
            $error_page_title = tourm_opt('tourm_breadcrumb_er_title') ? tourm_opt('tourm_breadcrumb_er_title') : 'Error Page';






        }else{
            $breadcumb_bg_class = ''; 
            $ex_class = ' th-breadcumb';     
        }
        echo '<div class="breadcumb-wrapper '. esc_attr($breadcumb_bg_class . $ex_class).'">'; 
            echo '<div class="container z-index-common">';
                    echo '<div class="breadcumb-content">';
                        if( class_exists( 'ReduxFramework' )  ){
                            $tourm_page_title_switcher  = tourm_opt('tourm_page_title_switcher');
                        }else{
                            $tourm_page_title_switcher = '1';
                        }

                        if( $tourm_page_title_switcher ){
                            if( class_exists( 'ReduxFramework' ) ){
                                $tourm_page_title_tag    = tourm_opt('tourm_page_title_tag');
                            }else{
                                $tourm_page_title_tag    = 'h1';
                            }
                            if( class_exists('woocommerce') && is_shop() ) {
                                echo tourm_heading_tag(
                                    array(
                                        "tag"   => esc_attr( $tourm_page_title_tag ),
                                        "text"  => wp_kses( woocommerce_page_title( false ), $allowhtml ),
                                        'class' => 'breadcumb-title'
                                    )
                                );
                            }elseif ( is_archive() ){
                                echo tourm_heading_tag(
                                    array(
                                        "tag"   => esc_attr( $tourm_page_title_tag ),
                                        "text"  => wp_kses( get_the_archive_title(), $allowhtml ),
                                        'class' => 'breadcumb-title'
                                    )
                                );
                            }elseif ( is_home() ){
                                $tourm_blog_page_title_setting = tourm_opt('tourm_blog_page_title_setting');
                                $tourm_blog_page_title_switcher = tourm_opt('tourm_blog_page_title_switcher');
                                $tourm_blog_page_custom_title = tourm_opt('tourm_blog_page_custom_title');
                                if( class_exists('ReduxFramework') ){
                                    if( $tourm_blog_page_title_switcher ){
                                        echo tourm_heading_tag(
                                            array(
                                                "tag"   => esc_attr( $tourm_page_title_tag ),
                                                "text"  => !empty( $tourm_blog_page_custom_title ) && $tourm_blog_page_title_setting == 'custom' ? esc_html( $tourm_blog_page_custom_title) : $blog_page_title,
                                                'class' => 'breadcumb-title'
                                            )
                                        );
                                    }
                                }else{
                                    echo tourm_heading_tag(
                                        array(
                                            "tag"   => "h1",
                                            "text"  => $blog_page_title,
                                            'class' => 'breadcumb-title',
                                        )
                                    );
                                }
                            }elseif( is_search() ){
                                echo tourm_heading_tag(
                                    array(
                                        "tag"   => esc_attr( $tourm_page_title_tag ),
                                        "text"  => $search_page_title,
                                        'class' => 'breadcumb-title'
                                    )
                                );
                            }elseif( is_404() ){
                                echo tourm_heading_tag(
                                    array(
                                        "tag"   => esc_attr( $tourm_page_title_tag ),
                                        "text"  => $error_page_title,
                                        'class' => 'breadcumb-title'
                                    )
                                );
                            }elseif( is_singular( 'product' ) ){
                                $posttitle_position  = tourm_opt('tourm_product_details_title_position');
                                $postTitlePos = false;
                                if( class_exists( 'ReduxFramework' ) ){
                                    if( $posttitle_position && $posttitle_position != 'header' ){
                                        $postTitlePos = true;
                                    }
                                }else{
                                    $postTitlePos = false;
                                }

                                if( $postTitlePos != true ){
                                    echo tourm_heading_tag(
                                        array(
                                            "tag"   => esc_attr( $tourm_page_title_tag ),
                                            "text"  => wp_kses( get_the_title( ), $allowhtml ),
                                            'class' => 'breadcumb-title'
                                        )
                                    );
                                } else {
                                    if( class_exists( 'ReduxFramework' ) ){
                                        $tourm_post_details_custom_title  = tourm_opt('tourm_product_details_custom_title');
                                    }else{
                                        $tourm_post_details_custom_title = __( 'Shop Details','tourm' );
                                    }

                                    if( !empty( $tourm_post_details_custom_title ) ) {
                                        echo tourm_heading_tag(
                                            array(
                                                "tag"   => esc_attr( $tourm_page_title_tag ),
                                                "text"  => wp_kses( $tourm_post_details_custom_title, $allowhtml ),
                                                'class' => 'breadcumb-title'
                                            )
                                        );
                                    }
                                }
                            }else{
                                $posttitle_position  = tourm_opt('tourm_post_details_title_position');
                                $postTitlePos = false;
                                if( is_single() ){
                                    if( class_exists( 'ReduxFramework' ) ){
                                        if( $posttitle_position && $posttitle_position != 'header' ){
                                            $postTitlePos = true;
                                        }
                                    }else{
                                        $postTitlePos = false;
                                    }
                                }
                                if( is_singular( 'product' ) ){
                                    $posttitle_position  = tourm_opt('tourm_product_details_title_position');
                                    $postTitlePos = false;
                                    if( class_exists( 'ReduxFramework' ) ){
                                        if( $posttitle_position && $posttitle_position != 'header' ){
                                            $postTitlePos = true;
                                        }
                                    }else{
                                        $postTitlePos = false;
                                    }
                                }

                                if( $postTitlePos != true ){
                                    echo tourm_heading_tag(
                                        array(
                                            "tag"   => esc_attr( $tourm_page_title_tag ),
                                            "text"  => wp_kses( get_the_title( ), $allowhtml ),
                                            'class' => 'breadcumb-title'
                                        )
                                    );
                                } else {
                                    if( class_exists( 'ReduxFramework' ) ){
                                        $tourm_post_details_custom_title  = tourm_opt('tourm_post_details_custom_title');
                                    }else{
                                        $tourm_post_details_custom_title = __( 'Blog Details','tourm' );
                                    }

                                    if( !empty( $tourm_post_details_custom_title ) ) {
                                        echo tourm_heading_tag(
                                            array(
                                                "tag"   => esc_attr( $tourm_page_title_tag ),
                                                "text"  => wp_kses( $tourm_post_details_custom_title, $allowhtml ),
                                                'class' => 'breadcumb-title'
                                            )
                                        );
                                    }
                                }
                            }
                        }
                        if( class_exists('ReduxFramework') ) {
                            $tourm_breadcrumb_switcher = tourm_opt( 'tourm_enable_breadcrumb' );
                        } else {
                            $tourm_breadcrumb_switcher = '1';
                        }
                        if( $tourm_breadcrumb_switcher == '1' ) {
                            if(tourm_breadcrumbs()){
                            echo '<div>';
                                tourm_breadcrumbs(
                                    array(
                                        'breadcrumbs_classes' => 'nav',
                                    )
                                );
                            echo '</div>';
                            }
                        }
                    echo '</div>';
            echo '</div>';

        echo '</div>';
        echo '<!-- End of Page title -->';
    }

