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



<div class="shipping-container">
    <div class="shipping-header">
        <i class="fas fa-shipping-fast"></i>
        <h2>نموذج طلب شحن</h2>
    </div>
    
    <form method="post" action="" class="shipping-request-form">
        <?php wp_nonce_field('submit_shipping_request', 'shipping_request_nonce'); ?>
        
        <div class="form-group">
            <label for="custom_title">اسم الشحنة (اختياري):</label>
            <i class="fas fa-box input-icon"></i>
            <input type="text" name="custom_title" id="custom_title" class="form-input" placeholder="مثال: شحنة كتب – طلب رقم ...">
        </div>
        
        <div class="form-row">
            <div class="form-group">
                <label for="weight">الوزن (كجم):</label>
                <i class="fas fa-weight input-icon"></i>
                <input type="number" name="weight" id="weight" class="form-input" step="0.01" min="0.1" required>
            </div>
            
            <div class="form-group">
                <label for="country_id">اختر الدولة:</label>
                <i class="fas fa-globe input-icon"></i>
                <select name="country_id" id="country_id" class="form-input form-select" required>
                    <option value="">-- اختر الدولة --</option>
                    <?php foreach ($countries as $country): ?>
                        <option value="<?php echo $country->ID; ?>">
                            <?php echo esc_html($country->post_title); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        
        <div class="form-group">
            <label>نوع الشحن:</label>
            
            <div class="shipping-type-option" data-value="land">
                <div class="shipping-type-icon"><i class="fas fa-truck"></i></div>
                <div>
                    <strong>شحن بري</strong>
                    <div class="price-note">الأكثر اقتصادية للشحنات الكبيرة</div>
                </div>
            </div>
            
            <div class="shipping-type-option" data-value="sea">
                <div class="shipping-type-icon"><i class="fas fa-ship"></i></div>
                <div>
                    <strong>شحن بحري</strong>
                    <div class="price-note">مناسب للشحنات الضخمة والغير مستعجلة</div>
                </div>
            </div>
            
            <div class="shipping-type-option" data-value="air">
                <div class="shipping-type-icon"><i class="fas fa-plane"></i></div>
                <div>
                    <strong>شحن جوي</strong>
                    <div class="price-note">الأسرع للشحنات المتوسطة والخفيفة</div>
                </div>
            </div>
            
            <div class="shipping-type-option" data-value="fast">
                <div class="shipping-type-icon"><i class="fas fa-bolt"></i></div>
                <div>
                    <strong>شحن سريع</strong>
                    <div class="price-note">الأسرع مع تتبع فوري - التوصيل خلال 72 ساعة</div>
                </div>
            </div>
            
            <input type="hidden" name="shipping_type" id="shipping_type" value="" required>
        </div>
        
        <div class="form-group">
            <label for="notes">ملاحظات إضافية (اختياري):</label>
            <i class="fas fa-sticky-note input-icon"></i>
            <textarea name="notes" id="notes" class="form-input form-textarea" placeholder="أدخل أي ملاحظات تخص الشحنة..."></textarea>
        </div>
        
        <!-- <div class="price-estimate">
            <h3>التكلفة المتوقعة</h3>
            <div class="price-value">سيتم عرض السعر بعد اختيار الدولة ونوع الشحن</div>
            <div class="price-note">السعر النهائي قد يختلف قليلاً حسب الوزن الفعلي بعد المعاينة</div>
        </div> -->
        
        <div class="shipping-info-card">
            <i class="fas fa-info-circle"></i>
            <p>يرجى التأكد من صحة المعلومات المدخلة. بعد إرسال الطلب، سيتم التواصل معك لتأكيد التفاصيل وتحديد موعد الاستلام. يمكنك متابعة حالة الطلب من خلال حسابك.</p>
        </div>
        
        <button type="submit" class="submit-btn">
            <i class="fas fa-paper-plane"></i>
            إرسال طلب الشحن
        </button>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // إدارة اختيار نوع الشحن
    const shippingOptions = document.querySelectorAll('.shipping-type-option');
    const shippingTypeInput = document.getElementById('shipping_type');
    
    shippingOptions.forEach(option => {
        option.addEventListener('click', function() {
            shippingOptions.forEach(opt => opt.classList.remove('selected'));
            this.classList.add('selected');
            shippingTypeInput.value = this.getAttribute('data-value');
        });
    });
    
    // يمكن إضافة المزيد من الوظائف التفاعلية هنا
});
</script>

<?php //get_footer(); ?>