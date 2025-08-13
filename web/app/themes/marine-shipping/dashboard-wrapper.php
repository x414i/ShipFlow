<?php
if (!is_user_logged_in()) {
    wp_redirect(wp_login_url());
    exit;
}

$current_user = wp_get_current_user();
get_header();

// تحميل ملف التنسيقات
wp_enqueue_style('dashboard-styles', get_template_directory_uri() . '/dashboard-styles.css');
?>

<div class="dashboard-layout">
    <button class="mobile-menu-toggle">☰</button>
    
    <aside class="dashboard-sidebar">
        <div class="dashboard-logo">
            <img src="<?php echo get_template_directory_uri(); ?>/assets/img/logo.png" alt="لوحة التحكم">
        </div>
        
        <h3>
            <div class="user-avatar"><?php echo substr($current_user->display_name, 0, 1); ?></div>
            <?php echo esc_html($current_user->display_name); ?>
        </h3>
        
        <nav class="dashboard-nav">
            <?php
            wp_nav_menu([
                'theme_location' => 'dashboard-menu',
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