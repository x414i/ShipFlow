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

<style>
    :root {
        --primary: #1a73e8;
        --primary-dark: #1557b0;
        --secondary: #2d3748;
        --accent: #e53e3e;
        --light: #f7fafc;
        --dark: #1a202c;
        --sidebar-width: 260px;
        --sidebar-collapsed: 70px;
        --transition: all 0.3s ease;
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Noto Sans Arabic', sans-serif;
    }

    body {
        background: var(--light);
        min-height: 100vh;
        color: var(--secondary);
        overflow-x: hidden;
    }

    .dashboard-container-1 {
        display: flex;
        direction: rtl;
        width: 90%;
        margin: 1rem auto;
    }

    .sidebar {
        width: var(--sidebar-width);
        background: var(--dark);
        color: white;
        position: fixed;
        top: 0;
        right: 0;
        height: 100%;
        overflow-y: auto;
        overflow-x: hidden;
        transition: var(--transition);
        z-index: 1000;
        display: flex;
        flex-direction: column;
    }
    ul {
        list-style: none;
    }

    .sidebar-header {
        padding-top: 10px;
        /* border-bottom: 1px solid rgba(255, 255, 255, 0.1); */
    }

    .sidebar-logo {
        text-align: center;
    }

    .sidebar-logo img {
    max-width: 80px;
    border-radius: 6px;
    background: #fff;
    padding: 10px;
    }

    .sidebar-logo h2 {
        font-size: 16px;
        margin-top: 10px;
        font-weight: 600;
    }

    .user-profile {
        display: flex;
        align-items: center;
        padding: 15px;
        margin: 15px;
        background: rgba(255, 255, 255, 0.05);
        border-radius: 6px;
    }

    .user-avatar {
        width: 40px;
        height: 40px;
        background: var(--primary);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        font-size: 16px;
        font-weight: 600;
        margin-left: 10px;
        flex-shrink: 0;
    }

    .user-details h3 {
        font-size: 15px;
        margin: 0;
    }

    .user-details p {
        font-size: 12px;
        color: rgba(255, 255, 255, 0.6);
        margin: 0;
    }

    .sidebar-nav {
        flex: 1;
        padding: 0 15px;
    }

    .sidebar-nav a {
        display: flex;
        align-items: center;
        padding: 12px 15px;
        color: rgba(255, 255, 255, 0.8);
        text-decoration: none;
        font-size: 14px;
        border-radius: 6px;
        margin-bottom: 5px;
        transition: var(--transition);
    }

    .sidebar-nav a:hover {
        background: rgba(255, 255, 255, 0.1);
        color: white;
    }

    .sidebar-nav a.active {
        background: var(--primary);
        color: white;
    }

    .sidebar-nav a i {
        margin-left: 10px;
        width: 20px;
        text-align: center;
    }

    .sidebar-footer {
        padding: 15px;
        border-top: 1px solid rgba(255, 255, 255, 0.1);
    }

    .logout-btn {
        display: flex;
        align-items: center;
        padding: 12px 0;
        /* background: rgba(229, 62, 62, 0.2); */
        color: white;
        text-decoration: none;
        border-radius: 6px;
        font-size: 14px;
        transition: var(--transition);
    }

    .logout-btn:hover {
        background: var(--accent);
    }

    .toggle-sidebar {
        position: fixed;
        top: 15px;
        right: calc(var(--sidebar-width) - 15px);
        width: 30px;
        height: 30px;
        background: var(--light);
        color: var(--primary);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        z-index: 1100;
        transition: var(--transition);
    }

    .main-content.sidebar-pushed {
        margin-right: var(--sidebar-width);
        transition: var(--transition);
    }

    .sidebar-collapsed .main-content.sidebar-pushed {
        margin-right: var(--sidebar-collapsed);
    }

    .toggle-sidebar:hover {
        background: var(--primary);
        color: white;
    }

    .mobile-toggle {
        display: none;
        position: fixed;
        top: 10px;
        right: 10px;
        background: var(--primary);
        color: white;
        border: none;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        font-size: 18px;
        cursor: pointer;
        z-index: 1200;
        transition: var(--transition);
    }

    .mobile-toggle:hover {
        background: var(--primary-dark);
    }

    .main-content {
        margin-right: var(--sidebar-width);
        padding: 20px;
        width: 100%;
        transition: var(--transition), transform 0.3s ease; /* إضافة transform للانتقال */
        transform: scale(1); /* الحجم الافتراضي */
    }

    .main-content.scaled {
        transform: scale(0.95); /* تصغير الصفحة عند فتح القائمة */
    }

    .content-header {
        background: white;
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 20px;
    }

    .content-header h1 {
        font-size: 22px;
        margin: 0;
        color: var(--secondary);
    }

    /* Collapsed Sidebar */
    .sidebar-collapsed .sidebar {
        width: var(--sidebar-collapsed);
    }

    .sidebar-collapsed .sidebar-logo h2,
    .sidebar-collapsed .user-details,
    .sidebar-collapsed .sidebar-nav a .nav-text {
        display: none;
    }

    .sidebar-collapsed .sidebar-logo img {
        max-width: 40px;
        display: none;
    }

    .sidebar-collapsed .user-profile {
        justify-content: center;
        padding: 10px;
        margin: 10px;
    }

    .sidebar-collapsed .sidebar-nav a {
        justify-content: center;
        padding: 10px;
    }

    .sidebar-collapsed .toggle-sidebar {
        right: calc(var(--sidebar-collapsed) - 15px);
        transform: rotate(180deg);
    }

    .sidebar-collapsed .main-content {
        margin-right: var(--sidebar-collapsed);
        transform: scale(1); /* إعادة الحجم الطبيعي عند الإغلاق */
    }

    /* Responsive Design */
    @media (max-width: 992px) {
        .sidebar {
            width: 260px;
            transform: translateX(100%);
        }

        .sidebar.active {
            transform: translateX(0);
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.3);
        }

        .main-content {
            margin-right: 0;
            transform: scale(1); /* الحجم الافتراضي في الشاشات الصغيرة */
        }

        .main-content.scaled {
            transform: scale(0.9); /* تصغير أكثر في الشاشات الصغيرة */
        }

        .mobile-toggle {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .toggle-sidebar {
            display: none;
        }
    }

    @media (max-width: 768px) {
        .sidebar {
            width: 240px;
        }

        .sidebar-logo img {
            max-width: 70px;
        }

        .content-header h1 {
            font-size: 20px;
        }
    }

    @media (max-width: 576px) {
        .sidebar {
            width: 100%;
        }

        .mobile-toggle {
            width: 36px;
            height: 36px;
            font-size: 16px;
        }

        .sidebar-nav a {
            font-size: 13px;
            padding: 10px;
        }

        .user-avatar {
            width: 36px;
            height: 36px;
            font-size: 14px;
        }

        .content-header h1 {
            font-size: 18px;
        }
    }

    @media (max-width: 992px) {
        .main-content.sidebar-pushed {
            transform: translateX(-260px); /* نفس عرض الـ sidebar */
            transition: var(--transition);
        }
    }

    /* Touch Optimizations */
    @media (hover: none) {
        .sidebar-nav a:hover,
        .logout-btn:hover,
        .toggle-sidebar:hover,
        .mobile-toggle:hover {
            background: none;
        }

        .sidebar-nav a:active,
        .logout-btn:active {
            background: rgba(255, 255, 255, 0.1);
        }

        .toggle-sidebar:active,
        .mobile-toggle:active {
            background: var(--primary-dark);
        }
    }
