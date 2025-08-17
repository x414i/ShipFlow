<?php
function redirect_to_shop_after_login($user_login, $user) {
    wp_redirect(home_url('/dashboard')); 
    exit;
}
add_action('wp_login', 'redirect_to_shop_after_login', 10, 2);

function redirect_non_logged_users() {
    if ( ! is_user_logged_in() && ! is_page('wp-login.php') && ! is_page('my-account') && ! is_admin() ) {
        wp_redirect( wp_login_url() );
        exit;
    }
}
add_action( 'template_redirect', 'redirect_non_logged_users' );