<?php
/*
Template Name: لوحة التحكم
*/

if (!is_user_logged_in()) {
    wp_redirect(wp_login_url(get_permalink()));
    exit;
}

$current_user = wp_get_current_user();

// Enqueue Font Awesome and Google Fonts
wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css');
wp_enqueue_style('noto-sans-arabic', 'https://fonts.googleapis.com/css2?family=Noto+Sans+Arabic:wght@400;600;700&display=swap', [], null);

get_header();
?>


<div class="dashboard-container-1">
    <button class="mobile-toggle"><i class="fas fa-bars"></i></button>
    
    <div class="toggle-sidebar">
        <i class="fas fa-chevron-right"></i>
    </div>
    
    <aside class="sidebar">
        <div class="sidebar-header">
            <div class="sidebar-logo">
                <img src="<?php echo get_template_directory_uri() . '/assets/img/shipflow.png' ?>" alt="لوحة التحكم">
                <h2>لوحة التحكم</h2>
            </div>
        </div>
        
        <div class="user-profile">
            <div class="user-avatar">
                <!-- <img src="" alt=""> -->
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640">
    <!--!Font Awesome Free v7.0.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.-->
    <path d="M463 448.2C440.9 409.8 399.4 384 352 384L288 384C240.6 384 199.1 409.8 177 448.2C212.2 487.4 263.2 512 320 512C376.8 512 427.8 487.3 463 448.2zM64 320C64 178.6 178.6 64 320 64C461.4 64 576 178.6 576 320C576 461.4 461.4 576 320 576C178.6 576 64 461.4 64 320zM320 336C359.8 336 392 303.8 392 264C392 224.2 359.8 192 320 192C280.2 192 248 224.2 248 264C248 303.8 280.2 336 320 336z"/>
</svg>
                <?php //echo esc_html(substr($current_user->display_name, 0, 1)); ?>
            </div>
            <div class="user-details">
                <h3><?php echo esc_html($current_user->display_name); ?></h3>
                <p><?php echo esc_html($current_user->user_email); ?></p>
            </div>
        </div>
        
        <nav class="sidebar-nav">
            <?php
            wp_nav_menu([
                'theme_location' => 'sidebar',
                'container' => false,
                'menu_class' => 'sidebar-menu',
                'fallback_cb' => false,
                'link_after' => '</span>',
            ]);
            ?>
            <div class="sidebar-footer">
                <a href="<?php echo wp_logout_url(home_url()); ?>" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i>
                    <span class="nav-text">تسجيل الخروج</span>
                </a>
            </div>
        </nav>
    </aside>
    <main class="main-content" id="mainContent">
        <!-- محتوى الصفحة سيوضع هنا -->
    </main>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const mobileToggle = document.querySelector('.mobile-toggle');
    const sidebar = document.querySelector('.sidebar');
    const toggleSidebar = document.querySelector('.toggle-sidebar');
    const dashboardContainer = document.querySelector('.dashboard-container-1');
    const mainContent = document.getElementById('mainContent');
    const body = document.body;

    // Mobile menu toggle
    mobileToggle.addEventListener('click', function() {
        sidebar.classList.toggle('active');
        body.classList.toggle('no-scroll'); 
        
    });

    // Sidebar collapse toggle for desktop
    toggleSidebar.addEventListener('click', function() {
        body.classList.toggle('sidebar-collapsed');
        localStorage.setItem('sidebarCollapsed', body.classList.contains('sidebar-collapsed'));
    });

    // Restore sidebar state on page load
    if (localStorage.getItem('sidebarCollapsed') === 'true') {
        body.classList.add('sidebar-collapsed');
    }

    // Close sidebar on click outside (mobile)
    document.addEventListener('click', function(e) {
        if (window.innerWidth <= 992 && 
            sidebar.classList.contains('active') &&
            !sidebar.contains(e.target) && 
            !mobileToggle.contains(e.target)) {
            sidebar.classList.remove('active');
            body.classList.remove('no-scroll');
        }
    });
});
</script>

<?php get_footer(); ?>