</style>

<div class="dashboard-container-1">
    <button class="mobile-toggle"><i class="fas fa-bars"></i></button>
    
    <div class="toggle-sidebar">
        <i class="fas fa-chevron-right"></i>
    </div>
    
    <aside class="sidebar">
        <div class="sidebar-header">
            <div class="sidebar-logo">
                <img src="<?php echo get_template_directory_uri() . '/assets/img/logo.png' ?>" alt="لوحة التحكم">
                <h2>لوحة التحكم</h2>
            </div>
        </div>
        
        <div class="user-profile">
            <div class="user-avatar"><?php echo esc_html(substr($current_user->display_name, 0, 1)); ?></div>
            <div class="user-details">
                <h3><?php echo esc_html($current_user->display_name); ?></h3>
                <p><?php echo esc_html($current_user->user_email); ?></p>
            </div>
        </div>
        
        <nav class="sidebar-nav">
            <?php
            wp_nav_menu([
                'theme_location' => 'dashboard-menu',
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
</div>
<main class="main-content" id="mainContent">
    <!-- محتوى الصفحة -->
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const mobileToggle = document.querySelector('.mobile-toggle');
    const sidebar = document.querySelector('.sidebar');
    const toggleSidebar = document.querySelector('.toggle-sidebar');
    const dashboardContainer = document.querySelector('.dashboard-container-1');
    const mainContent = document.getElementById('mainContent');

    // Mobile menu toggle
    mobileToggle.addEventListener('click', function() {
        sidebar.classList.toggle('active');
        mobileToggle.classList.toggle('active');
        if (mainContent) {
            mainContent.classList.toggle('sidebar-pushed');
            mainContent.classList.toggle('scaled'); // إضافة/إزالة كلاس scaled
        }
    });

    // Sidebar collapse toggle
    toggleSidebar.addEventListener('click', function() {
        dashboardContainer.classList.toggle('sidebar-collapsed');
        if (mainContent) {
            mainContent.classList.toggle('sidebar-pushed');
            mainContent.classList.toggle('scaled'); // إضافة/إزالة كلاس scaled
        }
        localStorage.setItem('sidebarCollapsed', dashboardContainer.classList.contains('sidebar-collapsed'));
    });

    // Restore sidebar state
    if (localStorage.getItem('sidebarCollapsed') === 'true') {
        dashboardContainer.classList.add('sidebar-collapsed');
        if (mainContent) {
            mainContent.classList.add('sidebar-pushed');
            mainContent.classList.remove('scaled'); // إزالة scaled عند تحميل الحالة المغلقة
        }
    }

    // Close sidebar on click outside (mobile)
    document.addEventListener('click', function(e) {
        if (window.innerWidth <= 992 && 
            !sidebar.contains(e.target) && 
            !mobileToggle.contains(e.target) && 
            sidebar.classList.contains('active')) {
            sidebar.classList.remove('active');
            mobileToggle.classList.remove('active');
            if (mainContent) {
                mainContent.classList.remove('sidebar-pushed');
                mainContent.classList.remove('scaled'); // إزالة scaled
            }
        }
    });

    // Swipe to close sidebar
    let startX = 0;
    sidebar.addEventListener('touchstart', function(e) {
        startX = e.touches[0].clientX;
    });

    sidebar.addEventListener('touchend', function(e) {
        const endX = e.changedTouches[0].clientX;
        if (startX - endX > 100) {
            sidebar.classList.remove('active');
            mobileToggle.classList.remove('active');
            if (mainContent) {
                mainContent.classList.remove('sidebar-pushed');
                mainContent.classList.remove('scaled'); // إزالة scaled
            }
        }
    });

    // Prevent horizontal scroll
    sidebar.addEventListener('wheel', function(e) {
        if (e.deltaX !== 0) {
            e.preventDefault();
        }
    });
});
</script>

<?php get_footer(); ?>