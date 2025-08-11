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


    // preloader hook function
    if( ! function_exists( 'tourm_preloader_wrap_cb' ) ) {
        function tourm_preloader_wrap_cb() {
            $preloader_display              =  tourm_opt('tourm_display_preloader');
            $preloader_text              =  tourm_opt('tourm_preloader_text');
            $preloader_text_cancel              =  tourm_opt('tourm_preloader_cancel_text');

            if( class_exists('ReduxFramework') ){
                if( $preloader_display ){
                    echo '<div id="preloader" class="preloader ">';
                        echo '<button class="th-btn preloaderCls">'.esc_html( $preloader_text_cancel ).'</button>';
                        echo '<div id="loader" class="th-preloader">';
                            if( $preloader_text ){
                                echo '<div class="animation-preloader">';
                                    echo '<div class="txt-loading">';
                                        for ($i = 0; $i < strlen($preloader_text); $i++) {
                                            $char = $preloader_text[$i];
                                            if ($char === ' ') {
                                                echo ' ';
                                            } else {
                                                echo '<span preloader-text="' . $char . '" class="characters">' . $char . '</span>';
                                            }
                                        }
                                    echo '</div>';
                                echo '</div>';
                            }else{
                                echo '<div class="loader"></div>';
                            }
                        echo '</div>';
                    echo '</div>';
                }
            }else{
                echo '<div class="preloader">';
                    echo '<button class="th-btn shadow-1 preloaderCls">'.esc_html__( 'Cancel Preloader', 'tourm' ).'</button>';
                    echo '<div class="preloader-inner">';
                        echo '<div class="loader"></div>';
                    echo '</div>';
                echo '</div>';
            }

        }
    }

    // Header Hook function
    if( !function_exists('tourm_header_cb') ) { 
        function tourm_header_cb( ) {
            get_template_part('templates/header');
            get_template_part('templates/header-menu-bottom');
        }
    }

    // back top top hook function
    if( ! function_exists( 'tourm_back_to_top_cb' ) ) {
        function tourm_back_to_top_cb( ) {
            $backtotop_trigger = tourm_opt('tourm_display_bcktotop');
            if( class_exists( 'ReduxFramework' ) ) {
                if( $backtotop_trigger ) {
            	?>
                    <div class="scroll-top">
                        <svg class="progress-circle svg-content" width="100%" height="100%" viewBox="-1 -1 102 102">
                            <path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98" style="transition: stroke-dashoffset 10ms linear 0s; stroke-dasharray: 307.919, 307.919; stroke-dashoffset: 307.919;">
                            </path>
                        </svg>
                    </div>
                <?php 
                }
            }

        }
    }

    // Blog Start Wrapper Function
    if( !function_exists('tourm_blog_start_wrap_cb') ) {
        function tourm_blog_start_wrap_cb() { ?>
            <section class="th-blog-wrapper space-top space-extra-bottom">
                <div class="container">
                    <div class="row">
        <?php }
    }

    // Blog End Wrapper Function
    if( !function_exists('tourm_blog_end_wrap_cb') ) {
        function tourm_blog_end_wrap_cb() {?>
                    </div>
                </div>
            </section>
        <?php }
    }

    // Blog Column Start Wrapper Function
    if( !function_exists('tourm_blog_col_start_wrap_cb') ) {
        function tourm_blog_col_start_wrap_cb() {
           
                //Redux option work
                if( class_exists('ReduxFramework') ) {
                    $tourm_blog_sidebar = tourm_opt('tourm_blog_sidebar');
                }else{
                    $tourm_blog_sidebar = '1';
                }

                if( class_exists('ReduxFramework') ) {
                    // $tourm_blog_sidebar = tourm_opt('tourm_blog_sidebar');
                    if( $tourm_blog_sidebar == '2' && is_active_sidebar('tourm-blog-sidebar') ) {
                        echo '<div class="col-xxl-8 col-lg-7  order-lg-last">';
                    } elseif( $tourm_blog_sidebar == '3' && is_active_sidebar('tourm-blog-sidebar') ) {
                        echo '<div class="col-xxl-8 col-lg-7">';
                    } else {
                        echo '<div class="col-lg-12">';
                    }

                } else {
                    if( is_active_sidebar('tourm-blog-sidebar') ) {
                        echo '<div class="col-xxl-8 col-lg-7">';
                    } else {
                        echo '<div class="col-lg-12">';
                    }
                }
                

        }
    }
    // Blog Column End Wrapper Function
    if( !function_exists('tourm_blog_col_end_wrap_cb') ) {
        function tourm_blog_col_end_wrap_cb() {
            echo '</div>';
        }
    }

    // Blog Sidebar
    if( !function_exists('tourm_blog_sidebar_cb') ) {
        function tourm_blog_sidebar_cb( ) {
            if( class_exists('ReduxFramework') ) {
                $tourm_blog_sidebar = tourm_opt('tourm_blog_sidebar');
            } else {
                $tourm_blog_sidebar = 2;
                
            }
            if( $tourm_blog_sidebar != 1 && is_active_sidebar('tourm-blog-sidebar') ) {
                // Sidebar
                get_sidebar();
            }
        }
    }


    if( !function_exists('tourm_blog_details_sidebar_cb') ) {
        function tourm_blog_details_sidebar_cb( ) {
            if( class_exists('ReduxFramework') ) {
                $tourm_blog_single_sidebar = tourm_opt('tourm_blog_single_sidebar');
            } else {
                $tourm_blog_single_sidebar = 4;
            }
            if( $tourm_blog_single_sidebar != 1 ) {
                // Sidebar
                get_sidebar();
            }

        }
    }

    // Blog Pagination Function
    if( !function_exists('tourm_blog_pagination_cb') ) {
        function tourm_blog_pagination_cb( ) {
            get_template_part('templates/pagination');
        }
    }

    // Blog Content Function
    if( !function_exists('tourm_blog_content_cb') ) {
        function tourm_blog_content_cb( ) {
            // Demo style show by get url varibale
            if ( isset( $_GET['column'] ) ) {
                $column_value = sanitize_text_field( $_GET['column'] );
            }
            if( isset( $_GET['column'] ) && ($column_value === '2' || $column_value === '3' ) ){
                if ( !empty($column_value) ) {
                    $tourm_blog_grid = $column_value;
                }else{
                    $tourm_blog_grid = 1;
                }
            }else{
                //Redux option work
                if( class_exists('ReduxFramework') ) {
                    $tourm_blog_grid = tourm_opt('tourm_blog_grid');
                }else{
                    $tourm_blog_grid = '1';
                }
            } //End - Demo style show by get url varibale 


            if( $tourm_blog_grid == '1' ) {
                $tourm_blog_grid_class = 'col-lg-12';
            } elseif( $tourm_blog_grid == '2' ) {
                $tourm_blog_grid_class = 'col-sm-6';
            } else {
                $tourm_blog_grid_class = 'col-lg-4 col-sm-6';
            }

            echo '<div class="row">';
                if( have_posts() ) {
                    while( have_posts() ) {
                        the_post();
                        echo '<div class="'.esc_attr($tourm_blog_grid_class).'">';
                            get_template_part('templates/content',get_post_format());
                        echo '</div>';
                    }
                    wp_reset_postdata();
                } else{
                    get_template_part('templates/content','none');
                }
            echo '</div>';
        }
    }

    // footer content Function
    if( !function_exists('tourm_footer_content_cb') ) {
        function tourm_footer_content_cb( ) {

            if( class_exists('ReduxFramework') && did_action( 'elementor/loaded' )  ){
                if( is_page() || is_page_template('template-builder.php') ) {
                    $post_id = get_the_ID();

                    // Get the page settings manager
                    $page_settings_manager = \Elementor\Core\Settings\Manager::get_settings_managers( 'page' );

                    // Get the settings model for current post
                    $page_settings_model = $page_settings_manager->get_model( $post_id );

                    // Retrieve the Footer Style
                    $footer_settings = $page_settings_model->get_settings( 'tourm_footer_style' );

                    // Footer Local
                    $footer_local = $page_settings_model->get_settings( 'tourm_footer_builder_option' );

                    // Footer Enable Disable
                    $footer_enable_disable = $page_settings_model->get_settings( 'tourm_footer_choice' );

                    if( $footer_enable_disable == 'yes' ){
                        if( $footer_settings == 'footer_builder' ) {
                            // local options
                            $tourm_local_footer = get_post( $footer_local );
                            echo '<footer>';
                            echo \Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $tourm_local_footer->ID );
                            echo '</footer>';
                        } else {
                            // global options
                            $tourm_footer_builder_trigger = tourm_opt('tourm_footer_builder_trigger');
                            if( $tourm_footer_builder_trigger == 'footer_builder' ) {
                                echo '<footer>';
                                $tourm_global_footer_select = get_post( tourm_opt( 'tourm_footer_builder_select' ) );
                                $footer_post = get_post( $tourm_global_footer_select );
                                echo \Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $footer_post->ID );
                                echo '</footer>';
                            } else {
                                // wordpress widgets
                                tourm_footer_global_option();
                            }
                        }
                    }
                } else {
                    // global options
                    $tourm_footer_builder_trigger = tourm_opt('tourm_footer_builder_trigger');
                    if( $tourm_footer_builder_trigger == 'footer_builder' ) {
                        echo '<footer>';
                        $tourm_global_footer_select = get_post( tourm_opt( 'tourm_footer_builder_select' ) );
                        $footer_post = get_post( $tourm_global_footer_select );
                        echo \Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $footer_post->ID );
                        echo '</footer>';
                    } else {
                        // wordpress widgets
                        tourm_footer_global_option();
                    }
                }
            } else { ?>
                <div class="footer-layout1 footer-sticky">
                    <div class="copyright-wrap bg-theme">
                        <div class="container">
                            <p class="copyright-text text-center text-white"><?php echo sprintf( 'Copyright <i class="fal fa-copyright"></i> %s <a class="text-white" href="%s"> %s </a> All Rights Reserved.', date('Y'), esc_url('#'), esc_html__( 'Tourm.','tourm') ); ?></p> 
                        </div>
                    </div>
                </div>
            <?php }

        }
    }

    // blog details wrapper start hook function
    if( !function_exists('tourm_blog_details_wrapper_start_cb') ) {
        function tourm_blog_details_wrapper_start_cb( ) {
            echo '<section class="th-blog-wrapper blog-details space-top space-extra-bottom">';
                echo '<div class="container">';
                    if( is_active_sidebar( 'tourm-blog-sidebar' ) ){
                        $tourm_gutter_class = 'gx-60';
                    }else{
                        $tourm_gutter_class = '';
                    }
                    // echo '<div class="row './/esc_attr( $tourm_gutter_class ).'">';
                    echo '<div class="row">';
        }
    }

    // blog details column wrapper start hook function
    if( !function_exists('tourm_blog_details_col_start_cb') ) {
        function tourm_blog_details_col_start_cb( ) {
            if( class_exists('ReduxFramework') ) {
                $tourm_blog_single_sidebar = tourm_opt('tourm_blog_single_sidebar');
                if( $tourm_blog_single_sidebar == '2' && is_active_sidebar('tourm-blog-sidebar') ) {
                    echo '<div class="col-xxl-8 col-lg-7 order-lg-last">';
                } elseif( $tourm_blog_single_sidebar == '3' && is_active_sidebar('tourm-blog-sidebar') ) {
                    echo '<div class="col-xxl-8 col-lg-7">';
                } else {
                    echo '<div class="col-lg-12">';
                }

            } else {
                if( is_active_sidebar('tourm-blog-sidebar') ) {
                    echo '<div class="col-xxl-8 col-lg-7">';
                } else {
                    echo '<div class="col-lg-12">';
                }
            }
        }
    }

    // blog details post meta hook function
    if( !function_exists('tourm_blog_post_meta_cb') ) {
        function tourm_blog_post_meta_cb( ) {
            if( class_exists('ReduxFramework') ) {
                $tourm_display_post_author      =  tourm_opt('tourm_display_post_author');
                $tourm_display_post_date      =  tourm_opt('tourm_display_post_date');
                $tourm_display_post_cate   =  tourm_opt('tourm_display_post_cate');
                $tourm_display_post_comments      =  tourm_opt('tourm_display_post_comments');
            } else {
                $tourm_display_post_author      = '1';
                $tourm_display_post_date      = '1';
                $tourm_display_post_cate   = '1';
                $tourm_display_post_comments      = '0'; 
            }

                echo '<div class="blog-meta">';
                    if( $tourm_display_post_author ){
                        echo '<a href="'.esc_url( get_author_posts_url( get_the_author_meta('ID') ) ).'"><i class="fa-light fa-user"></i>'.esc_html( ucwords( get_the_author() ) ).'</a>';
                    }
                    if( $tourm_display_post_date ){
                        echo ' <a href="'.esc_url( tourm_blog_date_permalink() ).'"><i class="fa-regular fa-calendar"></i>'.esc_html( get_the_date() ).'</a>';
                    }
                    if( $tourm_display_post_cate ){
                        $categories = get_the_category(); 
                        if(!empty($categories)){
                        echo '<a href="'.esc_url( get_category_link( $categories[0]->term_id ) ).'"><img src="'.get_template_directory_uri().'/assets/img/icon/map.svg" alt="">'.esc_html( $categories[0]->name ).'</a>';
                        }
                    }
                    if( $tourm_display_post_comments ){
                        ?>
                        <a href="#"><i class="fa-solid fa-comments"></i>
                            <?php 
                                if(get_comments_number() == 1){
                                    echo esc_html__('Comment (', 'tourm'); 
                                }else{
                                    echo esc_html__('Comments (', 'tourm'); 
                                }
                                echo get_comments_number(); ?>)</a>
                        <?php
                    }
                echo '</div>';
        }
    }

    // blog details share options hook function
    if( !function_exists('tourm_blog_details_share_options_cb') ) {
        function tourm_blog_details_share_options_cb( ) {
            if( class_exists('ReduxFramework') ) {
                $tourm_post_details_share_options = tourm_opt('tourm_post_details_share_options');
            } else {
                $tourm_post_details_share_options = false;
            }
            if( function_exists( 'tourm_social_sharing_buttons' ) && $tourm_post_details_share_options ) { ?>
                <div class="col-md-auto text-xl-end">
                    <div class="share-links_wrapp">
                        <span class="share-links-title"><?php echo esc_html__('Share:', 'tourm') ?></span>
                        <div class="social-links">
                            <?php echo tourm_social_sharing_buttons(); ?>
                        </div>
                    </div>
                </div>
            <?php }
        }
    }
    
    // blog details author bio hook function
    if( !function_exists('tourm_blog_details_author_bio_cb') ) {
        function tourm_blog_details_author_bio_cb( ) {
            if( class_exists('ReduxFramework') ) {
                $postauthorbox =  tourm_opt( 'tourm_post_details_author_box' );
            } else {
                $postauthorbox = '0';
            }
            if(  $postauthorbox == '1' ) {

                echo '<div class="author-widget-wrap">';
                        echo '<div class="avater">';
                            echo '<img src="'.esc_url( get_avatar_url( get_the_author_meta('ID') ) ).'" alt="img">';
                        echo '</div>';
                        echo '<div class="avater-content">';
                            echo ' <div class="author-info">';
                                echo '<h3 class="name"><a class="text-inherit" href="#">'.esc_html( ucwords( get_the_author() )).'</a></h3>';
                                echo '<span class="text">'.get_user_meta( get_the_author_meta('ID'), '_tourm_author_desig',true ).'</span>';
                            echo '</div>';
                            echo '<p class="author-bio">'.get_the_author_meta( 'user_description', get_the_author_meta('ID') ).'</p>';
                            echo '<div class="social-links">';
                                    $tourm_social_icons = get_user_meta( get_the_author_meta('ID'), '_tourm_social_profile_group',true );
                                    if(!empty($tourm_social_icons)){
                                        foreach( $tourm_social_icons as $singleicon ) {
                                            if( ! empty( $singleicon['_tourm_social_profile_icon'] ) ) {
                                                echo '<a href="'.esc_url( $singleicon['_tourm_lawyer_social_profile_link'] ).'"><i class="'.esc_attr( $singleicon['_tourm_social_profile_icon'] ).'"></i></a>';
                                            }
                                        }
                                    }
                            echo '</div>';
                        echo '</div>';
             echo '</div>';

               
            }

        }
    }

     // Blog Details Post Navigation hook function
     if( !function_exists( 'tourm_blog_details_post_navigation_cb' ) ) {
        function tourm_blog_details_post_navigation_cb( ) {
            if( class_exists('ReduxFramework') ) {
                $tourm_post_navigation = tourm_opt('tourm_post_details_post_navigation');
            } else {
                $tourm_post_navigation = 0;
            }

            $prevpost = get_previous_post();
            $nextpost = get_next_post();

            $allowhtml = array(
                'p'         => array(
                    'class'     => array()
                ),
                'span'      => array(),
                'a'         => array(
                    'href'      => array(),
                    'title'     => array()
                ),
                'br'        => array(),
                'em'        => array(),
                'strong'    => array(),
                'b'         => array(),
            );

            if( ($tourm_post_navigation == '1') && (!empty($prevpost) || !empty($nextpost)) ) {
                echo '<div class="blog-navigation">';
                    echo '<div>';
                        if( ! empty( $prevpost ) ) {
                            echo '<a href="'.esc_url( get_permalink( $prevpost->ID ) ).'" class="nav-btn prev">';
                            if( class_exists('ReduxFramework') ) {
                                if (has_post_thumbnail( $prevpost->ID )) {
                                    echo get_the_post_thumbnail( $prevpost->ID, 'tourm_85X85' );
                                };
                            }
                                echo '<span class="nav-text">'.esc_html__( ' Previous Post', 'tourm' ).'</span>';
                            echo '</a>';
                        }
                    echo '</div>';

                    echo '<a href="'.get_permalink( get_option( 'page_for_posts' ) ).'" class="blog-btn"><i class="fa-solid fa-grid"></i></a>';

                    echo '<div>';
                        if( ! empty( $nextpost ) ) {
                            echo '<a href="'.esc_url( get_permalink( $nextpost->ID ) ).'" class="nav-btn next">';
                                if( class_exists('ReduxFramework') ) {
                                    if (has_post_thumbnail($nextpost->ID)) {
                                        echo get_the_post_thumbnail( $nextpost->ID, 'tourm_85X85' );
                                    };
                                }
                                echo '<span class="nav-text">'.esc_html__( ' Next Post', 'tourm' ).'</span>';
                            echo '</a>';
                        }
                    echo '</div>';
                echo '</div>';
            }

        }
    }

    // Blog Details Comments hook function
    if( !function_exists('tourm_blog_details_comments_cb') ) {
        function tourm_blog_details_comments_cb( ) {
            if ( ! comments_open() ) {
                echo '<div class="blog-comment-area">';
                    echo tourm_heading_tag( array(
                        "tag"   => "h3",
                        "text"  => esc_html__( 'Comments are closed', 'tourm' ),
                        "class" => "inner-title"
                    ) );
                echo '</div>';
            }

            // comment template.
            if ( comments_open() || get_comments_number() ) {
                comments_template();
            }
        }
    }

    // Blog Details Column end hook function
    if( !function_exists('tourm_blog_details_col_end_cb') ) {
        function tourm_blog_details_col_end_cb( ) {
            echo '</div>';
        }
    }

    // Blog Details Wrapper end hook function
    if( !function_exists('tourm_blog_details_wrapper_end_cb') ) {
        function tourm_blog_details_wrapper_end_cb( ) {
                    echo '</div>';
                echo '</div>';
            echo '</section>';
        }
    }

    // page start wrapper hook function
    if( !function_exists('tourm_page_start_wrap_cb') ) {
        function tourm_page_start_wrap_cb( ) {
            
            if( is_page( 'cart' ) ){
                $section_class = "th-cart-wrapper space-top space-extra-bottom";
            }elseif( is_page( 'checkout' ) ){
                $section_class = "th-checkout-wrapper space-top space-extra-bottom";
            }elseif( is_page('wishlist') ){
                $section_class = "wishlist-area space-top space-extra-bottom";
            }else{
                $section_class = "space-top space-extra-bottom";  
            }
            echo '<section class="'.esc_attr( $section_class ).'">';
                echo '<div class="container">';
                    echo '<div class="row">';
        }
    }

    // page wrapper end hook function
    if( !function_exists('tourm_page_end_wrap_cb') ) {
        function tourm_page_end_wrap_cb( ) {
                    echo '</div>';
                echo '</div>';
            echo '</section>';
        }
    }

    // page column wrapper start hook function
    if( !function_exists('tourm_page_col_start_wrap_cb') ) {
        function tourm_page_col_start_wrap_cb( ) {
            if( class_exists('ReduxFramework') ) {
                $tourm_page_sidebar = tourm_opt('tourm_page_sidebar');
            }else {
                $tourm_page_sidebar = '1';
            }
            
            if( $tourm_page_sidebar == '2' && is_active_sidebar('tourm-page-sidebar') ) {
                echo '<div class="col-lg-8 order-last">';
            } elseif( $tourm_page_sidebar == '3' && is_active_sidebar('tourm-page-sidebar') ) {
                echo '<div class="col-lg-8">';
            } else {
                echo '<div class="col-lg-12">';
            }

        }
    }

    // page column wrapper end hook function
    if( !function_exists('tourm_page_col_end_wrap_cb') ) {
        function tourm_page_col_end_wrap_cb( ) {
            echo '</div>';
        }
    }

    // page sidebar hook function
    if( !function_exists('tourm_page_sidebar_cb') ) {
        function tourm_page_sidebar_cb( ) {
            if( class_exists('ReduxFramework') ) {
                $tourm_page_sidebar = tourm_opt('tourm_page_sidebar');
            }else {
                $tourm_page_sidebar = '1';
            }

            if( class_exists('ReduxFramework') ) {
                $tourm_page_layoutopt = tourm_opt('tourm_page_layoutopt');
            }else {
                $tourm_page_layoutopt = '3';
            }

            if( $tourm_page_layoutopt == '1' && $tourm_page_sidebar != 1 ) {
                get_sidebar('page');
            } elseif( $tourm_page_layoutopt == '2' && $tourm_page_sidebar != 1 ) {
                get_sidebar();
            }
        }
    }

    // page content hook function
    if( !function_exists('tourm_page_content_cb') ) {
        function tourm_page_content_cb( ) {
            if(  class_exists('woocommerce') && ( is_woocommerce() || is_cart() || is_checkout() || is_page('wishlist') || is_account_page() )  ) {
                echo '<div class="woocommerce--content">';
            } else {
                echo '<div class="page--content clearfix">';
            }

                the_content();

                // Link Pages
                tourm_link_pages();

            echo '</div>';
            // comment template.
            if ( comments_open() || get_comments_number() ) {
                comments_template();
            }

        }
    }

    if( !function_exists('tourm_blog_post_thumb_cb') ) {
        function tourm_blog_post_thumb_cb( ) {
            if( get_post_format() ) {
                $format = get_post_format();
            }else{
                $format = 'standard';
            }

            $tourm_post_slider_thumbnail = tourm_meta( 'post_format_slider' );

            if( !empty( $tourm_post_slider_thumbnail ) ){
                echo '<div class="blog-img th-slider" data-slider-options=\'{"effect":"fade"}\'>';
                    echo '<div class="swiper-wrapper">';
                        foreach( $tourm_post_slider_thumbnail as $single_image ){
                            echo '<div class="swiper-slide">';
                                echo tourm_img_tag( array(
                                    'url'   => esc_url( $single_image )
                                ) );
                            echo '</div>';
                        }
                    echo '</div>';
                    echo '<button class="slider-arrow slider-prev"><i class="far fa-arrow-left"></i></button>';
                    echo '<button class="slider-arrow slider-next"><i class="far fa-arrow-right"></i></button>';
                echo '</div>';

            }elseif( has_post_thumbnail() && $format == 'standard' ) {
                echo '<!-- Post Thumbnail -->';
                echo '<div class="blog-img">';
                    if( ! is_single() ){
                        echo '<a href="'.esc_url( get_permalink() ).'" class="post-thumbnail">';
                    }

                    the_post_thumbnail();

                    if( ! is_single() ){
                        echo '</a>';
                    }
                echo '</div>';
                echo '<!-- End Post Thumbnail -->';
            }elseif( $format == 'video' ){
                if( has_post_thumbnail() && ! empty ( tourm_meta( 'post_format_video' ) ) ){
                    echo '<div class="blog-img blog-video" data-overlay="title" data-opacity="4">';
                        if( ! is_single() ){
                            echo '<a href="'.esc_url( get_permalink() ).'" class="post-thumbnail">';
                        }
                            the_post_thumbnail();

                        if( ! is_single() ){
                            echo '</a>';
                        }
                        echo '<a href="'.esc_url( tourm_meta( 'post_format_video' ) ).'" class="play-btn popup-video">';
                            echo '<i class="fas fa-play"></i>';
                        echo '</a>';
                    echo '</div>';
                }elseif( ! has_post_thumbnail() && ! is_single() ){
                    echo '<div class="blog-video">';
                        if( ! is_single() ){
                            echo '<a href="'.esc_url( get_permalink() ).'" class="post-thumbnail">';
                        }
                            echo tourm_embedded_media( array( 'video', 'iframe' ) );
                        if( ! is_single() ){
                            echo '</a>';
                        }
                    echo '</div>';
                }
            }elseif( $format == 'audio' ){
                $tourm_audio = tourm_meta( 'post_format_audio' );
                if( ! empty( $tourm_audio ) ){
                    echo '<div class="blog-audio">';
                        echo wp_oembed_get( $tourm_audio );
                    echo '</div>';
                }elseif( ! is_single() ){
                    echo '<div class="blog-audio">';
                        echo wp_oembed_get( $tourm_audio );
                    echo '</div>';
                }
            }

        }
    }

    if( !function_exists('tourm_blog_post_content_cb') ) {
        function tourm_blog_post_content_cb( ) {
            $allowhtml = array(
                'p'         => array(
                    'class'     => array()
                ),
                'span'      => array(),
                'a'         => array(
                    'href'      => array(),
                    'title'     => array()
                ),
                'br'        => array(),
                'em'        => array(),
                'strong'    => array(),
                'b'         => array(),
            );
            if( class_exists( 'ReduxFramework' ) ) {
                $tourm_excerpt_length          = tourm_opt( 'tourm_blog_postExcerpt' );
                $tourm_display_post_category   = tourm_opt( 'tourm_display_post_category' );
            } else {
                $tourm_excerpt_length          = '48';
                $tourm_display_post_category   = '1';
            }

            if( class_exists( 'ReduxFramework' ) ) {
                $tourm_blog_admin = tourm_opt( 'tourm_blog_post_author' );
                $tourm_blog_readmore_setting_val = tourm_opt('tourm_blog_readmore_setting');
                if( $tourm_blog_readmore_setting_val == 'custom' ) {
                    $tourm_blog_readmore_setting = tourm_opt('tourm_blog_custom_readmore');
                } else {
                    $tourm_blog_readmore_setting = __( 'READ MORE', 'tourm' );
                }
            } else {
                $tourm_blog_readmore_setting = __( 'READ MORE', 'tourm' );
                $tourm_blog_admin = true;
            }
            echo '<!-- blog-content -->';

                do_action( 'tourm_blog_post_thumb' );
                
                echo '<div class="blog-content">';

                    // Blog Post Meta
                    do_action( 'tourm_blog_post_meta' );

                    echo '<!-- Post Title -->';
                    echo '<h2 class="blog-title"><a href="'.esc_url( get_permalink() ).'">'.wp_kses( get_the_title( ), $allowhtml ).'</a></h2>';
                    echo '<!-- End Post Title -->';

                    echo '<!-- Post Summary -->';
                    echo tourm_paragraph_tag( array(
                        "text"  => wp_kses( wp_trim_words( get_the_excerpt(), $tourm_excerpt_length, '' ), $allowhtml ),
                        "class" => 'blog-text',
                    ) );
  
                    if( !empty( $tourm_blog_readmore_setting ) ){
                        echo '<a href="'.esc_url( get_permalink() ).'" class="th-btn style-border2 th-btn-icon">'.esc_html( $tourm_blog_readmore_setting ).'</a>';
                    }

                    echo '<!-- End Post Summary -->';
                echo '</div>';
            echo '<!-- End Post Content -->';
        }
    }
