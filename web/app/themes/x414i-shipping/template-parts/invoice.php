<?php
/*
Template Name: فاتورة طلب شحن
*/

get_header();

// التحقق من تسجيل الدخول
if (!is_user_logged_in()) {
    $login_url = wp_login_url(get_permalink());
    echo '<div class="error-notice"><p>يجب <a href="' . esc_url($login_url) . '">تسجيل الدخول</a> لرؤية الفاتورة.</p></div>';
    get_footer();
    exit;
}

// التحقق من وجود order_id
if (!isset($_GET['order_id']) || !is_numeric($_GET['order_id'])) {
    echo '<div class="error-notice"><p>❌ رقم الطلب غير صحيح أو غير موجود.</p></div>';
    get_footer();
    exit;
}

$order_id = absint($_GET['order_id']);
$order = get_post($order_id);

// التحقق من صحة الطلب
if (!$order || $order->post_type !== 'shipping_request') {
    echo '<div class="error-notice"><p>⚠️ الطلب المطلوب غير موجود في سجلاتنا.</p></div>';
    get_footer();
    exit;
}

// التحقق من صلاحيات المستخدم
$current_user = wp_get_current_user();
if ($order->post_author != $current_user->ID && !current_user_can('manage_options')) {
    echo '<div class="error-notice"><p>⛔ ليس لديك صلاحيات لعرض هذه الفاتورة.</p></div>';
    get_footer();
    exit;
}

// جلب بيانات الطلب
$weight = get_post_meta($order_id, '_weight', true);
$country_id = get_post_meta($order_id, '_country_id', true);
$total_price = floatval(get_post_meta($order_id, '_total_price', true));
$order_status = get_post_meta($order_id, '_order_status', true);
$shipping_type = get_post_meta($order_id, '_shipping_type', true);
$notes = get_post_meta($order_id, '_notes', true);

// جلب اسم الدولة
$country_name = ($country_id) ? get_the_title($country_id) : 'غير محدد';

// تنسيق التاريخ
$order_date = date_i18n('j F Y', strtotime($order->post_date));

// أنواع الشحن
$shipping_types = [
    'land' => 'بري',
    'sea'  => 'بحري',
    'air'  => 'جوي',
    'fast' => 'سريع'
];
$type_label = $shipping_types[$shipping_type] ?? 'غير محدد';

// بيانات المستخدم
$user_info = get_userdata($order->post_author);
$user_name = $user_info->display_name;
$user_email = $user_info->user_email;
?>

<style>
.invoice-container {
    max-width: 800px;
    margin: 2rem auto;
    background: #fff;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 0 20px rgba(0,0,0,0.1);
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    border-top: 4px solid #2c7be5;
}

.invoice-header {
    text-align: center;
    margin-bottom: 30px;
    padding-bottom: 20px;
    border-bottom: 1px dashed #e0e0e0;
}

.invoice-header h2 {
    color: #2c3e50;
    margin-bottom: 10px;
    font-size: 28px;
}

.invoice-header p {
    color: #7d8fa9;
    margin: 5px 0;
}

.invoice-logo {
    max-width: 150px;
    margin: 0 auto 15px;
}

