<?php
/*
Template Name: فاتورة طلب شحن
*/

get_header();

if (!is_user_logged_in()) {
    echo '<p>يجب تسجيل الدخول لرؤية الفاتورة.</p>';
    get_footer();
    exit;
}

if (!isset($_GET['order_id']) || !is_numeric($_GET['order_id'])) {
    echo '<p>رقم الطلب غير صحيح.</p>';
    get_footer();
    exit;
}

$order_id = intval($_GET['order_id']);
$order = get_post($order_id);

if (!$order || $order->post_type !== 'shipping_request') {
    echo '<p>الطلب غير موجود.</p>';
    get_footer();
    exit;
}

// تحقق من ملكية الطلب
// if ($order->post_author !== get_current_user_id() && !current_user_can('manage_options')) {
//     echo '<p>ليس لديك صلاحية رؤية هذه الفاتورة.</p>';
//     get_footer();
//     exit;
// }

// بيانات الطلب
$weight = get_post_meta($order_id, '_weight', true);
$country_id = get_post_meta($order_id, '_country_id', true);
$total_price = get_post_meta($order_id, '_total_price', true);
$order_status = get_post_meta($order_id, '_order_status', true);
$country_name = get_the_title($country_id);
$order_date = get_the_date('F j, Y', $order_id);

$shipping_type = get_post_meta($order_id, '_shipping_type', true);

// بيانات المستخدم
$user_info = get_userdata($order->post_author);
$user_name = $user_info->display_name;
$user_email = $user_info->user_email;
?>

<style>
.invoice-container {
    max-width: 800px;
    margin: 0 auto;
    background: #fff;
    padding: 30px;
    border: 1px solid #ddd;
    font-family: 'Arial', sans-serif;
}

.invoice-container h2 {
    text-align: center;
    margin-bottom: 30px;
}

.invoice-container table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 20px;
}

.invoice-container th, .invoice-container td {
    padding: 10px;
    border: 1px solid #ddd;
    text-align: left;
}

.invoice-container .footer-note {
    text-align: center;
    font-size: 0.9em;
    color: #666;
}

@media print {
    body * {
        visibility: hidden;
    }
    .invoice-container, .invoice-container * {
        visibility: visible;
    }
    .invoice-container {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
    }
    #print-button {
        display: none;
    }
}
</style>

<div class="invoice-container">
    <h2>فاتورة طلب الشحن #<?php echo $order_id; ?></h2>

    <table>
        <tr>
            <th>اسم المستلم:</th><td><?php echo esc_html($user_name); ?></td>
        </tr>
        <tr>
            <th>البريد الإلكتروني:</th><td><?php echo esc_html($user_email); ?></td>
        </tr>
        <tr>
            <th>تاريخ الطلب:</th><td><?php echo esc_html($order_date); ?></td>
        </tr>
        <tr>
            <th>رقم الطلب:</th><td><?php echo $order_id; ?></td>
        </tr>
        <tr>
            <th>الدولة:</th><td><?php echo esc_html($country_name); ?></td>
        </tr>
        <tr>
<?php
$shipping_type = get_post_meta($order_id, '_shipping_type', true);
$type_label = '';

switch ($shipping_type) {
    case 'land':
        $type_label = 'بري';
        break;
    case 'sea':
        $type_label = 'بحري';
        break;
    case 'air':
        $type_label = 'جوي';
        break;
    case 'fast':
        $type_label = 'سريع';
        break;
    default:
        $type_label = 'غير محدد';
}
?>
<tr>
    <th>نوع الشحن:</th><td><?php echo esc_html($type_label); ?></td>
</tr>
        </tr>
        <tr>
            <th>الوزن (كجم):</th><td><?php echo esc_html($weight); ?></td>
        </tr>
        <tr>
            <th>السعر الإجمالي:</th><td><?php echo number_format($total_price, 2); ?> $</td>
        </tr>
        <tr>
            <th>حالة الطلب:</th><td><?php echo esc_html($order_status); ?></td>
        </tr>
    </table>

    <div class="footer-note">
        تم إنشاء هذه الفاتورة بواسطة نظام الشحن البحري.
    </div>

    <br>
    <div style="text-align: center;">
        <button id="print-button" onclick="window.print()">🖨️ طباعة الفاتورة</button>
    </div>
</div>

<?php get_footer(); ?>
