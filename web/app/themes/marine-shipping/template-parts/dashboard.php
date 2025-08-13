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
<style>
    :root {

}

</style>
<div class="dashboard-container">


    <main class="dashboard-content">
        <div class="dashboard-welcome">
            <h2>مرحباً بك في لوحة التحكم</h2>
            <p>إحصاءات شاملة لشحناتك حسب الأنواع المختلفة</p>
        </div>
        
        <div class="stats-grid">
            <div class="stat-card">
                <h3>إجمالي الشحنات</h3>
                <p>142</p>
                <p class="stat-desc">زيادة 15% عن الشهر الماضي</p>
            </div>
            
            <div class="stat-card">
                <h3>الشحنات المكتملة</h3>
                <p>118</p>
                <p class="stat-desc">معدل إنجاز 83%</p>
            </div>
            
            <div class="stat-card">
                <h3>قيد التوصيل</h3>
                <p>24</p>
                <p class="stat-desc">متوسط وقت التوصيل: 3 أيام</p>
            </div>
        </div>
        
        <div class="shipment-stats">
            <!-- الشحن البري -->
            <div class="shipment-type ground">
                <div class="shipment-header">
                    <h3 class="shipment-title">الشحن البري</h3>
                    <div class="shipment-icon">🚚</div>
                </div>
                
                <div class="shipment-stats-grid">
                    <div class="stat-item">
                        <div class="stat-label">عدد الشحنات</div>
                        <div class="stat-value">68</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-label">متوسط التكلفة</div>
                        <div class="stat-value">250 ر.س</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-label">مكتملة</div>
                        <div class="stat-value">56</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-label">قيد التوصيل</div>
                        <div class="stat-value">12</div>
                    </div>
                </div>
                
                <div class="progress-container">
                    <div class="progress-label">
                        <span>معدل الإنجاز</span>
                        <span>82%</span>
                    </div>
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: 82%"></div>
                    </div>
                </div>
            </div>
            
            <!-- الشحن البحري -->
            <div class="shipment-type sea">
                <div class="shipment-header">
                    <h3 class="shipment-title">الشحن البحري</h3>
                    <div class="shipment-icon">🚢</div>
                </div>
                
                <div class="shipment-stats-grid">
                    <div class="stat-item">
                        <div class="stat-label">عدد الشحنات</div>
                        <div class="stat-value">42</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-label">متوسط التكلفة</div>
                        <div class="stat-value">180 ر.س</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-label">مكتملة</div>
                        <div class="stat-value">35</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-label">قيد التوصيل</div>
                        <div class="stat-value">7</div>
                    </div>
                </div>
                
                <div class="progress-container">
                    <div class="progress-label">
                        <span>معدل الإنجاز</span>
                        <span>83%</span>
                    </div>
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: 83%"></div>
                    </div>
                </div>
            </div>
            
            <!-- الشحن الجوي -->
            <div class="shipment-type air">
                <div class="shipment-header">
                    <h3 class="shipment-title">الشحن الجوي</h3>
                    <div class="shipment-icon">✈️</div>
                </div>
                
                <div class="shipment-stats-grid">
                    <div class="stat-item">
                        <div class="stat-label">عدد الشحنات</div>
                        <div class="stat-value">18</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-label">متوسط التكلفة</div>
                        <div class="stat-value">450 ر.س</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-label">مكتملة</div>
                        <div class="stat-value">15</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-label">قيد التوصيل</div>
                        <div class="stat-value">3</div>
                    </div>
                </div>
                
                <div class="progress-container">
                    <div class="progress-label">
                        <span>معدل الإنجاز</span>
                        <span>83%</span>
                    </div>
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: 83%"></div>
                    </div>
                </div>
            </div>
            
            <!-- الشحن السريع -->
            <div class="shipment-type express">
                <div class="shipment-header">
                    <h3 class="shipment-title">الشحن السريع</h3>
                    <div class="shipment-icon">⚡</div>
                </div>
                
                <div class="shipment-stats-grid">
                    <div class="stat-item">
                        <div class="stat-label">عدد الشحنات</div>
                        <div class="stat-value">14</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-label">متوسط التكلفة</div>
                        <div class="stat-value">320 ر.س</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-label">مكتملة</div>
                        <div class="stat-value">12</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-label">قيد التوصيل</div>
                        <div class="stat-value">2</div>
                    </div>
                </div>
                
                <div class="progress-container">
                    <div class="progress-label">
                        <span>معدل الإنجاز</span>
                        <span>86%</span>
                    </div>
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: 86%"></div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="recent-activities">
            <h3>آخر الشحنات المضافة</h3>
            <ul class="activity-list">
                <li class="activity-item">
                    <div class="activity-icon">📦</div>
                    <div class="activity-content">
                        <div class="activity-title">شحنة #SH-2451 - جدة إلى الرياض</div>
                        <div class="activity-time">نوع الشحن: جوي - الحالة: قيد التوصيل</div>
                    </div>
                    <div class="activity-time">منذ 2 ساعة</div>
                </li>
                <li class="activity-item">
                    <div class="activity-icon">📦</div>
                    <div class="activity-content">
                        <div class="activity-title">شحنة #SH-2448 - الدمام إلى أبها</div>
                        <div class="activity-time">نوع الشحن: سريع - الحالة: تم التسليم</div>
                    </div>
                    <div class="activity-time">منذ 5 ساعات</div>
                </li>
                <li class="activity-item">
                    <div class="activity-icon">📦</div>
                    <div class="activity-content">
                        <div class="activity-title">شحنة #SH-2442 - الرياض إلى دبي</div>
                        <div class="activity-time">نوع الشحن: بحري - الحالة: في المستودع</div>
                    </div>
                    <div class="activity-time">منذ يوم واحد</div>
                </li>
            </ul>
        </div>
        
        <div class="shipment-chart-container">
            <h3>توزيع الشحنات خلال الشهر</h3>
            <div class="shipment-chart">
                <div class="chart-bar ground" style="height: 70%">
                    <div class="chart-bar-label">بري</div>
                </div>
                <div class="chart-bar sea" style="height: 40%">
                    <div class="chart-bar-label">بحري</div>
                </div>
                <div class="chart-bar air" style="height: 25%">
                    <div class="chart-bar-label">جوي</div>
                </div>
                <div class="chart-bar express" style="height: 20%">
                    <div class="chart-bar-label">سريع</div>
                </div>
            </div>
        </div>
    </main>
</div>

<?php get_footer(); ?>