.invoice-details {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.invoice-section {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 8px;
}

.invoice-section h3 {
    color: #2c7be5;
    margin-top: 0;
    padding-bottom: 10px;
    border-bottom: 1px solid #e0e0e0;
}

.invoice-row {
    display: flex;
    justify-content: space-between;
    padding: 10px 0;
    border-bottom: 1px solid #f0f0f0;
}

.invoice-label {
    font-weight: 600;
    color: #5e6e82;
}

.invoice-value {
    color: #2c3e50;
    text-align: right;
}

.total-row {
    background: #2c7be5;
    color: white;
    padding: 15px;
    border-radius: 6px;
    font-weight: 700;
    font-size: 18px;
    margin-top: 10px;
}

.invoice-footer {
    text-align: center;
    margin-top: 30px;
    padding-top: 20px;
    border-top: 1px dashed #e0e0e0;
    color: #7d8fa9;
    font-size: 14px;
}

.print-button {
    background: #2c7be5;
    color: white;
    border: none;
    padding: 12px 25px;
    border-radius: 5px;
    font-size: 16px;
    cursor: pointer;
    transition: all 0.3s;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    margin: 20px auto;
}

.print-button:hover {
    background: #1a68d1;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(44, 123, 229, 0.3);
}

.error-notice {
    max-width: 800px;
    margin: 2rem auto;
    padding: 20px;
    background: #fff8f8;
    border: 1px solid #ffdddd;
    border-radius: 8px;
    text-align: center;
}

.error-notice p {
    margin: 0;
    font-size: 18px;
    color: #e74c3c;
}

@media print {
    body {
        background: white !important;
    }
    .invoice-container {
        box-shadow: none;
        border: none;
        padding: 0;
        max-width: 100%;
    }
    .print-button {
        display: none;
    }
}
</style>

<div class="invoice-container">
    <div class="invoice-header">
        <h2>فاتورة طلب شحن</h2>
        <p>رقم الفاتورة: #<?php echo absint($order_id); ?></p>
        <p>تاريخ الفاتورة: <?php echo esc_html($order_date); ?></p>
    </div>
    
    <div class="invoice-details">
        <div class="invoice-section">
            <h3>معلومات العميل</h3>
            <div class="invoice-row">
                <span class="invoice-label">الاسم:</span>
                <span class="invoice-value"><?php echo esc_html($user_name); ?></span>
            </div>
            <div class="invoice-row">
                <span class="invoice-label">البريد الإلكتروني:</span>
                <span class="invoice-value"><?php echo esc_html($user_email); ?></span>
            </div>
        </div>
        
        <div class="invoice-section">
            <h3>معلومات الشحنة</h3>
            <div class="invoice-row">
                <span class="invoice-label">اسم الشحنة:</span>
                <span class="invoice-value"><?php echo esc_html($order->post_title); ?></span>
            </div>
            <div class="invoice-row">
                <span class="invoice-label">حالة الطلب:</span>
                <span class="invoice-value"><?php echo esc_html($order_status); ?></span>
            </div>
        </div>
    </div>
    
    <div class="invoice-section">
        <h3>تفاصيل الشحن</h3>
        <div class="invoice-row">
            <span class="invoice-label">الدولة:</span>
            <span class="invoice-value"><?php echo esc_html($country_name); ?></span>
        </div>
        <div class="invoice-row">
            <span class="invoice-label">نوع الشحن:</span>
            <span class="invoice-value"><?php echo esc_html($type_label); ?></span>
        </div>
        <div class="invoice-row">
            <span class="invoice-label">الوزن (كجم):</span>
            <span class="invoice-value"><?php echo esc_html($weight); ?></span>
        </div>
        <div class="invoice-row total-row">
            <span class="invoice-label">المبلغ الإجمالي:</span>
            <span class="invoice-value"><?php echo number_format($total_price, 2); ?> دولار أمريكي</span>
        </div>
    </div>
    
    <?php if (!empty($notes)): ?>
    <div class="invoice-section">
        <h3>ملاحظات إضافية</h3>
        <p><?php echo nl2br(esc_html($notes)); ?></p>
    </div>
    <?php endif; ?>
    
    <!-- <div class="invoice-footer">
        <p>شكراً لثقتكم بنا • هذه الفاتورة صادرة إلكترونياً ولا تحتاج إلى ختم</p>
        <p>للاستفسارات: support@example.com • هاتف: +966112345678</p>
    </div> -->
    
    <div style="text-align: center;">
        <button class="print-button" onclick="window.print()">
            <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                <path d="M5 1a2 2 0 0 0-2 2v1h10V3a2 2 0 0 0-2-2H5zm6 8H5a1 1 0 0 0-1 1v3a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1v-3a1 1 0 0 0-1-1z"/>
                <path d="M0 7a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2h-1v-2a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v2H2a2 2 0 0 1-2-2V7zm2.5 1a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1z"/>
            </svg>
            طباعة الفاتورة
        </button>
    </div>
</div>

<?php get_footer(); ?>