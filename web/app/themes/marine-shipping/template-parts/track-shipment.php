<?php
/*
Template Name: تتبع شحنة
*/

get_header();
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تتبع شحنة</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
   
</head>
<body>
    <div class="track-container">
        <h2 class="title-track"><i class="fas fa-truck-loading"></i> تتبع الشحنة</h2>
        <p class="para-track">أدخل رقم الشحنة الخاص بك لمتابعة حالة شحنتك ومعرفة أحدث التحديثات حول موقعها وتاريخ وصولها المتوقع</p>
        
        <form method="get" class="track-form">
            <div class="search-container">
                <input type="number" name="order_id" placeholder="أدخل رقم الشحنة" class="number-request" required>
                <button type="submit" class="btn-submit" style="position: absolute; top: 50%; left: 15px; transform: translateY(-50%); background: none; border: none; padding: 0; box-shadow: none; color: var(--gray);">
                    <i class="fas fa-search"></i>
                </button>
            </div>
            <button type="submit" class="btn-submit">
                <i class="fas fa-search"></i> تتبع الشحنة
            </button>
        </form>
        
        <div class="error-message <?php echo (isset($_GET['order_id']) && (!$order || $order->post_type !== 'shipping_request')) ? 'active' : ''; ?>">
            <i class="fas fa-exclamation-circle"></i>
            <p>عذرًا، لم يتم العثور على شحنة بهذا الرقم. يرجى التأكد من الرقم والمحاولة مرة أخرى.</p>
        </div>
        
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
                $country_name  = $country_id ? get_the_title($country_id) : 'غير محدد';
                $order_date    = get_the_date('Y-m-d', $order_id);

                // ترجمة نوع الشحن
                $type_label = '';
                $type_icon = '';
                switch ($shipping_type) {
                    case 'land': 
                        $type_label = 'بري'; 
                        $type_icon = 'truck';
                        break;
                    case 'sea': 
                        $type_label = 'بحري'; 
                        $type_icon = 'ship';
                        break;
                    case 'air': 
                        $type_label = 'جوي'; 
                        $type_icon = 'plane';
                        break;
                    case 'fast': 
                        $type_label = 'سريع'; 
                        $type_icon = 'bolt';
                        break;
                    default: 
                        $type_label = 'غير محدد'; 
                        $type_icon = 'box';
                        break;
                }
                
                // حالة الطلب مع التقدم
                $status_progress = 60; // يمكن تعديل هذه القيمة حسب حالة الطلب
        ?>
        <div class="track-result active">
            <h3><i class="fas fa-box-open"></i> تفاصيل الشحنة #<?php echo $order_id; ?></h3>
            
            <div class="details-grid">
                <div class="detail-card">
                    <h4><i class="fas fa-globe-asia"></i> الدولة</h4>
                    <p><?php echo esc_html($country_name); ?></p>
                </div>
                
                <div class="detail-card">
                    <h4><i class="fas fa-shipping-fast"></i> نوع الشحن</h4>
                    <p><i class="fas fa-<?php echo $type_icon; ?>"></i> <?php echo esc_html($type_label); ?></p>
                </div>
                
                <div class="detail-card">
                    <h4><i class="fas fa-weight-hanging"></i> الوزن</h4>
                    <p><?php echo esc_html($weight); ?> كجم</p>
                </div>
                
                <div class="detail-card">
                    <h4><i class="fas fa-money-bill-wave"></i> السعر الإجمالي</h4>
                    <p><?php echo number_format($total_price, 2); ?> $</p>
                </div>
                
                <div class="detail-card">
                    <h4><i class="fas fa-calendar-alt"></i> تاريخ الطلب</h4>
                    <p><?php echo esc_html($order_date); ?></p>
                </div>
                
                <!-- <div class="detail-card">
                    <h4><i class="fas fa-barcode"></i> رقم التتبع</h4>
                    <p><?php //echo !empty($tracking_code) ? esc_html($tracking_code) : 'غير متوفر'; ?></p>
                </div> -->
            </div>
            
             <!-- <div class="status-container">
                <h4><i class="fas fa-tasks"></i> حالة الشحنة: <span style="color: var(--primary);"><?php // echo esc_html($order_status); ?></span></h4>
                
                <div class="status-bar">
                    <div class="status-progress" style="width: <?php  //echo $status_progress; ?>%;"></div>
                </div> -->
                
                <!-- <div class="status-steps">
                    <div class="status-step completed">
                        <span>تم الطلب</span>
                    </div>
                    <div class="status-step completed">
                        <span>قيد المعالجة</span>
                    </div>
                    <div class="status-step <?php echo $status_progress >= 60 ? 'active' : ''; ?>">
                        <span>في الطريق</span>
                    </div>
                    <div class="status-step <?php echo $status_progress >= 80 ? 'active' : ''; ?>">
                        <span>وصلت إلى المركز</span>
                    </div>
                    <div class="status-step <?php echo $status_progress >= 100 ? 'completed' : ''; ?>">
                        <span>تم التوصيل</span>
                    </div>
                </div> -->
            </div> 
            
            <!-- <div class="tracking-history">
                <h4><i class="fas fa-history"></i> سجل التتبع</h4>
                <ul class="history-list">
                    <li class="history-item">
                        <div class="history-date">2023-07-15<br>10:30 ص</div>
                        <div class="history-content">
                            <div class="history-title">تم استلام الطلب</div>
                            <div class="history-location"><i class="fas fa-map-marker-alt"></i> الرياض، السعودية</div>
                        </div>
                    </li>
                    <li class="history-item">
                        <div class="history-date">2023-07-16<br>02:15 م</div>
                        <div class="history-content">
                            <div class="history-title">الشحنة قيد المعالجة</div>
                            <div class="history-location"><i class="fas fa-map-marker-alt"></i> الرياض، السعودية</div>
                        </div>
                    </li>
                    <li class="history-item">
                        <div class="history-date">2023-07-18<br>09:45 ص</div>
                        <div class="history-content">
                            <div class="history-title">الشحنة في الطريق</div>
                            <div class="history-location"><i class="fas fa-map-marker-alt"></i> دبي، الإمارات</div>
                        </div>
                    </li>
                    <li class="history-item">
                        <div class="history-date">2023-07-20<br>11:20 ص</div>
                        <div class="history-content">
                            <div class="history-title">وصلت إلى مركز التوزيع</div>
                            <div class="history-location"><i class="fas fa-map-marker-alt"></i> <?php echo esc_html($country_name); ?></div>
                        </div>
                    </li>
                </ul>
            </div> -->
