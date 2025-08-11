<?php
/*
Template Name: نموذج طلب شحن
*/

// get_header();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['shipping_request_nonce']) && wp_verify_nonce($_POST['shipping_request_nonce'], 'submit_shipping_request')) {
    
    // تأكد من صلاحيات المستخدم (مثلاً تسجيل الدخول أو لا)
    if (!is_user_logged_in()) {
        echo '<p>يجب عليك تسجيل الدخول لتقديم طلب شحن.</p>';
    } else {
        // استقبال البيانات
        $weight = floatval($_POST['weight']);
        $country_id = intval($_POST['country_id']);
        
        // تحقق من صحة البيانات
        if ($weight <= 0 || $country_id <= 0) {
            echo '<p>يرجى إدخال بيانات صحيحة.</p>';
        } else {
            // حساب السعر من بيانات الدولة
            $price_per_kg = get_post_meta($country_id, '_price_per_kg', true);
            if (!$price_per_kg) {
                echo '<p>تعذر الحصول على سعر الشحن للدولة المختارة.</p>';
            } else {
                $total_price = $weight * floatval($price_per_kg);

                // إنشاء طلب جديد
                $new_request = wp_insert_post([
                    'post_title' => 'طلب شحن جديد - ' . date('Y-m-d H:i:s'),
                    'post_type' => 'shipping_request',
                    'post_status' => 'publish',
                ]);

                if ($new_request) {
                    update_post_meta($new_request, '_weight', $weight);
                    update_post_meta($new_request, '_country_id', $country_id);
                    update_post_meta($new_request, '_total_price', $total_price);
                    update_post_meta($new_request, '_order_status', 'جديد');

                    echo '<p>تم إرسال طلبك بنجاح! السعر الإجمالي: ' . number_format($total_price, 2) . ' $.</p>';
                } else {
                    echo '<p>حدث خطأ أثناء إرسال الطلب، يرجى المحاولة لاحقًا.</p>';
                }
            }
        }
    }
}

// جلب الدول المتاحة
$countries = get_posts([
    'post_type' => 'country',
    'posts_per_page' => -1,
    'orderby' => 'title',
    'order' => 'ASC',
]);

?>

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
<p>السعر المتوقع: <span id="total_price" style="font-weight: bold;"></span></p>

    <br><br>

    <button type="submit">إرسال الطلب</button>
</form>

<?php 
// get_footer(); ?>
