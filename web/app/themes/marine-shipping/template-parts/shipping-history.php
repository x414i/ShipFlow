<?php
/*
Template Name: سجل الطلبات
*/

get_header();

if (!is_user_logged_in()) {
    echo '<div class="login-alert"><i class="fas fa-exclamation-circle"></i> يجب تسجيل الدخول لعرض سجل الطلبات.</div>';
    get_footer();
    exit;
}

$current_user_id = get_current_user_id();

// استلام قيم الفلاتر
$status_filter = $_GET['status'] ?? '';
$date_filter = $_GET['date'] ?? '';
$search_name = sanitize_text_field($_GET['shipment_name'] ?? '');
$country_filter = intval($_GET['country_id'] ?? 0);
$type_filter = sanitize_text_field($_GET['shipping_type'] ?? '');
$order_id_search = intval($_GET['order_id'] ?? 0);

// جلب قائمة الدول
$countries = get_posts([
    'post_type' => 'country',
    'posts_per_page' => -1,
    'orderby' => 'title',
    'order' => 'ASC',
]);

// بناء meta_query
$meta_query = [
    'relation' => 'AND',
];

if (!empty($status_filter)) {
    $meta_query[] = [
        'key' => '_order_status',
        'value' => $status_filter,
        'compare' => '='
    ];
}

if (!empty($type_filter)) {
    $meta_query[] = [
        'key' => '_shipping_type',
        'value' => $type_filter,
        'compare' => '='
    ];
}

if ($country_filter > 0) {
    $meta_query[] = [
        'key' => '_country_id',
        'value' => $country_filter,
        'compare' => '='
    ];
}

// التاريخ
$date_query = [];
if (!empty($date_filter)) {
    $timestamp = strtotime($date_filter);
    $date_query[] = [
        'year' => date('Y', $timestamp),
        'month' => date('m', $timestamp),
        'day' => date('d', $timestamp),
    ];
}

// Pagination
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

// بحث برقم الطلب
$args = [
    'post_type' => 'shipping_request',
    'posts_per_page' => 10, // تحديد 10 طلبات في كل صفحة
    'paged' => $paged,
    'author' => $current_user_id,
    'meta_query' => $meta_query,
    'date_query' => $date_query,
    'orderby' => 'date',
    'order' => 'DESC',
];

if (!empty($search_name)) {
    $args['s'] = $search_name;
}

if ($order_id_search > 0) {
    $shipping_requests_query = new WP_Query([
        'post_type' => 'shipping_request',
        'p' => $order_id_search,
        'author' => $current_user_id,
    ]);
} else {
    $shipping_requests_query = new WP_Query($args);
}

$shipping_requests = $shipping_requests_query->posts;
?>

    <main id="main-content" class="site-main">
