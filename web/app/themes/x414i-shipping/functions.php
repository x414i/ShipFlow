<?php

/**
 *  Shipping Theme Functions
 *
 * @package x414iShipping
 */
if (!defined('ABSPATH')) {
    exit; 
}
// Theme setup
function x414i_shipping_setup() {
    // Load text domain for translations
    load_theme_textdomain('x414i-shipping', get_template_directory() . '/languages');
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
        'primary' => __('Primary Menu', 'x414i-shipping'),
        'footer' => __('Footer Menu', 'x414i-shipping'),
        'sidebar' => __('Sidebar Menu', 'dashboard-menu'),
    )); // Register navigation menus
}
add_action('after_setup_theme', 'x414i_shipping_setup');

// Enqueue styles and scripts
function x414i_shipping_enqueue_assets() {
    // Register Font Awesome
    wp_register_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css', array(), '5.15.4', 'all');

    // Enqueue styles
    wp_enqueue_style('x414i-shipping-style', get_template_directory_uri() . '/style.css', array('font-awesome'), '1.0.0', 'all');
    wp_enqueue_style('x414i-shipping-dashboard-style', get_template_directory_uri() . '/assets/css/dashboard-styles.css', array('x414i-shipping-style'), '1.0.0', 'all');
    wp_enqueue_style('x414i-shipping-account-style', get_template_directory_uri() . '/assets/css/account-styles.css', array('x414i-shipping-style'), '1.0.0', 'all');
    wp_enqueue_style('x414i-culc-style', get_template_directory_uri() . '/assets/css/culc-styles.css', array('x414i-shipping-style'), '1.0.0', 'all');
    wp_enqueue_style('x414i-history-style', get_template_directory_uri() . '/assets/css/history-styles.css', array('x414i-shipping-style'), '1.0.0', 'all');
    wp_enqueue_style('x414i-price-style', get_template_directory_uri() . '/assets/css/prices-styles.css', array('x414i-shipping-style'), '1.0.0', 'all');
    wp_enqueue_style('x414i-request-shipping-style', get_template_directory_uri() . '/assets/css/request-shipping-styles.css', array('x414i-shipping-style'), '1.0.0', 'all');
    wp_enqueue_style('x414i-track-shipment-style', get_template_directory_uri() . '/assets/css/track-shipment.css', array('x414i-shipping-style'), '1.0.0', 'all');

    wp_enqueue_style('x414i-shipping-google-fonts', 'https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap', array(), null);
    wp_enqueue_style('x414i-shipping-bootstrap', 'https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css', array(), '4.5.2', 'all');

    // Enqueue custom scripts
    wp_enqueue_script('x414i-shipping-scripts', get_template_directory_uri() . '/assets/js/scripts.js', array('jquery'), '1.0.0', true);

    // Localize script for AJAX
    wp_localize_script('x414i-shipping-scripts', 'x414iShipping', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('x414i_shipping_nonce'),
    ));
}
add_action('wp_enqueue_scripts', 'x414i_shipping_enqueue_assets');

function x414i_shipping_admin_enqueue_assets($hook) {
    global $post;
    if ($hook === 'post.php' || $hook === 'post-new.php') {
        if (isset($post) && $post->post_type === 'shipping_request') {
            wp_enqueue_style('x414i-shipping-admin-style', get_template_directory_uri() . '/assets/css/admin-styles.css', array(), '1.0.0', 'all');
            wp_enqueue_script('x414i-shipping-admin-script', get_template_directory_uri() . '/assets/js/admin-scripts.js', array(), '1.0.0', true);
        }
    }
}
add_action('admin_enqueue_scripts', 'x414i_shipping_admin_enqueue_assets');

function remove_custom_fields_metabox() {
    remove_meta_box('postcustom', 'post', 'normal'); 
    remove_meta_box('postcustom', 'page', 'normal');

}
add_action('admin_menu', 'remove_custom_fields_metabox');


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
function get_translated_country_name($name) {
    if (get_locale() !== 'ar') {
        return $name;
    }

    $translations = [
        'USA' => 'الولايات المتحدة الأمريكية',
        'United Arab Emirates' => 'الامارات',
        'Saudi Arabia' => 'المملكة العربية السعودية',
        'Egypt' => 'مصر',
        'Jordan' => 'الأردن',
        'Turkey' => 'تركيا',
        'Tunisia' => 'تونس',
        'China' => 'الصين',
        'Dubai' => 'دبي',
        'India' => 'الهند',
        'Algeria' => 'الجزائر',
        'Morocco' => 'المغرب',
        'Italy' => 'ايطاليا',
        'France' => 'فرنسا',
        'Germany' => 'المانيا',
    ];

    return isset($translations[$name]) ? $translations[$name] : $name;
}
