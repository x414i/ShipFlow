    </main>
</div>

<?php get_footer(); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const currentUrl = window.location.href;
    const menuLinks = document.querySelectorAll('.dashboard-nav a');
    
    menuLinks.forEach(link => {
        if (link.href === currentUrl) {
            link.classList.add('active');
        }
    });
});
</script>