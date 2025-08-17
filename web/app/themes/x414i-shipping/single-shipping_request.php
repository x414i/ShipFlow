<?php
get_header();

if (have_posts()) :
    while (have_posts()) : the_post();

    $current_user_id = get_current_user_id();
    $post_author_id = get_the_author_meta('ID');

    // ✅ السماح للمسؤول أو مالك الطلب فقط بمشاهدة الفاتورة
    if (!current_user_can('manage_options') && $current_user_id !== $post_author_id) {
        echo '<p>🚫 ليس لديك صلاحية لعرض هذه الفاتورة.</p>';
        get_footer();
        exit;
    }

    // ✅ جلب بيانات الطلب
    $weight = get_post_meta(get_the_ID(), '_weight', true);
    $country_id = get_post_meta(get_the_ID(), '_country_id', true);
    $country_title = $country_id ? get_the_title($country_id) : '-';
    $shipping_type = $country_id ? get_post_meta($country_id, '_shipping_type', true) : '-';
    $price_per_kg = $country_id ? get_post_meta($country_id, '_price_per_kg', true) : '-';
    $total_price = get_post_meta(get_the_ID(), '_total_price', true);
    $order_status = get_post_meta(get_the_ID(), '_order_status', true);
    $date = get_the_date();

    ?>

    <div class="invoice">
        <h2>🧾 فاتورة طلب الشحن</h2>

        <table>
            <tr><th>رقم الطلب:</th><td>#<?php echo get_the_ID(); ?></td></tr>
            <tr><th>تاريخ الطلب:</th><td><?php echo esc_html($date); ?></td></tr>
            <tr><th>الوزن:</th><td><?php echo esc_html($weight); ?> كجم</td></tr>
            <tr><th>الدولة:</th><td><?php echo esc_html($country_title); ?></td></tr>
            <tr><th>نوع الشحن:</th><td><?php echo  esc_html($shipping_type); ?></td></tr>
            <tr><th>سعر الكيلو:</th><td><?php echo $price_per_kg > 0 ? number_format($price_per_kg, 2) : 0 ; ?> $</td></tr>
            <tr><th>السعر الإجمالي:</th><td><strong><?php echo $total_price > 0 ?  number_format($total_price, 2) : 0; ?> $</strong></td></tr>
            <tr><th>الحالة:</th><td><?php echo esc_html($order_status); ?></td></tr>
        </table>

        <button onclick="window.print()">🖨️ طباعة الفاتورة</button>
    </div>

    <style>
        .invoice {
            max-width: 600px;
            margin: 40px auto;
            padding: 20px;
            border: 1px solid #ddd;
            background: #fff;
        }

        .invoice table {
            width: 100%;
            border-collapse: collapse;
        }

        .invoice th, .invoice td {
            text-align: right;
            padding: 8px;
            border-bottom: 1px solid #eee;
        }

        .invoice button {
            margin-top: 20px;
            padding: 10px 20px;
            background: #2271b1;
            color: white;
            border: none;
            cursor: pointer;
        }

        @media print {
            button {
                display: none;
            }
        }
    </style>

    <?php

    endwhile;
endif;

// get_footer();