<div class="shipping-history-container">
    <div class="history-header">
        <i class="fas fa-history"></i>
        <h2>سجل طلبات الشحن</h2>
    </div>
    
    <div class="filters-container">
        <form method="get" action="" style="width: 100%;">
            <div class="filters-grid">
                <div class="filter-group">
                    <label for="shipment_name">اسم الشحنة:</label>
                    <input type="text" name="shipment_name" id="shipment_name" class="filter-input" placeholder="بحث باسم الشحنة" value="<?php echo esc_attr($search_name); ?>">
                </div>
                
                <div class="filter-group">
                    <label for="order_id">رقم الطلب:</label>
                    <input type="number" name="order_id" id="order_id" class="filter-input" placeholder="بحث برقم الطلب" value="<?php echo esc_attr($order_id_search); ?>">
                </div>
                
                <div class="filter-group">
                    <label for="status">حالة الطلب:</label>
                    <select name="status" id="status" class="filter-input filter-select">
                        <option value="">جميع الحالات</option>
                        <option value="جديد" <?php selected($status_filter, 'جديد'); ?>>جديد</option>
                        <option value="قيد المراجعة" <?php selected($status_filter, 'قيد المراجعة'); ?>>قيد المراجعة</option>
                        <option value="جاري الشحن" <?php selected($status_filter, 'جاري الشحن'); ?>>جاري الشحن</option>
                        <option value="تم التسليم" <?php selected($status_filter, 'تم التسليم'); ?>>تم التسليم</option>
                    </select>
                </div>
                
                <div class="filter-group">
                    <label for="shipping_type">نوع الشحن:</label>
                    <select name="shipping_type" id="shipping_type" class="filter-input filter-select">
                        <option value="">جميع الأنواع</option>
                        <option value="land" <?php selected($type_filter, 'land'); ?>>بري</option>
                        <option value="sea" <?php selected($type_filter, 'sea'); ?>>بحري</option>
                        <option value="air" <?php selected($type_filter, 'air'); ?>>جوي</option>
                        <option value="fast" <?php selected($type_filter, 'fast'); ?>>سريع</option>
                    </select>
                </div>
                
                <div class="filter-group">
                    <label for="country_id">الدولة:</label>
                    <select name="country_id" id="country_id" class="filter-input filter-select">
                        <option value="">جميع الدول</option>
                        <?php foreach ($countries as $country) : ?>
                            <option value="<?php echo $country->ID; ?>" <?php selected($country_filter, $country->ID); ?>>
                                <?php echo esc_html($country->post_title); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="filter-group">
                    <label for="date">التاريخ:</label>
                    <input type="date" name="date" id="date" class="filter-input" value="<?php echo esc_attr($date_filter); ?>">
                </div>
            </div>
            
            <div class="filter-buttons">
                <a href="<?php echo esc_url(add_query_arg('paged', $paged > 1 ? $paged : false, get_permalink())); ?>" class="filter-btn reset-btn">
                    <i class="fas fa-sync-alt"></i>
                    إعادة تعيين
                </a>
                <button type="submit" class="filter-btn">
                    <i class="fas fa-filter"></i>
                    تطبيق الفلتر
                </button>
            </div>
        </form>
    </div>

    <?php if (!empty($shipping_requests)) : ?>
        <!-- جدول الطلبات للشاشات الكبيرة -->
        <table class="orders-table">
            <thead>
                <tr>
                    <th>التاريخ</th>
                    <th>الملاحظات</th>
                    <th>الحالة</th>
                    <th>السعر الإجمالي</th>
                    <th>نوع الشحن</th>
                    <th>الدولة</th>
                    <th>الوزن (كجم)</th>
                    <th>اسم الشحنة</th>
                    <th>رقم الطلب</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($shipping_requests as $request) :
                    $weight = get_post_meta($request->ID, '_weight', true);
                    $country_id = get_post_meta($request->ID, '_country_id', true);
                    $country_title = $country_id ? get_the_title($country_id) : '-';

                    $shipping_type_key = get_post_meta($request->ID, '_shipping_type', true);
                    $shipping_type_label = match ($shipping_type_key) {
                        'land' => 'بري',
                        'sea' => 'بحري',
                        'air' => 'جوي',
                        'fast' => 'سريع',
                        default => '-',
                    };

                    $shipment_name = get_post_meta($request->ID, '_shipment_name', true);
                    $total_price = get_post_meta($request->ID, '_total_price', true);
                    $order_status = get_post_meta($request->ID, '_order_status', true);
                    $notes = get_post_meta($request->ID, '_notes', true);
                    $date = get_the_date('d/m/Y', $request->ID);
                    
                    // تحديد كلاس الحالة
                    $status_class = 'status-new';
                    if ($order_status === 'قيد المراجعة') $status_class = 'status-review';
                    elseif ($order_status === 'جاري الشحن') $status_class = 'status-shipping';
                    elseif ($order_status === 'تم التسليم') $status_class = 'status-delivered';
                ?>
                    <tr>
                        <td><?php echo esc_html($date); ?></td>
                        <td><?php echo esc_html($notes ?: '-'); ?></td>
                        <td><span class="status-badge <?php echo $status_class; ?>"><?php echo esc_html($order_status); ?></span></td>
                        <td><?php echo number_format(floatval($total_price), 2); ?> $</td>
                        <td><?php echo esc_html($shipping_type_label); ?></td>
                        <td><?php echo esc_html($country_title); ?></td>
                        <td><?php echo esc_html($weight); ?></td>
                        <td><?php echo esc_html($request->post_title ?: '-'); ?></td>
                        <td>
                            <a href="<?php echo home_url('/invoice/?order_id=' . $request->ID); ?>" target="_blank" class="order-link">
                                <i class="fas fa-external-link-alt"></i>
                                #<?php echo $request->ID; ?>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <!-- بطاقات الطلبات للشاشات الصغيرة -->
        <div class="mobile-orders">
            <?php foreach ($shipping_requests as $request) :
                $weight = get_post_meta($request->ID, '_weight', true);
                $country_id = get_post_meta($request->ID, '_country_id', true);
                $country_title = $country_id ? get_the_title($country_id) : '-';

                $shipping_type_key = get_post_meta($request->ID, '_shipping_type', true);
                $shipping_type_label = match ($shipping_type_key) {
                    'land' => 'بري',
                    'sea' => 'بحري',
                    'air' => 'جوي',
                    'fast' => 'سريع',
                    default => '-',
                };

                $shipment_name = get_post_meta($request->ID, '_shipment_name', true);
                $total_price = get_post_meta($request->ID, '_total_price', true);
                $order_status = get_post_meta($request->ID, '_order_status', true);
                $notes = get_post_meta($request->ID, '_notes', true);
                $date = get_the_date('d/m/Y', $request->ID);
                
                // تحديد كلاس الحالة
                $status_class = 'status-new';
                if ($order_status === 'قيد المراجعة') $status_class = 'status-review';
                elseif ($order_status === 'جاري الشحن') $status_class = 'status-shipping';
                elseif ($order_status === 'تم التسليم') $status_class = 'status-delivered';
            ?>
                <div class="mobile-card">
                    <div class="card-row">
                        <div class="card-label">رقم الطلب:</div>
                        <div class="card-value">
                            <a href="<?php echo home_url('/invoice/?order_id=' . $request->ID); ?>" target="_blank" class="order-link">
                                #<?php echo $request->ID; ?>
                            </a>
                        </div>
                    </div>
                    
                    <div class="card-row">
                        <div class="card-label">اسم الشحنة:</div>
                        <div class="card-value"><?php echo esc_html($request->post_title ?: '-'); ?></div>
                    </div>
                    
                    <div class="card-row">
                        <div class="card-label">الوزن:</div>
                        <div class="card-value"><?php echo esc_html($weight); ?> كجم</div>
                    </div>
                    
                    <div class="card-row">
                        <div class="card-label">الدولة:</div>
                        <div class="card-value"><?php echo esc_html($country_title); ?></div>
                    </div>
                    
                    <div class="card-row">
                        <div class="card-label">نوع الشحن:</div>
                        <div class="card-value"><?php echo esc_html($shipping_type_label); ?></div>
                    </div>
                    
                    <div class="card-row">
                        <div class="card-label">السعر:</div>
                        <div class="card-value"><?php echo number_format(floatval($total_price), 2); ?> $</div>
                    </div>
                    
                    <div class="card-row">
                        <div class="card-label">الحالة:</div>
                        <div class="card-value">
                            <span class="status-badge <?php echo $status_class; ?>"><?php echo esc_html($order_status); ?></span>
                        </div>
                    </div>
                    
                    <div class="card-row">
                        <div class="card-label">التاريخ:</div>
                        <div class="card-value"><?php echo esc_html($date); ?></div>
                    </div>
                    
                    <?php if ($notes) : ?>
                        <div class="card-row">
                            <div class="card-label">ملاحظات:</div>
                            <div class="card-value"><?php echo esc_html($notes); ?></div>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else : ?>
        <div class="no-orders">
            <i class="fas fa-box-open"></i>
            <p>لا توجد طلبات تطابق معايير البحث</p>
        </div>
    <?php endif; ?>

    <?php if ($shipping_requests_query->max_num_pages > 1) : ?>
        <div class="pagination-container">
            <?php
            $big = 999999999;
            echo paginate_links([
                'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
                'format' => '?paged=%#%',
                'current' => max(1, $paged),
                'total' => $shipping_requests_query->max_num_pages,
                'prev_text' => '<i class="fas fa-chevron-left"></i>',
                'next_text' => '<i class="fas fa-chevron-right"></i>',
            ]);
            ?>
        </div>
    <?php endif; ?>
    <?php wp_reset_postdata(); ?>
</div>
        </main>
<?php
get_footer();
?>
