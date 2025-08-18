<?php
function x414i_redirect_after_login($redirect_to, $request, $user) {
    if (isset($user->roles) && is_array($user->roles)) {
        return home_url('/dashboard');  
    }

    return $redirect_to;
}
add_filter('login_redirect', 'x414i_redirect_after_login', 10, 3);