<!--             
            <div class="tracking-actions">
                <div class="action-btn">
                    <i class="fas fa-print"></i>
                    <span>طباعة التفاصيل</span>
                </div>
                <div class="action-btn">
                    <i class="fas fa-envelope"></i>
                    <span>إرسال بالبريد</span>
                </div>
                <div class="action-btn">
                    <i class="fas fa-headset"></i>
                    <span>الدعم الفني</span>
                </div>
                <div class="action-btn">
                    <i class="fas fa-redo"></i>
                    <span>تتبع شحنة أخرى</span>
                </div>
            </div> -->
        </div>
        <?php endif; ?>
        <?php endif; ?>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // إضافة تأثيرات عند التحميل
            const trackContainer = document.querySelector('.track-container');
            trackContainer.style.opacity = '0';
            trackContainer.style.transform = 'translateY(20px)';
            
            setTimeout(() => {
                trackContainer.style.transition = 'all 0.8s ease';
                trackContainer.style.opacity = '1';
                trackContainer.style.transform = 'translateY(0)';
            }, 100);
            
            // إضافة تأثير للزر عند التحويم
            const submitBtn = document.querySelector('.btn-submit');
            if (submitBtn) {
                submitBtn.addEventListener('mouseenter', function() {
                    this.querySelector('i').style.transform = 'rotate(-10deg)';
                });
                
                submitBtn.addEventListener('mouseleave', function() {
                    this.querySelector('i').style.transform = 'rotate(0)';
                });
            }
            
            // إظهار رسالة الخطأ لمدة 5 ثواني
            const errorMessage = document.querySelector('.error-message.active');
            if (errorMessage) {
                setTimeout(() => {
                    errorMessage.style.opacity = '1';
                    setTimeout(() => {
                        errorMessage.style.transition = 'opacity 0.5s ease';
                        errorMessage.style.opacity = '0';
                        setTimeout(() => {
                            errorMessage.style.display = 'none';
                        }, 500);
                    }, 5000);
                }, 100);
            }
        });
    </script>
</body>
</html>

<?php get_footer(); ?>