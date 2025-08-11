<?php
/*
Template Name: طلب شحن جديد
*/

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['shipping_request_nonce']) && wp_verify_nonce($_POST['shipping_request_nonce'], 'submit_shipping_request')) {

    $weight = floatval($_POST['weight']);
    $country_id = intval($_POST['country_id']);
    $user_id = get_current_user_id(); // يمكنك ربط الطلب بالمستخدم إن كنت تستخدم تسجيل دخول

    // تحقق من القيم المطلوبة
    if ($weight > 0 && $country_id > 0) {

        // إنشاء طلب جديد
        $post_id = wp_insert_post([
            'post_type' => 'shipping_request',
            'post_title' => 'طلب شحن - ' . date('Y-m-d H:i:s'),
            'post_status' => 'publish',
            'post_author' => $user_id,
            'meta_input' => [
                '_weight' => $weight,
                '_country_id' => $country_id,
                '_order_status' => 'جديد',
            ],
        ]);

        if ($post_id) {
            echo '<p style="color:green;">تم إرسال طلب الشحن بنجاح!</p>';
        } else {
            echo '<p style="color:red;">حدث خطأ أثناء إرسال الطلب. حاول مرة أخرى.</p>';
        }
    } else {
        echo '<p style="color:red;">يرجى ملء جميع الحقول بشكل صحيح.</p>';
    }
}

// جلب الدول
$countries = get_posts([
    'post_type' => 'country',
    'posts_per_page' => -1,
    'orderby' => 'title',
    'order' => 'ASC',
]);
?>

<form method="post">
    <?php wp_nonce_field('submit_shipping_request', 'shipping_request_nonce'); ?>

    <label for="weight">الوزن (كجم):</label>
    <input type="number" name="weight" id="weight" step="0.01" min="0" required>

    <br><br>

    <label for="country_id">اختر الدولة:</label>
    <select name="country_id" id="country_id" required>
        <option value="">-- اختر الدولة --</option>
        <?php foreach ($countries as $country) : ?>
            <option value="<?php echo esc_attr($country->ID); ?>"><?php echo esc_html($country->post_title); ?></option>
        <?php endforeach; ?>
    </select>
<p>السعر المتوقع: <span id="total_price" style="font-weight: bold;"></span></p>

    <br><br>

    <button type="submit">أرسل طلب الشحن</button>
</form>
