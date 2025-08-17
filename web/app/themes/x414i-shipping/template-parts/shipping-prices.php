<?php
/*
Template Name: قائمة الأسعار
*/

get_header();

$countries = get_posts([
    'post_type' => 'country',
    'posts_per_page' => -1,
    'orderby' => 'title',
    'order' => 'ASC'
]);
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>قائمة أسعار الشحن</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

</head>
<body>
    <div class="prices-container">
        <div class="prices-header">
            <h1><i class="fas fa-money-bill-wave"></i> قائمة أسعار الشحن</h1>
            <p class="prices-description">
                استعرض أحدث أسعار الشحن لدينا حسب البلد ونوع الخدمة. يمكنك البحث والتصفية حسب نوع الشحن لمقارنة الأسعار بسهولة.
            </p>
        </div>
<!--         
        <div class="filters-container">
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" id="searchInput" placeholder="ابحث عن دولة...">
            </div>
            
            <div class="shipping-type-filter">
                <div class="type-filter-btn active" data-type="all">
                    <i class="fas fa-globe"></i> الكل
                </div>
                <div class="type-filter-btn" data-type="land">
                    <i class="fas fa-truck"></i> بري
                </div>
                <div class="type-filter-btn" data-type="sea">
                    <i class="fas fa-ship"></i> بحري
                </div>
                <div class="type-filter-btn" data-type="air">
                    <i class="fas fa-plane"></i> جوي
                </div>
                <div class="type-filter-btn" data-type="fast">
                    <i class="fas fa-bolt"></i> سريع
                </div>
            </div>
        </div> -->
        
        <div class="prices-table-container">
            <div class="table-header">
                <div>الدولة</div>
                <div>شحن بري ($/كجم)</div>
                <div>شحن بحري ($/كجم)</div>
                <div>شحن جوي ($/كجم)</div>
                <div>شحن سريع ($/كجم)</div>
            </div>
            
            <?php foreach ($countries as $country): 
                $land  = get_post_meta($country->ID, 'price_land', true);
                $sea   = get_post_meta($country->ID, 'price_sea', true);
                $air   = get_post_meta($country->ID, 'price_air', true);
                $fast  = get_post_meta($country->ID, 'price_fast', true);
                
                $land_val = $land !== '' ? number_format($land, 2) : false;
                $sea_val = $sea !== '' ? number_format($sea, 2) : false;
                $air_val = $air !== '' ? number_format($air, 2) : false;
                $fast_val = $fast !== '' ? number_format($fast, 2) : false;
            ?>
            <div class="table-row" data-country="<?php echo esc_attr(strtolower($country->post_title)); ?>">
                <div class="country-cell">
                    <div class="country-flag">
                        <i class="fas fa-flag"></i>
                    </div>
                    <div><?php echo esc_html($country->post_title); ?></div>
                </div>
                <div class="price-value"><?php echo $land_val ? $land_val . ' $' : '<span class="price-unavailable">—</span>'; ?></div>
                <div class="price-value"><?php echo $sea_val ? $sea_val . ' $' : '<span class="price-unavailable">—</span>'; ?></div>
                <div class="price-value"><?php echo $air_val ? $air_val . ' $' : '<span class="price-unavailable">—</span>'; ?></div>
                <div class="price-value"><?php echo $fast_val ? $fast_val . ' $' : '<span class="price-unavailable">—</span>'; ?></div>
            </div>
            
            <!-- Mobile Card -->
            <div class="mobile-price-card" data-country="<?php echo esc_attr(strtolower($country->post_title)); ?>">
                <div class="mobile-card-header">
                    <div class="country-flag">
                        <i class="fas fa-flag"></i>
                    </div>
                    <h3><?php echo esc_html($country->post_title); ?></h3>
                </div>
                
                <div class="mobile-price-row">
                    <div class="mobile-price-type">
                        <i class="fas fa-truck"></i>
                        <span>شحن بري</span>
                    </div>
                    <div class="mobile-price-value"><?php echo $land_val ? $land_val . ' $' : '<span class="price-unavailable">—</span>'; ?></div>
                </div>
                
                <div class="mobile-price-row">
                    <div class="mobile-price-type">
                        <i class="fas fa-ship"></i>
                        <span>شحن بحري</span>
                    </div>
                    <div class="mobile-price-value"><?php echo $sea_val ? $sea_val . ' $' : '<span class="price-unavailable">—</span>'; ?></div>
                </div>
                
                <div class="mobile-price-row">
                    <div class="mobile-price-type">
                        <i class="fas fa-plane"></i>
                        <span>شحن جوي</span>
                    </div>
                    <div class="mobile-price-value"><?php echo $air_val ? $air_val . ' $' : '<span class="price-unavailable">—</span>'; ?></div>
                </div>
                
                <div class="mobile-price-row">
                    <div class="mobile-price-type">
                        <i class="fas fa-bolt"></i>
                        <span>شحن سريع</span>
                    </div>
                    <div class="mobile-price-value"><?php echo $fast_val ? $fast_val . ' $' : '<span class="price-unavailable">—</span>'; ?></div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        
        <!-- <div class="action-buttons">
            <button class="action-btn print-btn" onclick="window.print()">
                <i class="fas fa-print"></i> طباعة القائمة
            </button>
            <button class="action-btn contact-btn">
                <i class="fas fa-headset"></i> اتصل بنا للاستفسار
            </button>
        </div> -->
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // البحث عن الدول
            const searchInput = document.getElementById('searchInput');
            searchInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();
                const tableRows = document.querySelectorAll('.table-row, .mobile-price-card');
                
                tableRows.forEach(row => {
                    const countryName = row.getAttribute('data-country');
                    if (countryName.includes(searchTerm)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
            
            // تصفية حسب نوع الشحن
            const filterButtons = document.querySelectorAll('.type-filter-btn');
            filterButtons.forEach(button => {
                button.addEventListener('click', function() {
                    // إزالة الفعالية من جميع الأزرار
                    filterButtons.forEach(btn => btn.classList.remove('active'));
                    // إضافة الفعالية للزر المختار
                    this.classList.add('active');
                    
                    const filterType = this.getAttribute('data-type');
                    const tableRows = document.querySelectorAll('.table-row, .mobile-price-card');
                    
                    if (filterType === 'all') {
                        tableRows.forEach(row => row.style.display = '');
                        return;
                    }
                    
                    // tableRows.forEach(row => {
                    //     // الحصول على قيمة هذا النوع من الشحن
                    //     let priceValue;
                    //     if (row.classList.contains('table-row')) {
                    //         // للجداول الكبيرة
                    //         const index = Array.from(row.children).findIndex(
                    //             cell => cell.textContent.includes(getTypeLabel(filterType))
                    //         priceValue = row.children[index].textContent;
                    //     } else {
                    //         // للبطاقات الصغيرة
                    //         const priceCell = row.querySelector(`.mobile-price-type i.fa-${getTypeIcon(filterType)}`).closest('.mobile-price-row').querySelector('.mobile-price-value');
                    //         priceValue = priceCell.textContent;
                    //     }
                        
                    //     // عرض الصف إذا كان السعر متوفرًا
                    //     if (priceValue !== '—') {
                    //         row.style.display = '';
                    //     } else {
                    //         row.style.display = 'none';
                    //     }
                    // });
                });
            });
            
            // وظائف مساعدة
            function getTypeLabel(type) {
                const labels = {
                    'land': 'بري',
                    'sea': 'بحري',
                    'air': 'جوي',
                    'fast': 'سريع'
                };
                return labels[type] || '';
            }
            
            function getTypeIcon(type) {
                const icons = {
                    'land': 'truck',
                    'sea': 'ship',
                    'air': 'plane',
                    'fast': 'bolt'
                };
                return icons[type] || '';
            }
            
            // إضافة تأثيرات للصفوف
            const tableRows = document.querySelectorAll('.table-row');
            tableRows.forEach((row, index) => {
                row.style.animationDelay = `${index * 0.05}s`;
            });
            
            // إضافة تأثيرات للبطاقات
            const mobileCards = document.querySelectorAll('.mobile-price-card');
            mobileCards.forEach((card, index) => {
                card.style.animationDelay = `${index * 0.05}s`;
            });
        });
    </script>
</body>
</html>

<?php get_footer(); ?>