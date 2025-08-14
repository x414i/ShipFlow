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
$user_id = $current_user->ID;

// تحميل ملف التنسيقات
wp_enqueue_style('dashboard-styles', get_template_directory_uri() . '/dashboard-styles.css');

// استرجاع إحصائيات الشحنات من قاعدة البيانات
$args = [
    'post_type' => 'shipping_request',
    'posts_per_page' => -1,
    'author' => $user_id,
    'post_status' => 'publish',
];

// إجمالي الشحنات
$total_shipments = count(get_posts($args));

// الشحنات المكتملة
$args_completed = array_merge($args, [
    'meta_query' => [
        [
            'key' => '_order_status',
            'value' => 'تم التسليم',
            'compare' => '='
        ]
    ]
]);
$completed_shipments = count(get_posts($args_completed));

// الشحنات قيد التوصيل
$args_in_transit = array_merge($args, [
    'meta_query' => [
        [
            'key' => '_order_status',
            'value' => 'جاري الشحن',
            'compare' => '='
        ]
    ]
]);
$in_transit = count(get_posts($args_in_transit));

// إحصائيات حسب نوع الشحن
$shipment_types = ['land' => 'بري', 'sea' => 'بحري', 'air' => 'جوي', 'fast' => 'سريع'];
$type_stats = [];

foreach ($shipment_types as $type_key => $type_label) {
    $args_type = array_merge($args, [
        'meta_query' => [
            [
                'key' => '_shipping_type',
                'value' => $type_key,
                'compare' => '='
            ]
        ]
    ]);

    $type_posts = get_posts($args_type);
    $total = count($type_posts);

    // الشحنات المكتملة لهذا النوع
    $args_type_completed = array_merge($args_type, [
        'meta_query' => [
            [
                'key' => '_shipping_type',
                'value' => $type_key,
                'compare' => '='
            ],
            [
                'key' => '_order_status',
                'value' => 'تم التسليم',
                'compare' => '='
            ]
        ]
    ]);
    $completed = count(get_posts($args_type_completed));

    // الشحنات قيد التوصيل لهذا النوع
    $args_type_in_transit = array_merge($args_type, [
        'meta_query' => [
            [
                'key' => '_shipping_type',
                'value' => $type_key,
                'compare' => '='
            ],
            [
                'key' => '_order_status',
                'value' => 'جاري الشحن',
                'compare' => '='
            ]
        ]
    ]);
    $in_transit_type = count(get_posts($args_type_in_transit));

    // متوسط التكلفة
    $total_cost = 0;
    foreach ($type_posts as $post) {
        $total_cost += floatval(get_post_meta($post->ID, '_total_price', true));
    }
    $avg_cost = $total > 0 ? $total_cost / $total : 0;

    $type_stats[$type_key] = [
        'total' => $total,
        'completed' => $completed,
        'in_transit' => $in_transit_type,
        'avg_cost' => $avg_cost
    ];
}

// آخر 3 شحنات
$recent_args = [
    'post_type' => 'shipping_request',
    'posts_per_page' => 3,
    'author' => $user_id,
    'orderby' => 'date',
    'order' => 'DESC',
    'post_status' => 'publish',
];
$recent_shipments = get_posts($recent_args);

// حساب النسب المئوية
$completion_rate = ($total_shipments > 0) ? round(($completed_shipments / $total_shipments) * 100) : 0;
$type_completion_rates = [];

foreach ($shipment_types as $type_key => $type_label) {
    $stats = $type_stats[$type_key];
    $type_completion_rates[$type_key] = ($stats['total'] > 0) ? round(($stats['completed'] / $stats['total']) * 100) : 0;
}
?>

