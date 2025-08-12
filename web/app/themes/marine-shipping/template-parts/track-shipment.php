
<?php
/*
Template Name: تتبع شحنة
*/

get_header();
?>

<style>
.track-container {
    max-width: 600px;
    margin: 50px auto;
    padding: 30px;
    background: #fff;
    border: 1px solid #ddd;
    text-align: center;
}
.track-container input[type="number"] {
    padding: 10px;
    width: 80%;
    margin-bottom: 20px;
}
.track-container button {
    padding: 10px 20px;
    background: #0073aa;
    color: #fff;
    border: none;
    cursor: pointer;
}
.track-container button:hover {
    background: #005e8a;
}
.track-result {
    margin-top: 30px;
    text-align: left;
    border-top: 1px solid #ddd;
    padding-top: 20px;
}
</style>

<div class="track-container">
    <h2>تتبع الشحنة</h2>
    <p>يرجى إدخال رقم الطلب لتتبع حالته.</p>

    <form method="get">
        <input type="number" name="order_id" placeholder="رقم الطلب" required>
        <br>
        <button type="submit">🔍 تتبع</button>
    </form>

    <?php
    if (isset($_GET['order_id']) && is_numeric($_GET['order_id'])):
        $order_id = intval($_GET['order_id']);
        $order = get_post($order_id);

        if ($order && $order->post_type === 'shipping_request'):
            $weight        = get_post_meta($order_id, '_weight', true);
            $country_id    = get_post_meta($order_id, '_country_id', true);
            $total_price   = get_post_meta($order_id, '_total_price', true);
            $order_status  = get_post_meta($order_id, '_order_status', true);
            $shipping_type = get_post_meta($order_id, '_shipping_type', true);
            $tracking_code = get_post_meta($order_id, '_tracking_code', true);
            $country_name  = get_the_title($country_id);
            $order_date    = get_the_date('Y-m-d', $order_id);

            // ترجمة نوع الشحن
            $type_label = '';
            switch ($shipping_type) {
                case 'land': $type_label = 'بري'; break;
                case 'sea': $type_label = 'بحري'; break;
                case 'air': $type_label = 'جوي'; break;
                case 'fast': $type_label = 'سريع'; break;
                default: $type_label = 'غير محدد'; break;
            }
    ?>
        <div class="track-result">
            <h3>تفاصيل الشحنة</h3>
            <p><strong>رقم الطلب:</strong> <?php echo $order_id; ?></p>
            <p><strong>الدولة:</strong> <?php echo esc_html($country_name); ?></p>
            <p><strong>نوع الشحن:</strong> <?php echo esc_html($type_label); ?></p>
            <p><strong>الوزن:</strong> <?php echo esc_html($weight); ?> كجم</p>
            <p><strong>السعر:</strong> <?php echo number_format($total_price, 2); ?> $</p>
            <p><strong>حالة الطلب:</strong> <?php echo esc_html($order_status); ?></p>
            <p><strong>تاريخ الطلب:</strong> <?php echo esc_html($order_date); ?></p>
            <?php if (!empty($tracking_code)): ?>
                <p><strong>رقم التتبع:</strong> <?php echo esc_html($tracking_code); ?></p>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <p style="color: red;">عذرًا، لم يتم العثور على طلب بهذا الرقم.</p>
    <?php endif; ?>
    <?php endif; ?>
</div>

<?php get_footer(); ?>
