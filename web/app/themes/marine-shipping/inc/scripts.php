<?php

function enqueue_shipping_scripts() {
    wp_enqueue_script('shipping-ajax', get_stylesheet_directory_uri() . '/js/shipping-ajax.js', ['jquery'], '1.0', true);
    wp_localize_script('shipping-ajax', 'shipping_ajax_obj', [
        'ajax_url' => admin_url('admin-ajax.php'),
    ]);
}
add_action('wp_enqueue_scripts', 'enqueue_shipping_scripts');
