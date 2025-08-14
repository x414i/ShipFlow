<?php

/**
 * Marine Shipping Theme Functions
 *
 * @package MarineShipping
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
// Theme setup
function marine_shipping_setup() {
    // Load text domain for translations
    load_theme_textdomain('marine-shipping', get_template_directory() . '/languages');
    add_theme_support('
    title-tag'); // Enable dynamic title tag support
    add_theme_support('post-thumbnails'); // Enable featured images
    add_theme_support('custom-logo', array(
        'height' => 100,
        'width' => 100,
        'flex-height' => true,
        'flex-width' => true,
    )); // Enable custom logo support
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    )); // Enable HTML5 support for various elements
    add_theme_support('customize-selective-refresh-widgets'); // Enable selective refresh for widgets
    register_nav_menus(array(
        'primary' => __('Primary Menu', 'marine-shipping'),
        'footer' => __('Footer Menu', 'marine-shipping'),
        'sidebar' => __('Sidebar Menu', 'dashboard-menu'),
    )); // Register navigation menus
}
add_action('after_setup_theme', 'marine_shipping_setup');
// Enqueue styles and scripts
function marine_shipping_enqueue_scripts() {
    // Enqueue main stylesheet
    wp_enqueue_style('marine-shipping-style', get_stylesheet_uri(), array(), wp_get_theme()->get('Version'), 'all');
    // Enqueue custom scripts
    wp_enqueue_script('marine-shipping-scripts', get_template_directory_uri() . '/js/scripts.js', array('jquery'), wp_get_theme()->get('Version'), true);
    // Localize script for AJAX
    wp_localize_script('marine-shipping-scripts', 'marineShipping', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('marine_shipping_nonce'),
    ));
}
add_action('wp_enqueue_scripts', 'marine_shipping_enqueue_scripts');
// Enqueue styles
add_action('wp_enqueue_scripts', 'marine_shipping_enqueue_styles');
function marine_shipping_enqueue_styles() {
    // Enqueue main stylesheet
    // if (is_rtl()) {
    //     wp_enqueue_style('marine-shipping-style-rtl', get_template_directory_uri() . '/rtl.css', array(), '1.0.0', 'all');
    // } else {
        wp_enqueue_style('marine-shipping-style', get_template_directory_uri() . '/style.css', array(), '1.0.0', 'all');
    // }
    // Enqueue Google Fonts
    wp_enqueue_style('marine-shipping-google-fonts', 'https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap', array(), null);
    // wp_enqueue_style('', marine_get_stylesheet_uri(), array(), '1.0.0', 'all');
    // Enqueue Font Awesome
    wp_enqueue_style('marine-shipping-font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css', array(), '5.15.3', 'all');
    // Enqueue custom styles
    wp_enqueue_style('marine-shipping-dashboard-style', get_template_directory_uri() . '/assets/css/dashboard-styles.css', array('marine-shipping-style'), '1.0.0', 'all');
    // Enqueue Bootstrap CSS
    wp_enqueue_style('marine-shipping-bootstrap', 'https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css', array(), '4.5.2', 'all');
    // Enqueue custom styles
}



require get_template_directory() . '/inc/auth-login-redurict.php';
require_once get_template_directory() . '/inc/cpt-shipping-request.php';
require_once get_template_directory() . '/inc/cpt-country.php';
require_once get_template_directory() . '/inc/meta-box-country.php';
require_once get_template_directory() . '/inc/meta-box-shipping-request.php';
require_once get_template_directory() . '/inc/ajax-handlers.php';
require_once get_template_directory() . '/inc/admin-pages.php';
require_once get_template_directory() . '/inc/custom-capabilities.php';
require_once get_template_directory() . '/inc/scripts.php';
require_once get_template_directory() . '/inc/columns.php';

function custom_hide_classic_editor_wrap() {
    ?>
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function () {
            var editorWrap = document.querySelector('.wp-editor-wrap');
            if (editorWrap) {
                editorWrap.style.display = 'none';
            }
        });
    </script>
    <?php
}
add_action('admin_footer', 'custom_hide_classic_editor_wrap');
add_action('admin_enqueue_scripts', function($hook) {
    global $post;
    if ($hook === 'post.php' || $hook === 'post-new.php') {
        if (isset($post) && $post->post_type === 'shipping_request') {
            echo '<style>
                #shipping_request_details label {display:block;font-weight:600;margin-bottom:6px;color:#0077b6;}
                #shipping_request_details input, 
                #shipping_request_details select, 
                #shipping_request_details textarea {
                    width:100%;padding:8px 12px;border-radius:8px;border:1px solid #ddd;margin-bottom:18px;font-size:15px;box-sizing:border-box;
                }
                #shipping_request_details textarea {min-height:70px;}
            </style>';
        }
    }
});
