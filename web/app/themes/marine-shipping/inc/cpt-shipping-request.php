<?php

function register_shipping_request_cpt() {
    register_post_type('shipping_request', [
        'labels' => [
            'name' => 'طلبات الشحن',
            'singular_name' => 'طلب شحن',
        ],
        'public' => true,
        'show_in_rest' => true,
        'supports' => ['title', 'editor'],
        'menu_icon' => 'dashicons-archive',
    ]);
}
add_action('init', 'register_shipping_request_cpt');
