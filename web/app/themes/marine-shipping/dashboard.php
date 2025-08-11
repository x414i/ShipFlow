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

<style>
.dashboard-container {
    display: flex;
    min-height: 80vh;
    font-family: "Segoe UI", sans-serif;
}
.dashboard-sidebar {
    width: 250px;
    background-color: #f7f7f7;
    padding: 20px;
    border-right: 1px solid #ddd;
}
.dashboard-sidebar h3 {
    margin-top: 0;
}
.dashboard-content {
    flex-grow: 1;
    padding: 30px;
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

<div class="dashboard-container">

    <aside class="dashboard-sidebar">
        <h3>مرحبا، <?php echo esc_html($current_user->display_name); ?> 👋</h3>
        
        <nav class="dashboard-nav">
            <?php
            wp_nav_menu([
                'theme_location' => 'dashboard-menu',
                'menu_class' => '',
                'container' => false,
                'fallback_cb' => false,
            ]);
            ?>

            <a class="logout-btn" href="<?php echo wp_logout_url(home_url()); ?>">تسجيل الخروج</a>
        </nav>
    </aside>

    <main class="dashboard-content">
        <h2>لوحة التحكم</h2>
        <p>اختر من القائمة الجانبية للبدء.</p>
    </main>

</div>

<?php get_footer(); ?>
