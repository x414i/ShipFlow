<?php
/*
Template Name: نموذج طلب شحن
*/

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['shipping_request_nonce']) && wp_verify_nonce($_POST['shipping_request_nonce'], 'submit_shipping_request')) {
    
    if (!is_user_logged_in()) {
        wp_die('يجب تسجيل الدخول لتقديم الطلب.');
    }

    $weight = floatval($_POST['weight']);
    $country_id = intval($_POST['country_id']);

    if ($weight <= 0 || $country_id <= 0) {
        wp_die('يرجى إدخال بيانات صحيحة.');
    }

    $price_per_kg = get_post_meta($country_id, '_price_per_kg', true);
    if (!$price_per_kg) {
        wp_die('لا يوجد سعر شحن لهذه الدولة.');
    }

    $total_price = $weight * floatval($price_per_kg);

    $new_request = wp_insert_post([
        'post_title'    => 'طلب شحن جديد - ' . current_time('mysql'),
        'post_type'     => 'shipping_request',
        'post_status'   => 'publish',
        'post_author'   => get_current_user_id(),
    ]);

    if ($new_request) {
        update_post_meta($new_request, '_weight', $weight);
        update_post_meta($new_request, '_country_id', $country_id);
        update_post_meta($new_request, '_total_price', $total_price);
        update_post_meta($new_request, '_order_status', 'جديد');

        // ✅ إعادة التوجيه إلى صفحة الفاتورة
        wp_redirect(home_url('/invoice/?order_id=' . $new_request));
        exit;
    } else {
        wp_die('حدث خطأ أثناء إنشاء الطلب.');
    }
}

get_header();

// جلب الدول
$countries = get_posts([
    'post_type' => 'country',
    'posts_per_page' => -1,
    'orderby' => 'title',
    'order' => 'ASC',
]);
?>

<h2>نموذج طلب شحن</h2>

<form method="post" action="">
    <?php wp_nonce_field('submit_shipping_request', 'shipping_request_nonce'); ?>

    <label for="weight">الوزن (كجم):</label>
    <input type="number" name="weight" id="weight" step="0.01" min="0" required>

    <br><br>

    <label for="country_id">اختر الدولة:</label>
    <select name="country_id" id="country_id" required>
        <option value="">-- اختر الدولة --</option>
        <?php foreach ($countries as $country): ?>
            <option value="<?php echo $country->ID; ?>">
                <?php echo esc_html($country->post_title); ?>
            </option>
        <?php endforeach; ?>
    </select>

    <br><br>

    <button type="submit">إرسال الطلب</button>
</form>

<?php get_footer(); ?>
