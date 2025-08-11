<?php

function add_custom_capabilities() {
    $role = get_role('subscriber');

    if ($role) {
        $role->add_cap('read_shipping_requests');
    }
}
add_action('init', 'add_custom_capabilities');


function restrict_admin_area() {
    if (
        is_admin() && 
        !current_user_can('manage_options') && 
        !(defined('DOING_AJAX') && DOING_AJAX)
    ) {
        wp_redirect(home_url('/dashboard'));
        exit;
    }
}
add_action('admin_init', 'restrict_admin_area');


function custom_login_redirect($redirect_to, $request, $user) {
    if (!isset($user->roles)) {
        return $redirect_to;
    }

    if (in_array('administrator', $user->roles)) {
        return admin_url();
    }

    return home_url('/dashboard');
}
add_filter('login_redirect', 'custom_login_redirect', 10, 3);


function hide_admin_bar_for_users() {
    if (!current_user_can('manage_options')) {
        show_admin_bar(false);
    }
}
add_action('after_setup_theme', 'hide_admin_bar_for_users');
