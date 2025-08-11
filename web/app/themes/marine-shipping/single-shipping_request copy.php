<?php
get_header();

if (!is_user_logged_in()) {
    echo '<p>⚠️ يجب تسجيل الدخول لعرض تفاصيل الطلب.</p>';
    get_footer();
    exit;
}

global $post;

// تحقق أن نوع المحتوى صحيح
if (get_post_type($post) !== 'shipping_request') {
    echo '<p>هذا ليس طلب شحن صالح.</p>';
    get_footer();
    exit;
}

// تحقق أن المستخدم هو صاحب الطلب أو مسؤول
$current_user_id = get_current_user_id();
if ($post->post_author != $current_user_id && !current_user_can('manage_options')) {
    echo '<p>⚠️ ليس لديك صلاحية عرض هذا الطلب.</p>';
    get_footer();
    exit;
}

// جلب بيانات الطلب
$weight = get_post_meta($post->ID, '_weight', true);
$country_id = get_post_meta($post->ID, '_country_id', true);
$country = $country_id ? get_the_title($country_id) : '-';
$order_status = get_post_meta($post->ID, '_order_status', true);
$total_price = get_post_meta($post->ID, '_total_price', true);
$date = get_the_date();

?>

<h2>تفاصيل طلب الشحن #<?php echo esc_html($post->ID); ?></h2>

<ul>
    <li><strong>الوزن (كجم):</strong> <?php echo esc_html($weight); ?></li>
    <li><strong>الدولة:</strong> <?php echo esc_html($country); ?></li>
    <li><strong>حالة الطلب:</strong> <?php echo esc_html($order_status); ?></li>
    <li><strong>السعر الإجمالي:</strong> <?php echo esc_html($total_price); ?></li>
    <li><strong>تاريخ الطلب:</strong> <?php echo esc_html($date); ?></li>
</ul>

<?php
// get_footer()