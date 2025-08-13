<?php
/*
Template Name: لوحة المستخدم
*/

get_header();

if (!is_user_logged_in()) {
    wp_redirect(wp_login_url());
    exit;
}

$current_user = wp_get_current_user();

?>

<div class="dashboard-container">
    <aside class="dashboard-sidebar">
        <h3>
            <div class="user-avatar"><?php echo substr($current_user->display_name, 0, 1); ?></div>
            <?php echo esc_html($current_user->display_name); ?>
        </h3>
        
        
        <nav class="dashboard-nav">
            <?php
            wp_nav_menu([
                'theme_location' => 'dashboard-menu',
                'menu_class' => '',
                'container' => false,
                'items_wrap' => '%3$s',
                'fallback_cb' => false,
            ]);
            ?>

            <a class="logout-btn" href="<?php echo wp_logout_url(home_url()); ?>">
                <span>🚪</span> تسجيل الخروج
            </a>
        </nav>
    </aside>

    <main class="dashboard-content">
        <div class="dashboard-welcome">
            <h2>مرحباً بك في لوحة التحكم</h2>
            <p>يمكنك إدارة حسابك ومحتوياتك من خلال القائمة الجانبية</p>
        </div>
        
        <div class="dashboard-stats">
        </div>
    </main>
</div>

