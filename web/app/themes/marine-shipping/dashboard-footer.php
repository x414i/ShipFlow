    </main>
</div>

<?php get_footer(); ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // ... (الكود السابق لإدارة القائمة) ...
    
    // تحريك أشرطة الرسم البياني بشكل متحرك
    const chartBars = document.querySelectorAll('.chart-bar');
    chartBars.forEach(bar => {
        const height = bar.style.height;
        bar.style.height = '0';
        setTimeout(() => {
            bar.style.height = height;
        }, 300);
    });
    
    // إضافة تأثيرات تفاعلية للبطاقات
    const shipmentTypes = document.querySelectorAll('.shipment-type');
    shipmentTypes.forEach(type => {
        type.addEventListener('click', function() {
            // يمكنك هنا إضافة وظيفة للانتقال إلى صفحة تفاصيل الشحنات
            alert('انقر هنا للانتقال إلى تفاصيل شحنات ' + 
                  this.querySelector('.shipment-title').textContent);
        });
    });
});
</script>