<?php
/*
Template Name: الصفحة الرئيسية
*/
get_header();
?>

<style>
.home-hero {
    background: #f4f4f4;
    padding: 50px 20px;
    text-align: center;
}
.home-hero h1 {
    margin-bottom: 10px;
    font-size: 36px;
}
.home-hero p {
    font-size: 18px;
    color: #555;
}

.quick-calc, .quick-links, .offers-section {
    margin: 40px auto;
    max-width: 800px;
    padding: 20px;
    background: #fff;
    border: 1px solid #ddd;
}

.quick-calc input, .quick-calc select {
    width: calc(33% - 10px);
    margin: 5px;
    padding: 10px;
}

.quick-links {
    display: flex;
    justify-content: space-around;
    flex-wrap: wrap;
}
.quick-links a {
    display: block;
    background: #0073aa;
    color: #fff;
    padding: 15px 25px;
    margin: 10px;
    text-decoration: none;
    border-radius: 5px;
}
.quick-links a:hover {
    background: #005e8a;
}

.offers-section {
    background: #fff3cd;
    border-color: #ffeeba;
    text-align: center;
}
</style>

<div class="home-hero">
    <h1>مرحبًا بكم في شركة الشحن البحري والبري</h1>
    <p>نوصّل شحنتك بأمان وسرعة إلى أي مكان في العالم.</p>
</div>

<div class="quick-calc">
    <h2>حاسبة تكلفة سريعة</h2>
    <form id="quick-shipping-form">
        <select id="calc_country">
            <option value="">اختر الدولة</option>
            <?php
            $countries = get_posts([
                'post_type' => 'country',
                'posts_per_page' => -1,
                'orderby' => 'title',
                'order' => 'ASC'
            ]);
            foreach ($countries as $country) {
                echo '<option value="' . $country->ID . '">' . esc_html($country->post_title) . '</option>';
            }
            ?>
        </select>

        <select id="calc_type">
            <option value="">نوع الشحن</option>
            <option value="land">بري</option>
            <option value="sea">بحري</option>
            <option value="air">جوي</option>
            <option value="fast">سريع</option>
        </select>

        <input type="number" id="calc_weight" placeholder="الوزن (كجم)" min="0" step="0.1">

        <br><br>
        <button type="button" onclick="calculateQuick()">احسب</button>
        <p id="quick_result" style="margin-top: 10px; font-weight: bold;"></p>
    </form>
</div>

<div class="quick-links">
    <a href="/12-2">🚚 طلب شحنة</a>
    <a href="/orders">📦 تتبع شحنة</a>
    <a href="/قائمة-الأسعار">💲 قائمة الأسعار</a>
</div>

<!-- <div class="offers-section">
    <h2>🔥 عروض وخصومات حالية</h2>
    <p>خصم 10% على الشحن السريع إلى السعودية – سارع بالاستفادة قبل نهاية الشهر!</p>
</div> -->

<script>
const shippingPrices = <?php
    $shipping_data = [];
    foreach ($countries as $country) {
        $shipping_data[$country->ID] = [
            'land' => floatval(get_post_meta($country->ID, 'price_land', true)),
            'sea'  => floatval(get_post_meta($country->ID, 'price_sea', true)),
            'air'  => floatval(get_post_meta($country->ID, 'price_air', true)),
            'fast' => floatval(get_post_meta($country->ID, 'price_fast', true)),
        ];
    }
    echo json_encode($shipping_data);
?>;

function calculateQuick() {
    const countryId = document.getElementById('calc_country').value;
    const type = document.getElementById('calc_type').value;
    const weight = parseFloat(document.getElementById('calc_weight').value);
    const resultElem = document.getElementById('quick_result');

    if (!countryId || !type || isNaN(weight) || weight <= 0) {
        resultElem.textContent = "يرجى تعبئة جميع الحقول بشكل صحيح.";
        return;
    }

    const pricePerKg = shippingPrices[countryId][type];

    if (!pricePerKg || pricePerKg <= 0) {
        resultElem.textContent = "السعر غير متوفر لنوع الشحن المختار.";
        return;
    }

    const total = weight * pricePerKg;
    resultElem.textContent = `السعر التقريبي: ${total.toFixed(2)} دولار`;
}
</script>

<?php get_footer(); ?>
