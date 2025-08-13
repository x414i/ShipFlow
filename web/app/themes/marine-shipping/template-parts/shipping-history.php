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

// بحث برقم الطلب
$args = [
    'post_type' => 'shipping_request',
    'posts_per_page' => -1,
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
    $shipping_requests = get_posts([
        'post_type' => 'shipping_request',
        'p' => $order_id_search,
        'author' => $current_user_id,
    ]);
} else {
    $shipping_requests = get_posts($args);
}
?>

<style>
    .shipping-history-container {
        max-width: 1200px;
        margin: 30px auto;
        padding: 20px;
        background: #fff;
        border-radius: 15px;
        box-shadow: 0 5px 25px rgba(0, 0, 0, 0.08);
        font-family: 'Segoe UI', Tahoma, sans-serif;
    }
    
    .history-header {
        display: flex;
        align-items: center;
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 1px solid #eee;
    }
    
    .history-header h2 {
        margin: 0;
        font-size: 30px;
        color: #2c3e50;
        font-weight: 700;
        display: flex;
        align-items: center;
    }
    
    .history-header i {
        background: #3498db;
        color: white;
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-left: 15px;
        font-size: 22px;
    }
    
    .filters-container {
        background: #f8f9fa;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 30px;
        border: 1px solid #eee;
    }
    
    .filters-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 15px;
        margin-bottom: 15px;
    }
    
    .filter-group {
        margin-bottom: 10px;
    }
    
    .filter-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: #2c3e50;
        font-size: 14px;
    }
    
    .filter-input {
        width: 100%;
        padding: 12px 15px;
        border: 1px solid #ddd;
        border-radius: 8px;
        font-size: 14px;
        background: #fff;
        transition: all 0.3s;
    }
    
    .filter-input:focus {
        border-color: #3498db;
        box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.2);
        outline: none;
    }
    
    .filter-select {
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='14' height='14' fill='%237f8c8d' viewBox='0 0 16 16'%3E%3Cpath d='M8 11L3 6h10z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 12px center;
        background-size: 12px;
        padding-right: 35px;
    }
    
    .filter-buttons {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        padding-top: 10px;
        border-top: 1px solid #eee;
        margin-top: 10px;
    }
    
    .filter-btn {
        background: #3498db;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 600;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .filter-btn:hover {
        background: #2980b9;
        transform: translateY(-2px);
    }
    
    .reset-btn {
        background: #e74c3c;
    }
    
    .reset-btn:hover {
        background: #c0392b;
    }
    
    .orders-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
        overflow: hidden;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }
    
    .orders-table th {
        background: #3498db;
        color: white;
        text-align: right;
        padding: 15px;
        font-weight: 600;
    }
    
    .orders-table td {
        padding: 12px 15px;
        border-bottom: 1px solid #eee;
        text-align: right;
    }
    
    .orders-table tr:nth-child(even) {
        background: #f9fafb;
    }
    
    .orders-table tr:hover {
        background: #f0f7ff;
    }
    
    .status-badge {
        display: inline-block;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
    }
    
    .status-new {
        background: #e8f4fd;
        color: #3498db;
        border: 1px solid #b8dcfa;
    }
    
    .status-review {
        background: #fef7e0;
        color: #e67e22;
        border: 1px solid #fad7a0;
    }
    
    .status-shipping {
        background: #e8f6ef;
        color: #27ae60;
        border: 1px solid #a9dfbf;
    }
    
    .status-delivered {
        background: #f4ecf7;
        color: #8e44ad;
        border: 1px solid #d2b4de;
    }
    
    .order-link {
        color: #2980b9;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        gap: 5px;
    }
    
    .order-link:hover {
        color: #1a5276;
        text-decoration: underline;
    }
    
    .no-orders {
        text-align: center;
        padding: 40px;
        background: #f9fafb;
        border-radius: 10px;
        margin-top: 20px;
    }
    
    .no-orders i {
        font-size: 50px;
        color: #bdc3c7;
        margin-bottom: 15px;
    }
    
    .no-orders p {
        font-size: 18px;
        color: #7f8c8d;
        margin: 0;
    }
    
    .mobile-card {
        display: none;
        background: #fff;
        border-radius: 10px;
        padding: 15px;
        margin-bottom: 15px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        border: 1px solid #eee;
    }
    
    .card-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 10px;
        padding-bottom: 10px;
        border-bottom: 1px solid #f5f5f5;
    }
    
    .card-label {
        font-weight: 600;
        color: #2c3e50;
        min-width: 120px;
    }
    
    .card-value {
        color: #34495e;
        text-align: left;
    }
    
    @media (max-width: 992px) {
        .orders-table {
            display: none;
        }
        
        .mobile-card {
            display: block;
        }
        
        .filters-grid {
            grid-template-columns: 1fr;
        }
        
        .history-header h2 {
            font-size: 24px;
        }
    }
</style>

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
                <a href="<?php echo esc_url(get_permalink()); ?>" class="filter-btn reset-btn">
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
</div>

<?php
get_footer();
?>