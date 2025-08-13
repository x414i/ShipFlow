<?php
/*
Template Name: سجل الطلبات
*/

get_header();
// include("dashboard-wrapper.php");

if (!is_user_logged_in()) {
    echo '<p>يجب تسجيل الدخول لعرض سجل الطلبات.</p>';
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

<div class="shipping-history">
    <h2>📦 سجل طلبات الشحن</h2>

    <form method="get" action="" style="margin-bottom: 20px;">
        <label>🔍 بحث:</label>

        <input type="text" name="shipment_name" placeholder="اسم الشحنة" value="<?php echo esc_attr($search_name); ?>">

        <input type="number" name="order_id" placeholder="رقم الطلب" value="<?php echo esc_attr($order_id_search); ?>">

        <select name="status">
            <option value="">الحالة</option>
            <option value="جديد" <?php selected($status_filter, 'جديد'); ?>>جديد</option>
            <option value="قيد المراجعة" <?php selected($status_filter, 'قيد المراجعة'); ?>>قيد المراجعة</option>
            <option value="جاري الشحن" <?php selected($status_filter, 'جاري الشحن'); ?>>جاري الشحن</option>
            <option value="تم التسليم" <?php selected($status_filter, 'تم التسليم'); ?>>تم التسليم</option>
            <!-- <option value="مرفوض" <?php //selected($status_filter, 'مرفوض'); ?>>مرفوض</option> -->
        </select>

        <select name="shipping_type">
            <option value="">نوع الشحن</option>
            <option value="land" <?php selected($type_filter, 'land'); ?>>بري</option>
            <option value="sea" <?php selected($type_filter, 'sea'); ?>>بحري</option>
            <option value="air" <?php selected($type_filter, 'air'); ?>>جوي</option>
            <option value="fast" <?php selected($type_filter, 'fast'); ?>>سريع</option>
        </select>

        <select name="country_id">
            <option value="">الدولة</option>
            <?php foreach ($countries as $country) : ?>
                <option value="<?php echo $country->ID; ?>" <?php selected($country_filter, $country->ID); ?>>
                    <?php echo esc_html($country->post_title); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <input type="date" name="date" value="<?php echo esc_attr($date_filter); ?>">

        <button type="submit">تصفية</button>
        <a href="<?php echo esc_url(get_permalink()); ?>" style="margin-right: 10px;">🔄 إعادة تعيين</a>
    </form>

    <?php if (!empty($shipping_requests)) : ?>
        <table>
            <thead>
                <tr>
                    <th>رقم الطلب</th>
                    <th>اسم الشحنة</th>
                    <th>الوزن (كجم)</th>
                    <th>الدولة</th>
                    <th>نوع الشحن</th>
                    <th>السعر الإجمالي</th>
                    <th>الحالة</th>
                    <th>الملاحظات</th>
                    <th>التاريخ</th>
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
                    $date = get_the_date('', $request->ID);
                ?>
                    <tr>
                        <td>
                            <a href="<?php echo home_url('/invoice/?order_id=' . $request->ID); ?>" target="_blank">
                                #<?php echo $request->ID; ?>
                            </a>
                        </td>
                        <td><?php echo esc_html($request->post_title ?: '-'); ?></td>
                        <td><?php echo esc_html($weight); ?></td>
                        <td><?php echo esc_html($country_title); ?></td>
                        <td><?php echo esc_html($shipping_type_label); ?></td>
                        <td><?php echo number_format(floatval($total_price), 2); ?> $</td>
                        <td><?php echo esc_html($order_status); ?></td>
                        <td><?php echo esc_html($notes ?: '-'); ?></td>
                        <td><?php echo esc_html($date); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else : ?>
        <p>لا توجد طلبات تطابق معايير البحث.</p>
    <?php endif; ?>
</div>

<style>
.shipping-history table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}
.shipping-history th, .shipping-history td {
    border: 1px solid #ccc;
    padding: 8px;
    text-align: center;
}
.shipping-history th {
    background-color: #f5f5f5;
}
.shipping-history a {
    color: #0073aa;
    text-decoration: none;
}
.shipping-history a:hover {
    text-decoration: underline;
}
form {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    align-items: center;
    margin-bottom: 20px;
}
form input[type="text"],
form input[type="number"],
form input[type="date"],
form select {
    padding: 6px;
    min-width: 140px;
}
</style>

<?php
// get_footer();
?>
