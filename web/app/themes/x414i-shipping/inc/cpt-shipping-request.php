<?php

function register_shipping_request_cpt() {
    $labels = [
        'name'                  => _x('Shipping Requests', 'Post type general name', 'x414i-shipping'),
        'singular_name'         => _x('Shipping Request', 'Post type singular name', 'x414i-shipping'),
        'menu_name'             => _x('Shipping Requests', 'Admin Menu text', 'x414i-shipping'),
        'name_admin_bar'        => _x('Shipping Request', 'Add New on Toolbar', 'x414i-shipping'),
        'add_new'               => __('Add New', 'x414i-shipping'),
        'add_new_item'          => __('Add New Shipping Request', 'x414i-shipping'),
        'new_item'              => __('New Shipping Request', 'x414i-shipping'),
        'edit_item'             => __('Edit Shipping Request', 'x414i-shipping'),
        'view_item'             => __('View Shipping Request', 'x414i-shipping'),
        'all_items'             => __('All Shipping Requests', 'x414i-shipping'),
        'search_items'          => __('Search Shipping Requests', 'x414i-shipping'),
        'parent_item_colon'     => __('Parent Shipping Requests:', 'x414i-shipping'),
        'not_found'             => __('No shipping requests found.', 'x414i-shipping'),
        'not_found_in_trash'    => __('No shipping requests found in Trash.', 'x414i-shipping'),
        'featured_image'        => _x('Shipping Request Image', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', 'x414i-shipping'),
        'set_featured_image'    => _x('Set shipping request image', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', 'x414i-shipping'),
        'remove_featured_image' => _x('Remove shipping request image', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', 'x414i-shipping'),
        'use_featured_image'    => _x('Use as shipping request image', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', 'x414i-shipping'),
        'archives'              => _x('Shipping Request archives', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', 'x414i-shipping'),
        'insert_into_item'      => _x('Insert into shipping request', 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4', 'x414i-shipping'),
        'uploaded_to_this_item' => _x('Uploaded to this shipping request', 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', 'x414i-shipping'),
        'filter_items_list'     => _x('Filter shipping requests list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', 'x414i-shipping'),
        'items_list_navigation' => _x('Shipping Requests list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4', 'x414i-shipping'),
        'items_list'            => _x('Shipping Requests list', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4', 'x414i-shipping'),
    ];

    $args = [
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => ['slug' => 'shipping-request'],
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => null,
        'supports'           => ['title', 'editor'],
        'show_in_rest'       => true,
        'menu_icon'          => 'dashicons-archive',
    ];

    register_post_type('shipping_request', $args);
}
add_action('init', 'register_shipping_request_cpt');
