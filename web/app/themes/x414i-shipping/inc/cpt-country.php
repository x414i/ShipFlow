<?php

function register_country_cpt() {
    $labels = [
        'name'                  => _x('Countries', 'Post type general name', 'x414i-shipping'),
        'singular_name'         => _x('Country', 'Post type singular name', 'x414i-shipping'),
        'menu_name'             => _x('Countries', 'Admin Menu text', 'x414i-shipping'),
        'name_admin_bar'        => _x('Country', 'Add New on Toolbar', 'x414i-shipping'),
        'add_new'               => __('Add New', 'x414i-shipping'),
        'add_new_item'          => __('Add New Country', 'x414i-shipping'),
        'new_item'              => __('New Country', 'x414i-shipping'),
        'edit_item'             => __('Edit Country', 'x414i-shipping'),
        'view_item'             => __('View Country', 'x414i-shipping'),
        'all_items'             => __('All Countries', 'x414i-shipping'),
        'search_items'          => __('Search Countries', 'x414i-shipping'),
        'parent_item_colon'     => __('Parent Countries:', 'x414i-shipping'),
        'not_found'             => __('No countries found.', 'x414i-shipping'),
        'not_found_in_trash'    => __('No countries found in Trash.', 'x414i-shipping'),
    ];

    $args = [
        'labels'             => $labels,
        'public'             => true,
        'has_archive'        => false,
        'menu_icon'          => 'dashicons-flag',
        'supports'           => ['title', 'custom-fields'],
        'show_in_rest'       => true,
        'rewrite'            => ['slug' => 'country'],
    ];

    register_post_type('country', $args);
}
add_action('init', 'register_country_cpt');
