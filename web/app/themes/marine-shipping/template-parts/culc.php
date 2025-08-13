<?php
/*
Template Name: آلة حاسبة الشحن
*/

get_header();

$countries = get_posts([
    'post_type' => 'country',
    'posts_per_page' => -1,
    'orderby' => 'title',
    'order' => 'ASC',
]);

$shipping_prices = [];
foreach ($countries as $country) {
    $land_price = get_post_meta($country->ID, 'price_land', true);
    $sea_price = get_post_meta($country->ID, 'price_sea', true);
    $price_air = get_post_meta($country->ID, 'price_air', true);
    $price_fast = get_post_meta($country->ID, 'price_fast', true);

    $shipping_prices[$country->ID] = [
        'name' => $country->post_title,
        'land' => floatval($land_price),
        'sea' => floatval($sea_price),
        'air' => floatval($price_air),
        'fast' => floatval($price_fast),
    ];
}

?>


<div class="calculator-page">
    <div class="calculator-container">
        <h2 class="title-calculator">آلة حاسبة تكلفة الشحن</h2>
        
        <form class="calculator-form">
            <div>
                <label for="weight"><i class="fas fa-weight-hanging"></i> الوزن (كجم):</label>
                <input type="number" id="weight" min="0" step="0.01" value="0" placeholder="أدخل الوزن">
            </div>
            
            <div>
                <label for="country"><i class="fas fa-globe-asia"></i> اختر الدولة:</label>
                <select id="country">
                    <option value="">-- اختر الدولة --</option>
                    <?php foreach ($shipping_prices as $id => $data): ?>
                        <option value="<?php echo $id; ?>"><?php echo esc_html($data['name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="full-width">
                <label><i class="fas fa-shipping-fast"></i> نوع الشحن:</label>
                <div class="shipping-types-info">
                    <div class="shipping-type-card land-type active" data-type="land">
                        <i class="fas fa-truck"></i>
                        <h4>بري</h4>
                    </div>
                    <div class="shipping-type-card sea-type" data-type="sea">
                        <i class="fas fa-ship"></i>
                        <h4>بحري</h4>
                    </div>
                    <div class="shipping-type-card air-type" data-type="air">
                        <i class="fas fa-plane"></i>
                        <h4>جوي</h4>
                    </div>
                    <div class="shipping-type-card fast-type" data-type="fast">
                        <i class="fas fa-bolt"></i>
                        <h4>سريع</h4>
                    </div>
                </div>
                <input type="hidden" id="shipping_type" value="land">
            </div>
            
            <button type="button" id="calculate_btn">احسب تكلفة الشحن</button>
            
            <div class="result-container">
                <p class="result-title">السعر المتوقع:</p>
                <p id="total_price">0 دولار</p>
                <div class="shipping-type-icon">
                    <i class="fas fa-truck"></i>
                </div>
            </div>
            
            <p id="error_message"></p>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const shippingPrices = <?php echo json_encode($shipping_prices); ?>;
    const weightInput = document.getElementById('weight');
    const countrySelect = document.getElementById('country');
    const shippingTypeInput = document.getElementById('shipping_type');
    const totalPriceElem = document.getElementById('total_price');
    const errorMessageElem = document.getElementById('error_message');
    const calculateBtn = document.getElementById('calculate_btn');
    const shippingTypeCards = document.querySelectorAll('.shipping-type-card');
    const shippingTypeIcon = document.querySelector('.shipping-type-icon i');

    // تحديث نوع الشحن عند النقر على البطاقات
    shippingTypeCards.forEach(card => {
        card.addEventListener('click', function() {
            // إزالة الفعالية من جميع البطاقات
            shippingTypeCards.forEach(c => c.classList.remove('active'));
            // إضافة الفعالية للبطاقة المختارة
            this.classList.add('active');
            
            // تحديث قيمة نوع الشحن
            const type = this.getAttribute('data-type');
            shippingTypeInput.value = type;
            
            // تحديث الأيقونة
            const iconClass = this.querySelector('i').className;
            shippingTypeIcon.className = iconClass;
        });
    });

    // دالة لحساب السعر
    function calculatePrice() {
        errorMessageElem.textContent = '';
        errorMessageElem.style.display = 'none';
        
        const weight = parseFloat(weightInput.value);
        const countryId = countrySelect.value;
        const shippingType = shippingTypeInput.value;

        // التحقق من صحة المدخلات
        if (!countryId) {
            showError('يرجى اختيار الدولة.');
            return;
        }
        
        if (isNaN(weight) || weight <= 0) {
            showError('يرجى إدخال وزن صحيح أكبر من صفر.');
            return;
        }
        
        const countryData = shippingPrices[countryId];
        
        if (!countryData) {
            showError('البيانات غير متوفرة للدولة المختارة.');
            return;
        }
        
        const pricePerKg = countryData[shippingType];
        
        if (isNaN(pricePerKg) || pricePerKg <= 0) {
            showError('السعر غير متوفر لنوع الشحن المختار.');
            return;
        }
        
        const total = weight * pricePerKg;
        
        // عرض النتيجة مع تأثير متحرك
        totalPriceElem.style.opacity = '0';
        totalPriceElem.style.transform = 'translateY(20px)';
        totalPriceElem.textContent = total.toFixed(2) + ' دولار';
        
        setTimeout(() => {
            totalPriceElem.style.transition = 'all 0.5s ease';
            totalPriceElem.style.opacity = '1';
            totalPriceElem.style.transform = 'translateY(0)';
        }, 10);
    }

    // دالة لعرض الأخطاء
    function showError(message) {
        errorMessageElem.textContent = message;
        errorMessageElem.style.display = 'block';
        
        // إخفاء الرسالة بعد 5 ثواني
        setTimeout(() => {
            errorMessageElem.style.display = 'none';
        }, 5000);
    }

    // إضافة حدث النقر على زر الحساب
    calculateBtn.addEventListener('click', calculatePrice);
    
    // إضافة حدث تغيير للدولة والوزن لحساب السعر تلقائياً
    weightInput.addEventListener('input', function() {
        if (this.value > 0 && countrySelect.value) {
            calculatePrice();
        }
    });
    
    countrySelect.addEventListener('change', function() {
        if (this.value && weightInput.value > 0) {
            calculatePrice();
        }
    });
    
    // تحميل مكتبة Font Awesome للأيقونات
    const faScript = document.createElement('script');
    faScript.src = 'https://kit.fontawesome.com/a076d05399.js';
    faScript.crossOrigin = 'anonymous';
    document.head.appendChild(faScript);
});
</script>

<?php get_footer(); ?>