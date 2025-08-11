<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <?php wp_head();?>
</head>
<body <?php body_class(); ?>>

<?php

    wp_body_open();

    $mouse_follower_trigger = tourm_opt('tourm_display_mouse_follower');
    $tourm_display_slider_drag = tourm_opt('tourm_display_slider_drag');

    if($mouse_follower_trigger){

        echo '<div class="cursor-follower"></div>';
    }

    if($tourm_display_slider_drag){

        $drag_text = tourm_opt('drag_text');

        echo '<div class="slider-drag-cursor"><i class="fas fa-angle-left me-2"></i>'.esc_html( $drag_text ).'<i class="fas fa-angle-right ms-2"></i></div>';
    }

    /**
    *
    * Preloader
    *
    * Hook tourm_preloader_wrap
    *
    * @Hooked tourm_preloader_wrap_cb 10
    *
    */
    do_action( 'tourm_preloader_wrap' );



    /**
    *
    * tourm header
    *
    * Hook tourm_header
    *
    * @Hooked tourm_header_cb 10
    *
    */
    do_action( 'tourm_header' );
    