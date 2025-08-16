<?php

function register_country_cpt() {
    $labels = [
        'name'                  => _x('Countries', 'Post type general name', 'marine-shipping'),
        'singular_name'         => _x('Country', 'Post type singular name', 'marine-shipping'),
        'menu_name'             => _x('Countries', 'Admin Menu text', 'marine-shipping'),
        'name_admin_bar'        => _x('Country', 'Add New on Toolbar', 'marine-shipping'),
        'add_new'               => __('Add New', 'marine-shipping'),
        'add_new_item'          => __('Add New Country', 'marine-shipping'),
        'new_item'              => __('New Country', 'marine-shipping'),
        'edit_item'             => __('Edit Country', 'marine-shipping'),
        'view_item'             => __('View Country', 'marine-shipping'),
        'all_items'             => __('All Countries', 'marine-shipping'),
        'search_items'          => __('Search Countries', 'marine-shipping'),
        'parent_item_colon'     => __('Parent Countries:', 'marine-shipping'),
        'not_found'             => __('No countries found.', 'marine-shipping'),
        'not_found_in_trash'    => __('No countries found in Trash.', 'marine-shipping'),
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
