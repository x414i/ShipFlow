<?php
/**
 * @Packge     : Tourm
 * @Version    : 1.0
 * @Author     : Themeholy
 * @Author URI : https://themeforest.net/user/themeholy
 *
 */

// Block direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Enqueue scripts and styles.
 */
function tourm_essential_scripts() {

    wp_enqueue_style( 'tourm-style', get_stylesheet_uri() ,array(), wp_get_theme()->get( 'Version' ) ); 
    wp_enqueue_style( 'tourm-fonts', tourm_google_fonts() ,array(), null );
    wp_enqueue_style( 'bootstrap', get_theme_file_uri( '/assets/css/bootstrap.min.css' ) ,array(), '5.0.0' );
    wp_enqueue_style( 'app', get_theme_file_uri( '/assets/css/app.min.css' ) ,array(), '5.0.0' );
    wp_enqueue_style( 'fontawesome', get_theme_file_uri( '/assets/css/fontawesome.min.css' ) ,array(), '6.0.0' );
    wp_enqueue_style( 'magnific-popup', get_theme_file_uri( '/assets/css/magnific-popup.min.css' ), array(), '1.0' );
    wp_enqueue_style( 'imageRevealHover', get_theme_file_uri( '/assets/css/imageRevealHover.css' ), array(), '1.0' );
    wp_enqueue_style( 'magic-cursor', get_theme_file_uri( '/assets/css/magic-cursor.min.css' ), array(), '1.0' );
    wp_enqueue_style( 'swiper-css', get_theme_file_uri( '/assets/css/swiper-bundle.min.css' ) ,array(), '4.0.13' );
    wp_enqueue_style( 'tourm-main-style', get_theme_file_uri('/assets/css/style.css') ,array(), wp_get_theme()->get( 'Version' ) );


    // Load Js

    wp_enqueue_script( 'bootstrap', get_theme_file_uri( '/assets/js/bootstrap.min.js' ), array( 'jquery' ), '5.0.0', true );
    wp_enqueue_script( 'swiper-js', get_theme_file_uri( '/assets/js/swiper-bundle.min.js' ), array('jquery'), '1.0.0', true );
    wp_enqueue_script( 'magnific-popup', get_theme_file_uri( '/assets/js/jquery.magnific-popup.min.js' ), array('jquery'), '1.0.0', true );
    wp_enqueue_script( 'isototpe-pkgd', get_theme_file_uri( '/assets/js/isotope.pkgd.min.js' ), array( 'jquery' ), '1.0.0', true );
    wp_enqueue_script( 'imagesloaded' ); 
    wp_enqueue_script( 'gsap', get_theme_file_uri( '/assets/js/gsap.min.js' ), array( 'jquery' ), '3.4.0', true );
    wp_enqueue_script( 'circle-progress', get_theme_file_uri( '/assets/js/circle-progress.js' ), array( 'jquery' ), '1.2.2', true );
    wp_enqueue_script( 'counterup', get_theme_file_uri( '/assets/js/jquery.counterup.min.js' ), array( 'jquery' ), '1.0.0', true );
    wp_enqueue_script( 'tweenmax', get_theme_file_uri( '/assets/js/tweenmax.min.js' ), array( 'jquery' ), '1.0.0', true );
    // wp_enqueue_script( 'matter', get_theme_file_uri( '/assets/js/matter.min.js' ), array( 'jquery' ), '1.0.0', true );

    // wp_enqueue_script('matter-js', 'https://cdnjs.cloudflare.com/ajax/libs/matter-js/0.17.1/matter.min.js', array(), '0.17.1', true);
    // wp_enqueue_script( 'matterjs-custom', get_theme_file_uri( '/assets/js/matterjs-custom.js' ), array( 'jquery' ), '1.0.0', true );
    // wp_enqueue_script( 'matter-intigration', get_theme_file_uri( '/assets/js/matter-intigration.js' ), array( 'jquery' ), '1.0.0', true );
    wp_enqueue_script( 'tourm-main-script', get_theme_file_uri( '/assets/js/main.js' ), array('jquery'), wp_get_theme()->get( 'Version' ), true );

    // comment reply
    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
        wp_enqueue_script( 'comment-reply' );
    }
}
add_action( 'wp_enqueue_scripts', 'tourm_essential_scripts',99 );


function tourm_block_editor_assets( ) {
    // Add custom fonts.
    wp_enqueue_style( 'tourm-editor-fonts', tourm_google_fonts(), array(), null );
}

add_action( 'enqueue_block_editor_assets', 'tourm_block_editor_assets' );

/*
Register Fonts
*/
function tourm_google_fonts() {
    $font_url = '';
    
    /*
    Translators: If there are characters in your language that are not supported
    by chosen font(s), translate this to 'off'. Do not translate into your own language.
     */
     
    if ( 'off' !== _x( 'on', 'Google font: on or off', 'tourm' ) ) {
        $font_url =  'https://fonts.googleapis.com/css2?family=Inter:wght@100..900&family=Manrope:wght@200..800&family=Montez&display=swap';
    }
    return $font_url;
}