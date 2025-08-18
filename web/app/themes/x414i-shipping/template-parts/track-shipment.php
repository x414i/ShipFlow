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
        
      
        
        <?php
        if (isset($_GET['order_id']) && !empty($_GET['order_id']) && is_numeric($_GET['order_id'])):
            $order_id = absint($_GET['order_id']);
            $order = get_post($order_id);

            if ($order && $order->post_type === 'shipping_request'):
                $weight        = get_post_meta($order_id, '_weight', true);
                $country_id    = get_post_meta($order_id, '_country_id', true);
                $total_price   = get_post_meta($order_id, '_total_price', true);
                $order_status  = get_post_meta($order_id, '_order_status', true);
                $shipping_type = get_post_meta($order_id, '_shipping_type', true);
                $country_name  = $country_id ? get_the_title($country_id) : 'غير محدد';
                $user_info = get_userdata($order->post_author);
                $recipient_name = $user_info->display_name;
                $shipment_name = $order->post_title;
                $order_date    = get_the_date('Y-m-d', $order_id);
                $notes = get_post_meta($order_id, '_notes', true);
                $recipient_phone = get_post_meta($order_id, '_recipient_phone', true);

                //translate shipping type to Arabic
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
                
                $status_class = 'status-new';
                if ($order_status === 'قيد المراجعة') $status_class = 'status-review';
                elseif ($order_status === 'جاري الشحن') $status_class = 'status-shipping';
                elseif ($order_status === 'تم التسليم') $status_class = 'status-delivered';
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
                
                <div class="detail-card">
                    <h4><i class="fas fa-info-circle"></i> حالة الشحنة</h4>
                    <!-- <p><?php //echo esc_html($status_label); ?></p> -->
                                                <span class="status-badge <?php echo $status_class; ?>"><?php echo esc_html($order_status); ?></span>

                </div>
                <div class="detail-card">
                    <h4><i class="fas fa-box"></i> اسم الشحنة</h4>
                    <p><?php echo esc_html($shipment_name); ?></p>
                </div>
                <div class="detail-card">
                    <h4><i class="fas fa-user"></i> اسم المستلم</h4>
                    <p><?php echo esc_html($recipient_name); ?></p>
                </div>
                <div class="detail-card">
                    <h4><i class="fas fa-phone"></i> معلومات التواصل</h4>
                    <p><?php echo esc_html($recipient_phone ? $recipient_phone : $user_info->user_email); ?></p>
                </div>
                <?php if (!empty($notes)): ?>
                <div class="detail-card">
                    <h4><i class="fas fa-sticky-note"></i> ملاحظات</h4>
                    <p><?php echo esc_html($notes); ?></p>
                </div>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <div class="track-result active">
                <p style="text-align: center; color: red;">رقم الشحنة غير صحيح أو لم يتم العثور عليها.</p>
            </div>
        <?php endif; ?>
        <?php endif; ?>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const trackContainer = document.querySelector('.track-container');
            trackContainer.style.opacity = '0';
            trackContainer.style.transform = 'translateY(20px)';
            
            setTimeout(() => {
                trackContainer.style.transition = 'all 0.8s ease';
                trackContainer.style.opacity = '1';
                trackContainer.style.transform = 'translateY(0)';
            }, 100);
            
            const submitBtn = document.querySelector('.btn-submit');
            if (submitBtn) {
                submitBtn.addEventListener('mouseenter', function() {
                    this.querySelector('i').style.transform = 'rotate(-10deg)';
                });
                
                submitBtn.addEventListener('mouseleave', function() {
                    this.querySelector('i').style.transform = 'rotate(0)';
                });
            }
            
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
