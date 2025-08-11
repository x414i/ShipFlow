<?php
if (!is_user_logged_in()) {
    wp_redirect(wp_login_url());
    exit;
}

$current_user = wp_get_current_user();
get_header();
?>

<style>
.dashboard-layout {
    display: flex;
    min-height: 100vh;
    font-family: "Segoe UI", sans-serif;
}
.dashboard-sidebar {
    width: 250px;
    background-color: #f7f7f7;
    padding: 20px;
    border-right: 1px solid #ddd;
}
.dashboard-content {
    flex-grow: 1;
    padding: 40px;
}
.dashboard-sidebar h3 {
    margin-top: 0;
}
.dashboard-nav a {
    display: block;
    padding: 10px;
    margin: 5px 0;
    background: #fff;
    border: 1px solid #ddd;
    text-decoration: none;
    color: #333;
    border-radius: 4px;
    transition: 0.2s;
}
.dashboard-nav a:hover {
    background: #eaeaea;
}
.logout-btn {
    color: red;
    font-weight: bold;
}
</style>

<div class="dashboard-layout">

    <aside class="dashboard-sidebar">
        <h3>👤 <?php echo esc_html($current_user->display_name); ?></h3>
        <nav class="dashboard-nav">
            <?php
            wp_nav_menu([
                'theme_location' => 'dashboard-menu',
                'container' => false,
                'fallback_cb' => false,
            ]);
            ?>
            <a class="logout-btn" href="<?php echo wp_logout_url(home_url()); ?>">🚪 تسجيل الخروج</a>
        </nav>
    </aside>

    <main class="dashboard-content">
