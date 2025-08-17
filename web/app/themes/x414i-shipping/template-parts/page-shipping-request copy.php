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

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>نموذج طلب شحن</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #3498db;
            --primary-dark: #2980b9;
            --secondary: #2c3e50;
            --accent: #e74c3c;
            --light: #f8f9fa;
            --dark: #343a40;
            --success: #27ae60;
            --warning: #f39c12;
            --gray: #7f8c8d;
            --light-gray: #ecf0f1;
            --border-radius: 12px;
            --shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
            --transition: all 0.3s ease;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', 'Tajawal', Tahoma, sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #e4e7eb 100%);
            min-height: 100vh;
            padding: 20px;
            color: var(--secondary);
        }
        
        .shipping-container {
            max-width: 900px;
            margin: 40px auto;
            background: #fff;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            overflow: hidden;
            position: relative;
            z-index: 1;
        }
        
        .shipping-header {
            background: linear-gradient(120deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            padding: 35px 40px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .shipping-header::before {
            content: "";
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0) 70%);
            transform: rotate(30deg);
        }
        
        .shipping-header i {
            font-size: 50px;
            margin-bottom: 20px;
            display: inline-block;
            text-shadow: 0 2px 10px rgba(0,0,0,0.2);
        }
        
        .shipping-header h2 {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 10px;
        }
        
        .shipping-header p {
            font-size: 18px;
            opacity: 0.9;
            max-width: 600px;
            margin: 0 auto;
        }
        
        .shipping-content {
            padding: 40px;
        }
        
        .form-row {
            display: flex;
            gap: 25px;
            margin-bottom: 25px;
        }
        
        .form-group {
            flex: 1;
            position: relative;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 12px;
            font-weight: 600;
            color: var(--secondary);
            font-size: 16px;
            position: relative;
            padding-right: 5px;
        }
        
        .form-group label::after {
            content: '*';
            color: var(--accent);
            margin-right: 4px;
            display: inline-block;
            opacity: 0.7;
        }
        
        .form-group.optional label::after {
            content: '';
            display: none;
        }
        
        .input-container {
            position: relative;
        }
        
        .form-input {
            width: 100%;
            padding: 16px 20px 16px 55px;
            border: 1px solid #ddd;
            border-radius: var(--border-radius);
            font-size: 16px;
            transition: var(--transition);
            background: var(--light);
            color: var(--dark);
        }
        
        .form-input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.2);
            outline: none;
            background: #fff;
        }
        
        .input-icon {
            position: absolute;
            right: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--primary);
            font-size: 20px;
            z-index: 2;
        }
        
        .form-textarea {
            height: 120px;
            resize: vertical;
            padding: 16px 20px 16px 55px;
        }
        
        .form-select {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%233498db' viewBox='0 0 16 16'%3E%3Cpath d='M8 11L3 6h10z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: left 15px center;
            background-size: 14px;
            padding-left: 55px;
        }
        
        .shipping-types {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin: 20px 0;
        }
        
        .shipping-type-option {
            background: var(--light);
            border-radius: var(--border-radius);
            padding: 20px 15px;
            cursor: pointer;
            transition: var(--transition);
            border: 2px solid var(--light-gray);
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .shipping-type-option:hover {
            transform: translateY(-5px);
            border-color: var(--primary);
            box-shadow: 0 10px 20px rgba(0,0,0,0.08);
        }
        
        .shipping-type-option.selected {
            border-color: var(--primary);
            background: rgba(52, 152, 219, 0.05);
        }
        
        .shipping-type-option.selected::after {
            content: '\f00c';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            position: absolute;
            top: 10px;
            left: 10px;
            width: 24px;
            height: 24px;
            background: var(--primary);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
        }
        
        .shipping-type-icon {
            font-size: 36px;
            color: var(--primary);
            margin-bottom: 15px;
            display: inline-block;
        }
        
        .shipping-type-title {
            font-weight: 700;
            font-size: 18px;
            margin-bottom: 8px;
            color: var(--secondary);
        }
        
        .shipping-type-desc {
            font-size: 14px;
            color: var(--gray);
            line-height: 1.5;
        }
        
        .price-estimate {
            background: linear-gradient(135deg, #e8f4fd, #f0f9ff);
            border-radius: var(--border-radius);
            padding: 25px;
            margin: 30px 0;
            text-align: center;
            border-left: 4px solid var(--primary);
            transition: var(--transition);
            opacity: 0;
            transform: translateY(10px);
        }
        
        .price-estimate.visible {
            opacity: 1;
            transform: translateY(0);
        }
        
        .price-estimate h3 {
            margin: 0 0 15px 0;
            color: var(--secondary);
            font-size: 20px;
            font-weight: 600;
        }
        
        .price-value {
            font-size: 36px;
            font-weight: 800;
            color: var(--primary);
            margin: 10px 0;
            transition: var(--transition);
        }
        
        .price-details {
            font-size: 16px;
            color: var(--gray);
            margin: 15px 0;
            line-height: 1.6;
        }
        
        .price-note {
            font-size: 14px;
            color: var(--gray);
            margin-top: 15px;
            font-style: italic;
        }
        
        .shipping-info-card {
            background: var(--light);
            border-radius: var(--border-radius);
            padding: 20px;
            margin: 25px 0;
            display: flex;
            align-items: flex-start;
            border-left: 4px solid var(--success);
        }
        
        .shipping-info-card i {
            font-size: 24px;
            color: var(--success);
            margin-left: 15px;
            flex-shrink: 0;
        }
        
        .shipping-info-card p {
            margin: 0;
            font-size: 15px;
            color: var(--secondary);
            line-height: 1.7;
        }
        
        .submit-btn {
            background: linear-gradient(120deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            border: none;
            padding: 18px 40px;
            font-size: 18px;
            border-radius: var(--border-radius);
            cursor: pointer;
            transition: var(--transition);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            margin: 20px auto 0;
            width: 100%;
            max-width: 300px;
            box-shadow: 0 6px 15px rgba(52, 152, 219, 0.4);
            position: relative;
            overflow: hidden;
        }
        
        .submit-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(52, 152, 219, 0.5);
        }
        
        .submit-btn:active {
            transform: translateY(0);
        }
        
        .submit-btn i {
            margin-right: 10px;
            font-size: 20px;
        }
        
        .submit-btn::after {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transform: translateX(-100%);
            transition: 0.5s;
        }
        
        .submit-btn:hover::after {
            transform: translateX(100%);
        }
        
        .form-steps {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            position: relative;
        }
        
        .form-steps::before {
            content: "";
            position: absolute;
            top: 20px;
            right: 0;
            left: 0;
            height: 3px;
            background: var(--light-gray);
            z-index: 1;
        }
        
        .step {
            position: relative;
            z-index: 2;
            text-align: center;
            width: 100px;
        }
        
        .step-circle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--light);
            border: 3px solid var(--light-gray);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 10px;
            font-weight: 700;
            color: var(--gray);
            transition: var(--transition);
        }
        
        .step.active .step-circle {
            background: var(--primary);
            border-color: var(--primary);
            color: white;
        }
        
        .step-label {
            font-size: 14px;
            color: var(--gray);
            font-weight: 500;
        }
        
        .step.active .step-label {
            color: var(--primary);
            font-weight: 600;
        }
        
        @media (max-width: 768px) {
            .shipping-container {
                margin: 20px;
            }
            
            .shipping-header {
                padding: 25px 20px;
            }
            
            .shipping-header h2 {
                font-size: 26px;
            }
            
            .shipping-content {
                padding: 25px;
            }
            
            .form-row {
                flex-direction: column;
                gap: 20px;
            }
            
            .shipping-types {
                grid-template-columns: 1fr;
            }
            
            .price-value {
                font-size: 28px;
            }
        }
        
        @media (max-width: 480px) {
            .shipping-container {
                margin: 10px;
            }
            
            .shipping-content {
                padding: 20px 15px;
            }
            
            .step {
                width: 70px;
            }
            
            .step-circle {
                width: 32px;
                height: 32px;
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <div class="shipping-container">
        <div class="shipping-header">
            <i class="fas fa-shipping-fast"></i>
            <h2>نموذج طلب شحن</h2>
            <p>املأ النموذج التالي لطلب خدمة الشحن الخاصة بك</p>
        </div>
        
        <div class="shipping-content">
            <div class="form-steps">
                <div class="step active">
                    <div class="step-circle">1</div>
                    <div class="step-label">معلومات الشحنة</div>
                </div>
                <div class="step">
                    <div class="step-circle">2</div>
                    <div class="step-label">نوع الشحن</div>
                </div>
                <div class="step">
                    <div class="step-circle">3</div>
                    <div class="step-label">التأكيد</div>
                </div>
            </div>
            
            <form method="post" action="" class="shipping-request-form" id="shippingForm">
                <?php wp_nonce_field('submit_shipping_request', 'shipping_request_nonce'); ?>
                
                <div class="form-group optional">
                    <label for="custom_title">اسم الشحنة (اختياري)</label>
                    <div class="input-container">
                        <i class="fas fa-box input-icon"></i>
                        <input type="text" name="custom_title" id="custom_title" class="form-input" placeholder="مثال: شحنة كتب - طلب رقم ...">
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="weight">الوزن (كجم)</label>
                        <div class="input-container">
                            <i class="fas fa-weight input-icon"></i>
                            <input type="number" name="weight" id="weight" class="form-input" step="0.01" min="0.1" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="country_id">الدولة الوجهة</label>
                        <div class="input-container">
                            <i class="fas fa-globe-asia input-icon"></i>
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
                </div>
                
                <div class="form-group">
                    <label>اختر نوع الشحن</label>
                    <div class="shipping-types">
                        <div class="shipping-type-option" data-value="land">
                            <i class="fas fa-truck shipping-type-icon"></i>
                            <div class="shipping-type-title">شحن بري</div>
                            <div class="shipping-type-desc">الأكثر اقتصادية للشحنات الكبيرة</div>
                        </div>
                        
                        <div class="shipping-type-option" data-value="sea">
                            <i class="fas fa-ship shipping-type-icon"></i>
                            <div class="shipping-type-title">شحن بحري</div>
                            <div class="shipping-type-desc">مناسب للشحنات الضخمة والغير مستعجلة</div>
                        </div>
                        
                        <div class="shipping-type-option" data-value="air">
                            <i class="fas fa-plane shipping-type-icon"></i>
                            <div class="shipping-type-title">شحن جوي</div>
                            <div class="shipping-type-desc">الأسرع للشحنات المتوسطة والخفيفة</div>
                        </div>
                        
                        <div class="shipping-type-option" data-value="fast">
                            <i class="fas fa-bolt shipping-type-icon"></i>
                            <div class="shipping-type-title">شحن سريع</div>
                            <div class="shipping-type-desc">الأسرع مع تتبع فوري - التوصيل خلال 72 ساعة</div>
                        </div>
                    </div>
                    <input type="hidden" name="shipping_type" id="shipping_type" value="" required>
                </div>
                
                <div class="form-group optional">
                    <label for="notes">ملاحظات إضافية (اختياري)</label>
                    <div class="input-container">
                        <i class="fas fa-sticky-note input-icon"></i>
                        <textarea name="notes" id="notes" class="form-input form-textarea" placeholder="أدخل أي ملاحظات تخص الشحنة..."></textarea>
                    </div>
                </div>
                
                <div class="price-estimate" id="priceEstimate">
                    <h3>التكلفة المتوقعة</h3>
                    <div class="price-value" id="priceValue">0.00 ر.س</div>
                    <div class="price-details" id="priceDetails">سيتم حساب السعر بعد إدخال جميع البيانات</div>
                    <div class="price-note">السعر النهائي قد يختلف قليلاً حسب الوزن الفعلي بعد المعاينة</div>
                </div>
                
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
                
                // تحديث خطوات التقدم
                document.querySelectorAll('.step')[1].classList.add('active');
                
                // تحديث السعر
                calculatePrice();
            });
        });
        
        // عناصر الحقول
        const weightInput = document.getElementById('weight');
        const countrySelect = document.getElementById('country_id');
        const priceEstimate = document.getElementById('priceEstimate');
        const priceValue = document.getElementById('priceValue');
        const priceDetails = document.getElementById('priceDetails');
        
        // إضافة مستمعات الأحداث للحقول
        weightInput.addEventListener('input', calculatePrice);
        countrySelect.addEventListener('change', calculatePrice);
        
        // وظيفة حساب السعر
        function calculatePrice() {
            const weight = parseFloat(weightInput.value) || 0;
            const countryId = countrySelect.value;
            const shippingType = shippingTypeInput.value;
            
            // إذا لم يتم ملء جميع الحقول المطلوبة
            if (!weight || !countryId || !shippingType) {
                priceEstimate.classList.remove('visible');
                return;
            }
            
            // في بيئة حقيقية، سنقوم بطلب AJAX للحصول على السعر الفعلي
            // لكن هنا سنستخدم قيمة افتراضية للعرض التوضيحي
            const pricePerKg = {
                'land': 25,
                'sea': 18,
                'air': 45,
                'fast': 32
            }[shippingType] || 0;
            
            const totalPrice = weight * pricePerKg;
            
            // تحديث واجهة السعر
            priceValue.textContent = totalPrice.toFixed(2) + ' ر.س';
            priceDetails.textContent = `${weight} كجم × ${pricePerKg} ر.س/كجم`;
            priceEstimate.classList.add('visible');
        }
        
        // التحقق من الحقول المطلوبة عند الإرسال
        const form = document.getElementById('shippingForm');
        form.addEventListener('submit', function(e) {
            let isValid = true;
            
            // التحقق من الوزن
            if (!weightInput.value || parseFloat(weightInput.value) <= 0) {
                isValid = false;
                highlightError(weightInput);
            }
            
            // التحقق من الدولة
            if (!countrySelect.value) {
                isValid = false;
                highlightError(countrySelect);
            }
            
            // التحقق من نوع الشحن
            if (!shippingTypeInput.value) {
                isValid = false;
                alert('يرجى اختيار نوع الشحن');
            }
            
            if (!isValid) {
                e.preventDefault();
                alert('يرجى ملء جميع الحقول المطلوبة بشكل صحيح');
            }
        });
        
        // وظيفة تسليط الضوء على الحقل الخطأ
        function highlightError(input) {
            input.style.borderColor = '#e74c3c';
            input.style.boxShadow = '0 0 0 3px rgba(231, 76, 60, 0.2)';
            
            setTimeout(() => {
                input.style.borderColor = '';
                input.style.boxShadow = '';
            }, 3000);
        }
    });
    </script>
</body>
</html>

<?php get_footer(); ?>