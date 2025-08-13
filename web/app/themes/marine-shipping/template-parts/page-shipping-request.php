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

<h2 class="title-page-shipping">نموذج طلب شحن</h2>

<form method="post" action="" class="shipping-request-form">
    <?php wp_nonce_field('submit_shipping_request', 'shipping_request_nonce'); ?>

    <label for="custom_title" class="custom-title">اسم الشحنة (اختياري):</label>
    <input type="text" name="custom_title" id="custom_title" class="title-shipping" placeholder="مثال: شحنة كتب – طلب رقم ...">
    <br><br>

    <label for="weight" class="weigth-title">الوزن (كجم):</label>
    <input type="number" name="weight" id="weight" class="weigth-input" step="0.01" min="0" required>
    <br><br>

    <label for="country_id" class="country-title">اختر الدولة:</label>
    <select name="country_id" id="country_id" class="country-type" required>
        <option value="">-- اختر الدولة --</option>
        <?php foreach ($countries as $country): ?>
            <option value="<?php echo $country->ID; ?>">
                <?php echo esc_html($country->post_title); ?>
            </option>
        <?php endforeach; ?>
    </select>
    <br><br>

    <label for="shipping_type" class="shipping-type-title">نوع الشحن:</label>
    <select name="shipping_type" id="shipping_type" class="shipping-type" required>
        <option value="">-- اختر نوع الشحن --</option>
        <option value="land" class="land">بري</option>
        <option value="sea"  class="sea">بحري</option>
        <option value="air"  class="air">جوي</option>
        <option value="fast" class="fast">سريع</option>
    </select>
    <br><br>

    <label for="notes" class="notes">ملاحظات إضافية (اختياري):</label><br>
    <textarea  class="note-textarea" name="notes" id="notes" rows="4" cols="50" placeholder="أدخل أي ملاحظات تخص الشحنة..."></textarea>
    <br><br>

    <!-- <button type="submit">📦 إرسال الطلب</button> -->
     <!-- From Uiverse.io by adamgiebl --> 
<button>
      <span> إرسال الطلب </span>

  <svg
    height="24"
    width="24"
    viewBox="0 0 24 24"
    xmlns="http://www.w3.org/2000/svg"
  >
    <path d="M0 0h24v24H0z" fill="none"></path>
    <path
      d="M5 13c0-5.088 2.903-9.436 7-11.182C16.097 3.564 19 7.912 19 13c0 .823-.076 1.626-.22 2.403l1.94 1.832a.5.5 0 0 1 .095.603l-2.495 4.575a.5.5 0 0 1-.793.114l-2.234-2.234a1 1 0 0 0-.707-.293H9.414a1 1 0 0 0-.707.293l-2.234 2.234a.5.5 0 0 1-.793-.114l-2.495-4.575a.5.5 0 0 1 .095-.603l1.94-1.832C5.077 14.626 5 13.823 5 13zm1.476 6.696l.817-.817A3 3 0 0 1 9.414 18h5.172a3 3 0 0 1 2.121.879l.817.817.982-1.8-1.1-1.04a2 2 0 0 1-.593-1.82c.124-.664.187-1.345.187-2.036 0-3.87-1.995-7.3-5-8.96C8.995 5.7 7 9.13 7 13c0 .691.063 1.372.187 2.037a2 2 0 0 1-.593 1.82l-1.1 1.039.982 1.8zM12 13a2 2 0 1 1 0-4 2 2 0 0 1 0 4z"
      fill="currentColor"
    ></path>
  </svg>
</button>

</form>

<?php get_footer(); ?>
