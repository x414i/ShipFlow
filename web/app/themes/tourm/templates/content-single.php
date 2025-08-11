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

    tourm_setPostViews( get_the_ID() );

    ?>
    <div <?php post_class(); ?>>
    <?php
        if( class_exists('ReduxFramework') ) {
            $tourm_post_details_title_position = tourm_opt('tourm_post_details_title_position');
        } else {
            $tourm_post_details_title_position = 'header';
        }

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
        // Blog Post Thumbnail
        do_action( 'tourm_blog_post_thumb' );
        
        echo '<div class="blog-content">';
            // Blog Post Meta
            do_action( 'tourm_blog_post_meta' );

            if( $tourm_post_details_title_position != 'header' ) {
                echo '<h2 class="blog-title">'.wp_kses( get_the_title(), $allowhtml ).'</h2>';
            }

            if( get_the_content() ){

                the_content();
                // Link Pages
                tourm_link_pages();
            }  

            if( class_exists('ReduxFramework') ) {
                $tourm_post_details_share_options = tourm_opt('tourm_post_details_share_options');
                $tourm_display_post_tags = tourm_opt('tourm_display_post_tags');
                $tourm_author_options = tourm_opt('tourm_post_details_author_desc_trigger');
            } else {
                $tourm_post_details_share_options = false;
                $tourm_display_post_tags = false;
                $tourm_author_options = false;
            }
            
            $tourm_post_tag = get_the_tags();
            
            if( ! empty( $tourm_display_post_tags ) || ( ! empty($tourm_post_details_share_options )) ){
                echo '<div class="share-links clearfix">';
                    echo '<div class="row justify-content-between">';
                        if( is_array( $tourm_post_tag ) && ! empty( $tourm_post_tag ) ){
                            if( count( $tourm_post_tag ) > 1 ){
                                $tag_text = __( 'Tags:', 'tourm' );
                            }else{
                                $tag_text = __( 'Tag:', 'tourm' );
                            }
                            if($tourm_display_post_tags){
                                echo '<div class="col-md-auto">';
                                    echo '<span class="share-links-title">'.esc_html($tag_text).'</span>';
                                    echo '<div class="tagcloud">';
                                        foreach( $tourm_post_tag as $tags ){
                                            echo '<a href="'.esc_url( get_tag_link( $tags->term_id ) ).'">'.esc_html( $tags->name ).'</a>';
                                        }
                                    echo '</div>';
                                echo '</div>';
                            }
                        }
    
                        /**
                        *
                        * Hook for Blog Details Share Options
                        *
                        * Hook tourm_blog_details_share_options
                        *
                        * @Hooked tourm_blog_details_share_options_cb 10
                        *
                        */
                        do_action( 'tourm_blog_details_share_options' );
    
                    echo '</div>';
    
                echo '</div>';    
            }  
        
        echo '</div>';

    echo '</div>'; 

        /**
        *
        * Hook for Post Navigation
        *
        * Hook tourm_blog_details_post_navigation
        *
        * @Hooked tourm_blog_details_post_navigation_cb 10
        *
        */
        do_action( 'tourm_blog_details_post_navigation' );

        /**
        *
        * Hook for Blog Authro Bio
        *
        * Hook tourm_blog_details_author_bio
        *
        * @Hooked tourm_blog_details_author_bio_cb 10
        *
        */
        do_action( 'tourm_blog_details_author_bio' );

        /**
        *
        * Hook for Blog Details Comments
        *
        * Hook tourm_blog_details_comments
        *
        * @Hooked tourm_blog_details_comments_cb 10
        *
        */
        do_action( 'tourm_blog_details_comments' );