<div class="dashboard-container">
    <main class="dashboard-content">
        <div class="dashboard-welcome">
            <h2>مرحباً بك في لوحة التحكم، <?php echo $current_user->display_name; ?></h2>
            <p>إحصاءات شاملة لشحناتك حسب الأنواع المختلفة</p>
        </div>
        
        <!-- <div class="stats-grid">
            <div class="stat-card">
                <h3>إجمالي الشحنات</h3>
                <p><?php echo $total_shipments; ?></p>
                <p class="stat-desc">جميع طلبات الشحن</p>
            </div>
            
            <div class="stat-card">
                <h3>الشحنات المكتملة</h3>
                <p><?php echo $completed_shipments; ?></p>
                <p class="stat-desc">معدل الإنجاز <?php echo $completion_rate; ?>%</p>
            </div>
            
            <div class="stat-card">
                <h3>جاري الشحن</h3>
                <p><?php echo $in_transit; ?></p>
                <p class="stat-desc">الشحنات قيد التوصيل</p>
            </div>
        </div> -->
        
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
                        <div class="stat-value"><?php echo $type_stats['land']['total']; ?></div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-label">متوسط التكلفة</div>
                        <div class="stat-value"><?php echo number_format($type_stats['land']['avg_cost'], 2); ?> $</div>
                    </div>
                    <!-- <div class="stat-item">
                        <div class="stat-label">مكتملة</div>
                        <div class="stat-value"><?php //echo $type_stats['land']['completed']; ?></div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-label">قيد التوصيل</div>
                        <div class="stat-value"><?php //echo $type_stats['land']['in_transit']; ?></div>
                    </div>-->
                </div>
                
             <!-- <div class="progress-container"> 
                    <div class="progress-label">
                        <span>معدل الإنجاز</span>
                        <span><?php //echo $type_completion_rates['land']; ?>%</span>
                    </div>
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: <?php //echo $type_completion_rates['land']; ?>%"></div>
                    </div>
                </div> -->
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
                        <div class="stat-value"><?php echo $type_stats['sea']['total']; ?></div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-label">متوسط التكلفة</div>
                        <div class="stat-value"><?php echo number_format($type_stats['sea']['avg_cost'], 2); ?> $</div>
                    </div>
                    <!-- <div class="stat-item">
                        <div class="stat-label">مكتملة</div>
                        <div class="stat-value"><?php //echo $type_stats['sea']['completed']; ?></div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-label">قيد التوصيل</div>
                        <div class="stat-value"><?php //echo $type_stats['sea']['in_transit']; ?></div>
                    </div>-->
                </div>
                
            <!--    <div class="progress-container">
                    <div class="progress-label">
                        <span>معدل الإنجاز</span>
                        <span><?php echo $type_completion_rates['sea']; ?>%</span>
                    </div>
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: <?php //echo $type_completion_rates['sea']; ?>%"></div>
                    </div>
                </div>-->
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
                        <div class="stat-value"><?php echo $type_stats['air']['total']; ?></div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-label">متوسط التكلفة</div>
                        <div class="stat-value"><?php echo number_format($type_stats['air']['avg_cost'], 2); ?> $</div>
                    </div>
                    <!-- <div class="stat-item">
                        <div class="stat-label">مكتملة</div>
                        <div class="stat-value"><?php //echo $type_stats['air']['completed']; ?></div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-label">قيد التوصيل</div>
                        <div class="stat-value"><?php //echo $type_stats['air']['in_transit']; ?></div>
                    </div> -->
                </div>
                
                <!-- <div class="progress-container">
                    <div class="progress-label">
                        <span>معدل الإنجاز</span>
                        <span><?php //echo $type_completion_rates['air']; ?>%</span>
                    </div>
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: <?php //echo $type_completion_rates['air']; ?>%"></div>
                    </div>
                </div> -->
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
                        <div class="stat-value"><?php echo $type_stats['fast']['total']; ?></div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-label">متوسط التكلفة</div>
                        <div class="stat-value"><?php echo number_format($type_stats['fast']['avg_cost'], 2); ?> $</div>
                    </div>
                    <!-- <div class="stat-item">
                        <div class="stat-label">مكتملة</div>
                        <div class="stat-value"><?php //echo $type_stats['fast']['completed']; ?></div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-label">قيد التوصيل</div>
                        <div class="stat-value"><?php //echo $type_stats['fast']['in_transit']; ?></div>
                    </div>-->
                </div>
                <!--
                <div class="progress-container">
                    <div class="progress-label">
                        <span>معدل الإنجاز</span>
                        <span><?php //echo $type_completion_rates['fast']; ?>%</span>
                    </div>
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: <?php //echo $type_completion_rates['fast']; ?>%"></div>
                    </div>
                </div> -->
            </div>
        </div>
        <?php if (!empty($recent_shipments)): ?>
        <div class="recent-activities">
            <h3>آخر الشحنات المضافة</h3>
            <ul class="activity-list">
                <?php foreach ($recent_shipments as $shipment):
                    $country_id = get_post_meta($shipment->ID, '_country_id', true);
                    $country_title = $country_id ? get_the_title($country_id) : '-';
                    $shipping_type_key = get_post_meta($shipment->ID, '_shipping_type', true);
                    $shipping_type_label = match ($shipping_type_key) {
                        'land' => 'بري',
                        'sea' => 'بحري',
                        'air' => 'جوي',
                        'fast' => 'سريع',
                        default => '-',
                    };
                    $order_status = get_post_meta($shipment->ID, '_order_status', true);
                ?>
                <li class="activity-item">
                    <div class="activity-icon">📦</div>
                    <div class="activity-content">
                        <div class="activity-title">شحنة #<?php echo $shipment->ID; ?> - <?php echo esc_html($shipment->post_title ?: '-'); ?> إلى <?php echo esc_html($country_title); ?></div>
                        <div class="activity-time">نوع الشحن: <?php echo $shipping_type_label; ?> - الحالة: <?php echo esc_html($order_status); ?></div>
                    </div>
                    <div class="activity-time"><?php echo human_time_diff(strtotime($shipment->post_date), time()); ?> منذ</div>
                </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php endif; ?>
    </main>
</div>

<?php get_footer(); ?>