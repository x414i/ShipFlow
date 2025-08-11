<?php

function register_country_cpt() {
    register_post_type('country', [
        'labels' => [
            'name' => 'الدول',
            'singular_name' => 'دولة',
        ],
        'public' => true,
        'has_archive' => false,
        'menu_icon' => 'dashicons-flag',
        'supports' => ['title', 'custom-fields'],
        'show_in_rest' => true,
    ]);
}
add_action('init', 'register_country_cpt');
