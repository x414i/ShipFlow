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
    // حاول هنا إزالة _ إذا لم تكن موجودة في الـ meta keys
    $land_price = get_post_meta($country->ID, 'price_land', true);
    $sea_price = get_post_meta($country->ID, 'price_sea', true);

    // طباعة للتأكد من القيم
    echo "الدولة: {$country->post_title} - بري: {$land_price} - بحري: {$sea_price}<br>";

    $shipping_prices[$country->ID] = [
        'name' => $country->post_title,
        'land' => floatval($land_price),
        'sea' => floatval($sea_price),
    ];
}
?>

<h2>آلة حاسبة تكلفة الشحن</h2>

<label for="weight">الوزن (كجم):</label>
<input type="number" id="weight" min="0" step="0.01" value="0">

<br><br>

<label for="country">اختر الدولة:</label>
<select id="country">
    <option value="">-- اختر الدولة --</option>
    <?php foreach ($shipping_prices as $id => $data): ?>
        <option value="<?php echo $id; ?>"><?php echo esc_html($data['name']); ?></option>
    <?php endforeach; ?>
</select>

<br><br>

<label for="shipping_type">نوع الشحن:</label>
<select id="shipping_type">
    <option value="land">بري</option>
    <option value="sea">بحري</option>
</select>

<br><br>

<button id="calculate_btn">احسب السعر</button>

<p>السعر المتوقع: <strong id="total_price">0</strong> دولار</p>
<p id="error_message" style="color:red;"></p>

<script>
    const shippingPrices = <?php echo json_encode($shipping_prices); ?>;

    const weightInput = document.getElementById('weight');
    const countrySelect = document.getElementById('country');
    const shippingTypeSelect = document.getElementById('shipping_type');
    const totalPriceElem = document.getElementById('total_price');
    const errorMessageElem = document.getElementById('error_message');
    const calculateBtn = document.getElementById('calculate_btn');

    function calculatePrice() {
        errorMessageElem.textContent = '';
        totalPriceElem.textContent = '0';

        const weight = parseFloat(weightInput.value);
        const countryId = countrySelect.value;
        const shippingType = shippingTypeSelect.value;

        if (!countryId) {
            errorMessageElem.textContent = 'يرجى اختيار الدولة.';
            return;
        }
        if (isNaN(weight) || weight <= 0) {
            errorMessageElem.textContent = 'يرجى إدخال وزن صحيح أكبر من صفر.';
            return;
        }
        if (!shippingType || (shippingType !== 'land' && shippingType !== 'sea')) {
            errorMessageElem.textContent = 'يرجى اختيار نوع الشحن.';
            return;
        }

        const countryData = shippingPrices[countryId];

        if (!countryData) {
            errorMessageElem.textContent = 'البيانات غير متوفرة للدولة المختارة.';
            return;
        }

        const pricePerKg = countryData[shippingType];

        // if (!pricePerKg || pricePerKg <= 0) {
        //     errorMessageElem.textContent = 'السعر غير متوفر لنوع الشحن المختار.';
        //     return;
        // }

        const total = weight * pricePerKg;
        totalPriceElem.textContent = total.toFixed(2);
    }

    calculateBtn.addEventListener('click', calculatePrice);
</script>

<?php get_footer(); ?>
