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
    $shipping_type = sanitize_text_field($_POST['shipping_type']);
    $custom_title = sanitize_text_field($_POST['custom_title']);
    $notes = sanitize_textarea_field($_POST['notes']);

    if ($weight <= 0 || $country_id <= 0) {
        wp_die('يرجى إدخال بيانات صحيحة.');
    }

    if (!in_array($shipping_type, ['land', 'sea', 'air', 'fast'])) {
        wp_die('يرجى اختيار نوع الشحن الصحيح.');
    }

    // جلب السعر حسب نوع الشحن
    $meta_key = 'price_' . $shipping_type;
    $price_per_kg = floatval(get_post_meta($country_id, $meta_key, true));

    if (!$price_per_kg || $price_per_kg <= 0) {
        wp_die('لا يوجد سعر شحن لهذه الدولة ونوع الشحن المختار.');
    }

    $total_price = $weight * $price_per_kg;

    // تعيين عنوان الطلب
    $user_info = wp_get_current_user();
    $base_title = $custom_title !== '' ? $custom_title : $user_info->display_name;
    $post_title = $base_title . ' - ' . current_time('YmdHis');

    $new_request = wp_insert_post([
        'post_title'    => $post_title,
        'post_type'     => 'shipping_request',
        'post_status'   => 'publish',
        'post_author'   => get_current_user_id(),
    ]);

    if ($new_request) {
        update_post_meta($new_request, '_weight', $weight);
        update_post_meta($new_request, '_country_id', $country_id);
        update_post_meta($new_request, '_shipping_type', $shipping_type);
        update_post_meta($new_request, '_total_price', $total_price);
        update_post_meta($new_request, '_order_status', 'جديد');
        update_post_meta($new_request, '_notes', $notes);

        // إعادة التوجيه إلى الفاتورة
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

    <label for="custom_title">اسم الشحنة (اختياري):</label>
    <input type="text" name="custom_title" id="custom_title" placeholder="مثال: شحنة كتب – طلب رقم ...">
    <br><br>

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

    <label for="shipping_type">نوع الشحن:</label>
    <select name="shipping_type" id="shipping_type" required>
        <option value="">-- اختر نوع الشحن --</option>
        <option value="land">بري</option>
        <option value="sea">بحري</option>
        <option value="air">جوي</option>
        <option value="fast">سريع</option>
    </select>
    <br><br>

    <label for="notes">ملاحظات إضافية (اختياري):</label><br>
    <textarea name="notes" id="notes" rows="4" cols="50" placeholder="أدخل أي ملاحظات تخص الشحنة..."></textarea>
    <br><br>

    <button type="submit">📦 إرسال الطلب</button>
</form>

<?php get_footer(); ?>
