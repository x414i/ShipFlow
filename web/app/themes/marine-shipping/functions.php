<?php

require_once get_template_directory() . '/inc/cpt-shipping-request.php';
require_once get_template_directory() . '/inc/cpt-country.php';
require_once get_template_directory() . '/inc/meta-box-country.php';
require_once get_template_directory() . '/inc/meta-box-shipping-request.php';
require_once get_template_directory() . '/inc/ajax-handlers.php';
require_once get_template_directory() . '/inc/admin-pages.php';
require_once get_template_directory() . '/inc/custom-capabilities.php';
require_once get_template_directory() . '/inc/scripts.php';
require_once get_template_directory() . '/inc/columns.php';

function marine_shipping_register_menus() {
    register_nav_menus([
        'primary' => __('القائمة الرئيسية', 'marine-shipping'),
        'footer'  => __('قائمة الفوتر', 'marine-shipping'),
    ]);
}
add_action('after_setup_theme', 'marine_shipping_register_menus');
