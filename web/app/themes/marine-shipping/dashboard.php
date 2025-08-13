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

// تحميل ملف التنسيقات
wp_enqueue_style('dashboard-styles', get_template_directory_uri() . '/dashboard-styles.css');
?>

<div class="dashboard-container">
    <button class="mobile-menu-toggle">☰</button>
    
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
        
        <div class="stats-grid">
            <div class="stat-card">
                <h3>المشاركات</h3>
                <p>24</p>
                <p class="stat-desc">زيادة 15% عن الشهر الماضي</p>
            </div>
            
            <div class="stat-card">
                <h3>التعليقات</h3>
                <p>18</p>
                <p class="stat-desc">زيادة 8% عن الشهر الماضي</p>
            </div>
            
            <div class="stat-card">
                <h3>المشاهدات</h3>
                <p>1,245</p>
                <p class="stat-desc">زيادة 32% عن الشهر الماضي</p>
            </div>
        </div>
        
        <div class="recent-activities">
            <h3>آخر الأنشطة</h3>
            <ul class="activity-list">
                <li class="activity-item">
                    <div class="activity-icon">📝</div>
                    <div class="activity-content">
                        <div class="activity-title">تم إنشاء مشاركة جديدة</div>
                        <div class="activity-time">منذ ساعتين</div>
                    </div>
                </li>
                <li class="activity-item">
                    <div class="activity-icon">💬</div>
                    <div class="activity-content">
                        <div class="activity-title">تمت الموافقة على تعليق</div>
                        <div class="activity-time">منذ 5 ساعات</div>
                    </div>
                </li>
                <li class="activity-item">
                    <div class="activity-icon">🔒</div>
                    <div class="activity-content">
                        <div class="activity-title">تم تحديث كلمة المرور</div>
                        <div class="activity-time">منذ يوم واحد</div>
                    </div>
                </li>
                <li class="activity-item">
                    <div class="activity-icon">👥</div>
                    <div class="activity-content">
                        <div class="activity-title">تم تحديث الملف الشخصي</div>
                        <div class="activity-time">منذ 3 أيام</div>
                    </div>
                </li>
            </ul>
        </div>
    </main>
</div>