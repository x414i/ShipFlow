<?php
/*
Template Name: سجل الطلبات
*/

get_header();
include("dashboard-wrapper.php");

if (!is_user_logged_in()) {
    echo '<p>يجب تسجيل الدخول لعرض سجل الطلبات.</p>';
    get_footer();
    exit;
}

$current_user_id = get_current_user_id();

$shipping_requests = get_posts([
    'post_type' => 'shipping_request',
    'posts_per_page' => -1,
    'author' => $current_user_id,
    'orderby' => 'date',
    'order' => 'DESC',
]);

?>

<div class="shipping-history">
    <h2>📦 سجل طلبات الشحن</h2>

    <?php if (!empty($shipping_requests)) : ?>
        <table>
            <thead>
                <tr>
                    <th>رقم الطلب</th>
                    <th>الوزن (كجم)</th>
                    <th>الدولة</th>
                    <th>نوع الشحن</th>
                    <th>السعر الإجمالي</th>
                    <th>حالة الطلب</th>
                    <th>الملاحظات</th>
                    <th>التاريخ</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($shipping_requests as $request) : 
                    $weight = get_post_meta($request->ID, '_weight', true);
                    $country_id = get_post_meta($request->ID, '_country_id', true);
                    $country_title = $country_id ? get_the_title($country_id) : '-';

                    // جلب نوع الشحن من ميتا الطلب وليس من الدولة
                    $shipping_type_key = get_post_meta($request->ID, '_shipping_type', true);

                    $shipping_type_label = match ($shipping_type_key) {
                        'land' => 'بري',
                        'sea'  => 'بحري',
                        'air'  => 'جوي',
                        'fast' => 'سريع',
                        default => '-',
                    };

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
        <p>لا توجد طلبات حتى الآن.</p>
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
</style>

<?php 
// get_footer(); 
?>
