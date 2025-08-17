<?php
/*
Template Name: خدماتنا
*/
get_header();
?>

<style>
.services-container {
    max-width: 1000px;
    margin: 50px auto;
    padding: 30px;
    background: #fff;
    border: 1px solid #ddd;
    font-family: 'Arial', sans-serif;
    line-height: 1.8;
    color: #333;
}

.services-container h2 {
    text-align: center;
    margin-bottom: 40px;
    color: #0073aa;
}

.service-box {
    display: flex;
    align-items: center;
    margin-bottom: 40px;
    border-bottom: 1px solid #eee;
    padding-bottom: 30px;
}

.service-box img {
    width: 100px;
    height: 100px;
    margin-left: 30px;
}

.service-box:last-child {
    border-bottom: none;
}

.service-content h3 {
    margin-top: 0;
    color: #0073aa;
}

.service-content p {
    font-size: 16px;
}

@media (max-width: 768px) {
    .service-box {
        flex-direction: column;
        text-align: center;
    }
    .service-box img {
        margin: 0 0 15px 0;
    }
}
</style>

<div class="services-container">
    <h2>🚚 خدمات الشحن لدينا</h2>

    <div class="service-box">
        <img src="<?php echo get_template_directory_uri(); ?>/assets/icons/sea-shipping.png" alt="شحن بحري">
        <div class="service-content">
            <h3>الشحن البحري</h3>
            <p>
                نقدم خدمات الشحن البحري الآمن والاقتصادي للحاويات والبضائع الكبيرة، مع تغطية شاملة لأغلب الموانئ العالمية.
                مناسب للشحنات الثقيلة والكميات الكبيرة.
            </p>
        </div>
    </div>

    <div class="service-box">
        <img src="<?php echo get_template_directory_uri(); ?>/assets/icons/land-shipping.png" alt="شحن بري">
        <div class="service-content">
            <h3>الشحن البري</h3>
            <p>
                شحن مرن وسريع داخل المملكة والدول المجاورة باستخدام أسطول من الشاحنات الحديثة.
                مناسب لنقل البضائع إلى المستودعات والمتاجر مباشرة.
            </p>
        </div>
    </div>

    <div class="service-box">
        <img src="<?php echo get_template_directory_uri(); ?>/assets/icons/air-shipping.png" alt="شحن جوي">
        <div class="service-content">
            <h3>الشحن الجوي</h3>
            <p>
                أسرع وسيلة لشحن الوثائق والبضائع ذات الأهمية العالية.
                نوفر خيارات شحن جوي إلى أغلب مطارات العالم بالتعاون مع شركات الطيران العالمية.
            </p>
        </div>
    </div>

    <div class="service-box">
        <img src="<?php echo get_template_directory_uri(); ?>/assets/icons/express-shipping.png" alt="شحن سريع">
        <div class="service-content">
            <h3>الشحن السريع (Express)</h3>
            <p>
                خدمة خاصة لتوصيل الطرود الصغيرة والمستعجلة خلال وقت قياسي.
                مثالية للتجار والعملاء الذين يحتاجون سرعة فائقة في التسليم.
            </p>
        </div>
    </div>
</div>

<?php get_footer(); ?>
