<?php
/* Template Name: طلباتي */

// get_header();

if (!is_user_logged_in()) {
    echo '<p>⚠️ يجب عليك تسجيل الدخول لعرض طلباتك.</p>';
    get_footer();
    exit;
}

$current_user_id = get_current_user_id();

// استعلام الطلبات التي أنشأها المستخدم الحالي
$args = [
    'post_type' => 'shipping_request',
    'author' => $current_user_id,
    'posts_per_page' => -1,
    'orderby' => 'date',
    'order' => 'DESC',
];

$requests = new WP_Query($args);
if (!is_user_logged_in() || !current_user_can('read_shipping_requests')) {
    echo '<p>⚠️ ليس لديك صلاحية لعرض طلبات الشحن.</p>';
    get_footer();
    exit;
}

if ($requests->have_posts()) {
    echo '<h2>طلبات الشحن الخاصة بك</h2>';
    echo '<table border="1" cellpadding="8" cellspacing="0">';
    echo '<tr><th>رقم الطلب</th><th>الوزن (كجم)</th><th>الدولة</th><th>الحالة</th><th>السعر الإجمالي</th><th>التاريخ</th><th>رابط العرض</th></tr>';

    while ($requests->have_posts()) {
        $requests->the_post();
        $weight = get_post_meta(get_the_ID(), '_weight', true);
        $country_id = get_post_meta(get_the_ID(), '_country_id', true);
        $country = $country_id ? get_the_title($country_id) : '-';
        $order_status = get_post_meta(get_the_ID(), '_order_status', true);
        $total_price = get_post_meta(get_the_ID(), '_total_price', true);
        $date = get_the_date();

        echo '<tr>';
        echo '<td>' . get_the_ID() . '</td>';
        echo '<td>' . esc_html($weight) . '</td>';
        echo '<td>' . esc_html($country) . '</td>';
        echo '<td>' . esc_html($order_status) . '</td>';
        echo '<td>' . esc_html($total_price) . '</td>';
        echo '<td>' . esc_html($date) . '</td>';
        echo '<td><a href="' . get_permalink() . '">عرض الطلب</a></td>';
        echo '</tr>';
    }
    echo '</table>';

    wp_reset_postdata();

} else {
    echo '<p>لم تقم بأي طلبات حتى الآن.</p>';
}

// get_footer();
