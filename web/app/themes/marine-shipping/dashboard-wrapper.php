<?php
/*
Template Name: لوحة التحكم
*/

if (!is_user_logged_in()) {
    wp_redirect(wp_login_url(get_permalink()));
    exit;
}

$current_user = wp_get_current_user();

// Enqueue Font Awesome and styles
wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css');
wp_enqueue_style('noto-sans-arabic', 'https://fonts.googleapis.com/css2?family=Noto+Sans+Arabic:wght@400;600;700&display=swap', [], null);

get_header();
?>

<style>
    :root {
        --primary: #3498db;
        --primary-dark: #2980b9;
        --secondary: #2c3e50;
        --accent: #e74c3c;
        --light: #f8f9fa;
        --dark: #343a40;
        --success: #27ae60;
        --warning: #f39c12;
        --gray: #7f8c8d;
        --light-gray: #ecf0f1;
        --sidebar-width: 280px;
        --sidebar-collapsed: 80px;
        --transition: all 0.3s ease;
    }
    
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Noto Sans Arabic', 'Segoe UI', Tahoma, sans-serif;
    }
    
    body {
        background: linear-gradient(135deg, #f5f7fa 0%, #e4e7eb 100%);
        min-height: 100vh;
        color: var(--secondary);
    }
    
    .dashboard-layout {
        display: flex;
        /* min-height: 100vh; */
        direction: rtl;
        background: transparent;
    }
    
    /* ---------------------------- */
    /* الشريط الجانبي المحسّن */
    /* ---------------------------- */
    .dashboard-sidebar {
        width: var(--sidebar-width);
        background: linear-gradient(180deg, #2c3e50 0%, #1a2a3a 100%);
        box-shadow: 0 0 30px rgba(0, 0, 0, 0.3);
        padding: 20px 0;
        position: fixed;
        top: 0;
        right: 0;
        height: 100%;
        overflow-y: auto;
        transition: var(--transition);
        z-index: 1000;
        display: flex;
        flex-direction: column;
    }
    
    .sidebar-header {
        padding: 0 20px 20px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        margin-bottom: 20px;
    }
    
    .dashboard-logo {
        text-align: center;
        margin-bottom: 15px;
        transition: var(--transition);
    }
    
    .dashboard-logo img {
        max-width: 120px;
        border-radius: 10px;
        transition: var(--transition);
        filter: drop-shadow(0 2px 5px rgba(0,0,0,0.3));
    }
    
    .dashboard-logo h2 {
        color: white;
        font-size: 20px;
        margin-top: 10px;
        font-weight: 600;
    }
    
    .user-info {
        display: flex;
        align-items: center;
        margin-bottom: 30px;
        padding: 15px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 12px;
        position: relative;
        overflow: hidden;
        transition: var(--transition);
    }
    
    .user-info::before {
        content: "";
        position: absolute;
        top: 0;
        right: 0;
        width: 4px;
        height: 100%;
        background: var(--primary);
    }
    
    .user-info:hover {
        background: rgba(255, 255, 255, 0.15);
        transform: translateX(-5px);
    }
    
    .user-avatar {
        width: 50px;
        height: 50px;
        background: var(--primary);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        font-size: 20px;
        font-weight: 600;
        margin-left: 15px;
        flex-shrink: 0;
        box-shadow: 0 3px 10px rgba(0,0,0,0.2);
    }
    
    .user-info-content {
        flex: 1;
    }
    
    .user-info h3 {
        margin: 0;
        font-size: 18px;
        color: white;
        font-weight: 600;
        margin-bottom: 5px;
    }
    
    .user-info p {
        margin: 0;
        font-size: 14px;
        color: rgba(255, 255, 255, 0.7);
    }
    
    .user-status {
        display: flex;
        align-items: center;
        font-size: 12px;
        color: var(--success);
        margin-top: 3px;
    }
    
    .user-status::before {
        content: "";
        width: 8px;
        height: 8px;
        background: var(--success);
        border-radius: 50%;
        margin-left: 5px;
    }
    
    .dashboard-nav {
        display: flex;
        flex-direction: column;
        gap: 8px;
        padding: 0 15px;
        flex: 1;
    }
    
    .dashboard-nav a {
        display: flex;
        align-items: center;
        padding: 15px 20px;
        color: rgba(255, 255, 255, 0.8);
        text-decoration: none;
        font-size: 16px;
        border-radius: 8px;
        transition: var(--transition);
        position: relative;
        overflow: hidden;
    }
    
    .dashboard-nav a:hover {
        background: rgba(255, 255, 255, 0.1);
        color: white;
        transform: translateX(-5px);
    }
    
    .dashboard-nav a:hover::before {
        content: "";
        position: absolute;
        top: 0;
        right: 0;
        width: 4px;
        height: 100%;
        background: var(--primary);
    }
    
    .dashboard-nav a.active {
        background: rgba(52, 152, 219, 0.2);
        color: white;
        box-shadow: 0 0 15px rgba(52, 152, 219, 0.2);
    }
    
    .dashboard-nav a.active::before {
        content: "";
        position: absolute;
        top: 0;
        right: 0;
        width: 4px;
        height: 100%;
        background: var(--primary);
    }
    
    .dashboard-nav a span {
        margin-left: 15px;
        font-size: 18px;
        width: 24px;
        text-align: center;
    }
    
    .dashboard-nav a .nav-text {
        flex: 1;
    }
    
    .dashboard-nav a .notification-badge {
        background: var(--accent);
        color: white;
        font-size: 12px;
        width: 22px;
        height: 22px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-left: 10px;
    }
    
    .nav-divider {
        height: 1px;
        background: rgba(255, 255, 255, 0.1);
        margin: 15px 0;
    }
    
    .sidebar-footer {
        padding: 20px 20px 0;
        border-top: 1px solid rgba(255, 255, 255, 0.1);
    }
    
    .logout-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 12px 20px;
        background: rgba(231, 76, 60, 0.2);
        color: rgba(255, 255, 255, 0.8) !important;
        border-radius: 8px;
        font-weight: 600;
        transition: var(--transition);
    }
    
    .logout-btn:hover {
        background: rgba(231, 76, 60, 0.3);
        color: white !important;
        transform: translateX(0);
    }
    
    .collapse-toggle {
        position: absolute;
        top: 20px;
        left: -15px;
        width: 30px;
        height: 30px;
        background: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 3px 10px rgba(0,0,0,0.2);
        cursor: pointer;
        color: var(--primary);
        font-size: 14px;
        transition: var(--transition);
        z-index: 10;
    }
    
    .collapse-toggle:hover {
        transform: translateX(-3px);
    }
    
    /* حالة الشريط الجانبي المطوي */
    .sidebar-collapsed .dashboard-sidebar {
        width: var(--sidebar-collapsed);
    }
    
    .sidebar-collapsed .dashboard-logo img {
        max-width: 50px;
    }
    
    .sidebar-collapsed .dashboard-logo h2,
    .sidebar-collapsed .user-info-content,
    .sidebar-collapsed .nav-text,
    .sidebar-collapsed .notification-badge {
        display: none;
    }
    
    .sidebar-collapsed .dashboard-logo {
        padding: 10px;
    }
    
    .sidebar-collapsed .user-info {
        padding: 10px;
        justify-content: center;
    }
    
    .sidebar-collapsed .dashboard-nav a {
        justify-content: center;
        padding: 15px 10px;
    }
    
    .sidebar-collapsed .collapse-toggle {
        transform: rotate(180deg);
    }
    
    .sidebar-collapsed .collapse-toggle:hover {
        transform: rotate(180deg) translateX(-3px);
    }
    
    /* زر القائمة للجوال */
    .mobile-menu-toggle {
        display: none;
        position: fixed;
        top: 20px;
        right: 20px;
        background: var(--primary);
        color: white;
        border: none;
        padding: 12px 16px;
        font-size: 24px;
        border-radius: 8px;
        cursor: pointer;
        z-index: 1100;
        transition: var(--transition);
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }
    
    .mobile-menu-toggle:hover {
        background: var(--primary-dark);
        transform: scale(1.05);
    }
    
    /* المحتوى الرئيسي */
    .dashboard-content {
        margin-right: var(--sidebar-width);
        padding: 30px;
        width: 100%;
        transition: var(--transition);
        min-height: 100vh;
    }
    
    .sidebar-collapsed .dashboard-content {
        margin-right: var(--sidebar-collapsed);
    }
    
    .content-header {
        background: white;
        border-radius: 15px;
        padding: 20px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        margin-bottom: 30px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    
    .content-header h1 {
        color: var(--secondary);
        font-size: 28px;
        margin: 0;
    }
    
    /* تجاوبية */
    @media (max-width: 992px) {
        .dashboard-sidebar {
            transform: translateX(100%);
        }
        
        .dashboard-sidebar.active {
            transform: translateX(0);
        }
        
        .dashboard-content {
            margin-right: 0;
        }
        
        .mobile-menu-toggle {
            display: block;
        }
        
        .sidebar-collapsed .dashboard-sidebar {
            width: var(--sidebar-width);
        }
        
        .sidebar-collapsed .dashboard-logo img {
            max-width: 120px;
        }
        
        .sidebar-collapsed .dashboard-logo h2,
        .sidebar-collapsed .user-info-content,
        .sidebar-collapsed .nav-text,
        .sidebar-collapsed .notification-badge {
            display: block;
        }
        
        .sidebar-collapsed .dashboard-logo {
            padding: 0;
        }
        
        .sidebar-collapsed .user-info {
            padding: 15px;
            justify-content: flex-start;
        }
        
        .sidebar-collapsed .dashboard-nav a {
            justify-content: flex-start;
            padding: 15px 20px;
        }
        
        .sidebar-collapsed .collapse-toggle {
            transform: rotate(0);
        }
    }
    
    @media (max-width: 768px) {
        :root {
            --sidebar-width: 260px;
        }
        
        .dashboard-content {
            padding: 20px;
        }
        
        .content-header {
            flex-direction: column;
            align-items: flex-start;
        }
    }
    
    @media (max-width: 576px) {
        .dashboard-sidebar {
            width: 100%;
        }
        
        .dashboard-logo img {
            max-width: 100px;
        }
        
        .user-avatar {
            width: 40px;
            height: 40px;
            font-size: 16px;
        }
        
        .dashboard-nav a {
            padding: 12px 15px;
        }
    }
</style>

<div class="dashboard-layout">
    <button class="mobile-menu-toggle"><i class="fas fa-bars"></i></button>
    
    <aside class="dashboard-sidebar">
        <div class="collapse-toggle">
            <i class="fas fa-chevron-right"></i>
        </div>
        
        <div class="sidebar-header">
            <div class="dashboard-logo">
                <img src="https://via.placeholder.com/120x60/2c3e50/ffffff?text=LOGO" alt="لوحة التحكم">
                <h2>لوحة التحكم</h2>
            </div>
            
            <div class="user-info">
                <div class="user-avatar"><?php echo esc_html(substr($current_user->display_name, 0, 1)); ?></div>
                <div class="user-info-content">
                    <h3><?php echo esc_html($current_user->display_name); ?></h3>
                </div>
            </div>
        </div>
        
        <nav class="dashboard-nav">


          <?php
            wp_nav_menu([
                'theme_location' => 'dashboard-menu',
                'container' => false,
                'fallback_cb' => false,
            ]);
            ?>


    <!-- <a href="#" class="active">
            </a> -->
            <!-- <a href="#" class="active">
                <span class="fas fa-home"></span>
                <div class="nav-text">الرئيسية</div>
            </a>
            
            <a href="#">
                <span class="fas fa-shipping-fast"></span>
                <div class="nav-text">طلبات الشحن</div>
                <div class="notification-badge">3</div>
            </a>
            
            <a href="#">
                <span class="fas fa-history"></span>
                <div class="nav-text">سجل الطلبات</div>
            </a>
            
            <a href="#">
                <span class="fas fa-file-invoice"></span>
                <div class="nav-text">الفواتير</div>
            </a>
            
            <a href="#">
                <span class="fas fa-chart-line"></span>
                <div class="nav-text">الإحصائيات</div>
            </a>
            
            <div class="nav-divider"></div>
            
            <a href="#">
                <span class="fas fa-cog"></span>
                <div class="nav-text">الإعدادات</div>
            </a>
            
            <a href="#">
                <span class="fas fa-user"></span>
                <div class="nav-text">الملف الشخصي</div>
            </a>
            
            <a href="#">
                <span class="fas fa-question-circle"></span>
                <div class="nav-text">الدعم الفني</div>
            </a>
            
            <div class="sidebar-footer">
                <a class="logout-btn" href="<?php echo esc_url(wp_logout_url(home_url())); ?>">
                    <span class="fas fa-sign-out-alt"></span>
                    <div class="nav-text">تسجيل الخروج</div>
                </a>
            </div> -->
        </nav>
    </aside>

    <!-- <main class="dashboard-content">
        <div class="content-header">
            <h1><i class="fas fa-home"></i> لوحة التحكم الرئيسية</h1>
            <div class="search-container">
                <input type="text" placeholder="بحث...">
                <button><i class="fas fa-search"></i></button>
            </div>
        </div>
        
        <div class="content-placeholder">
            <h2>مرحباً بك في لوحة التحكم</h2>
            <p>هذا هو محتوى لوحة التحكم الرئيسية. سيتم عرض الإحصائيات والبيانات هنا.</p>
            
            <div class="stats-container">
                <div class="stat-card">
                    <i class="fas fa-box"></i>
                    <h3>25</h3>
                    <p>طلبات الشحن</p>
                </div>
                
                <div class="stat-card">
                    <i class="fas fa-check-circle"></i>
                    <h3>18</h3>
                    <p>مكتملة</p>
                </div>
                
                <div class="stat-card">
                    <i class="fas fa-shipping-fast"></i>
                    <h3>7</h3>
                    <p>قيد التوصيل</p>
                </div>
                
                <div class="stat-card">
                    <i class="fas fa-dollar-sign"></i>
                    <h3>8,250</h3>
                    <p>إجمالي المصروفات</p>
                </div>
            </div>
        </div>
    </main> -->
<!-- </div> -->

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // زر القائمة للجوال
        const mobileToggle = document.querySelector('.mobile-menu-toggle');
        const sidebar = document.querySelector('.dashboard-sidebar');
        
        mobileToggle.addEventListener('click', function() {
            sidebar.classList.toggle('active');
        });
        
        // زر طي/فتح الشريط الجانبي
        const collapseToggle = document.querySelector('.collapse-toggle');
        const dashboardLayout = document.querySelector('.dashboard-layout');
        
        collapseToggle.addEventListener('click', function() {
            dashboardLayout.classList.toggle('sidebar-collapsed');
            
            // حفظ الحالة في التخزين المحلي
            if (dashboardLayout.classList.contains('sidebar-collapsed')) {
                localStorage.setItem('sidebarCollapsed', 'true');
            } else {
                localStorage.setItem('sidebarCollapsed', 'false');
            }
        });
        
        // استعادة حالة الشريط الجانبي من التخزين المحلي
        if (localStorage.getItem('sidebarCollapsed') === 'true') {
            dashboardLayout.classList.add('sidebar-collapsed');
        }
        
        // إغلاق الشريط الجانبي عند النقر خارجها على الجوال
        document.addEventListener('click', function(e) {
            if (window.innerWidth <= 992 && 
                !sidebar.contains(e.target) && 
                !mobileToggle.contains(e.target) &&
                sidebar.classList.contains('active')) {
                sidebar.classList.remove('active');
            }
        });
    });
</script>

<?php get_footer(); ?>