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

<script>
document.addEventListener('DOMContentLoaded', function() {
    const menuToggle = document.querySelector('.mobile-menu-toggle');
    const sidebar = document.querySelector('.dashboard-sidebar');
    const content = document.querySelector('.dashboard-content');

    // Toggle sidebar on mobile
    menuToggle.addEventListener('click', function() {
        sidebar.classList.toggle('active');
        content.style.marginRight = sidebar.classList.contains('active') ? '280px' : '0';
    });

    // Highlight active menu item
    const navLinks = document.querySelectorAll('.dashboard-nav a:not(.logout-btn)');
    navLinks.forEach(link => {
        if (link.href === window.location.href) {
            link.classList.add('active');
        }
    });

    // Close sidebar when clicking outside on mobile
    document.addEventListener('click', function(e) {
        if (window.innerWidth <= 992 && !sidebar.contains(e.target) && !menuToggle.contains(e.target)) {
            sidebar.classList.remove('active');
            content.style.marginRight = '0';
        }
    });
});
</script>