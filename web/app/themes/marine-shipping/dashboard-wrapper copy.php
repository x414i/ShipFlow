<?php
/*
Template Name: لوحة التحكم
*/

if (!is_user_logged_in()) {
    wp_redirect(wp_login_url(get_permalink()));
    exit;
}

$current_user = wp_get_current_user();

// Enqueue Font Awesome and styles
wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css');
wp_enqueue_style('noto-sans-arabic', 'https://fonts.googleapis.com/css2?family=Noto+Sans+Arabic:wght@400;600;700&display=swap', [], null);

// تحميل ملف التنسيقات
$stylesheet_path = get_template_directory() . '/dashboard-styles.css';
if (file_exists($stylesheet_path)) {
    wp_enqueue_style('dashboard-styles', get_template_directory_uri() . '/dashboard-styles.css', [], filemtime($stylesheet_path));
} else {
    error_log('Dashboard stylesheet not found: ' . $stylesheet_path);
}

get_header();
?>

<style>
    .dashboard-layout {
        display: flex;
        /* min-height: 100vh; */
        direction: rtl;
        font-family: 'Noto Sans Arabic', 'Segoe UI', Tahoma, sans-serif;
        background: #f4f6f9;
    }

    .dashboard-sidebar {
        width: 280px;
        background: linear-gradient(180deg, #ffffff, #f8f9fa);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        padding: 20px;
        position: fixed;
        top: 0;
        right: 0;
        /* height: 100%; */
        overflow-y: auto;
        transition: transform 0.3s ease;
        z-index: 1000;
    }

    .dashboard-logo {
        text-align: center;
        margin-bottom: 30px;
    }

    .dashboard-logo img {
        max-width: 120px;
        border-radius: 10px;
        transition: transform 0.3s ease;
    }

    .dashboard-logo img:hover {
        transform: scale(1.05);
    }

    .user-info {
        display: flex;
        align-items: center;
        margin-bottom: 30px;
        padding: 15px;
        background: #e3f0ff;
        border-radius: 12px;
        box-shadow: 0 3px 10px rgba(0, 123, 255, 0.1);
    }

    .user-avatar {
        width: 50px;
        height: 50px;
        background: #007bff;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        font-size: 20px;
        font-weight: 600;
        margin-left: 15px;
        flex-shrink: 0;
    }

    .user-info h3 {
        margin: 0;
        font-size: 18px;
        color: #1a3c5e;
        font-weight: 600;
    }

    .dashboard-nav {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .dashboard-nav a {
        display: flex;
        align-items: center;
        padding: 12px 15px;
        color: #1a3c5e;
        text-decoration: none;
        font-size: 16px;
        border-radius: 8px;
        transition: all 0.3s ease;
    }

    .dashboard-nav a:hover {
        background: #e9f2ff;
        color: #007bff;
        transform: translateX(-5px);
    }

    .dashboard-nav a.active {
        background: #007bff;
        color: white;
        box-shadow: 0 3px 10px rgba(0, 123, 255, 0.3);
    }

    .dashboard-nav a span {
        margin-left: 10px;
        font-size: 18px;
    }

    .logout-btn {
        background: linear-gradient(45deg, #dc3545, #e4606d);
        color: white !important;
        margin-top: 20px;
        justify-content: center;
        font-weight: 600;
    }

    .logout-btn:hover {
        background: linear-gradient(45deg, #b02a37, #c82333);
        transform: translateX(0);
    }

    .mobile-menu-toggle {
        display: none;
        position: fixed;
        top: 20px;
        right: 20px;
        background: #007bff;
        color: white;
        border: none;
        padding: 12px 16px;
        font-size: 24px;
        border-radius: 8px;
        cursor: pointer;
        z-index: 1100;
        transition: background 0.3s ease;
    }

    .mobile-menu-toggle:hover {
        background: #0056b3;
    }

    .dashboard-content {
        margin-right: 300px;
        padding: 30px;
        width: 100%;
        transition: margin-right 0.3s ease;
    }

    @media (max-width: 992px) {
        .dashboard-sidebar {
            transform: translateX(100%);
        }

        .dashboard-sidebar.active {
            transform: translateX(0);
        }

        .dashboard-content {
            margin-right: 0;
        }

        .mobile-menu-toggle {
            display: block;
        }
    }

    @media (max-width: 576px) {
        .dashboard-sidebar {
            width: 100%;
            padding: 15px;
        }

        .dashboard-logo img {
            max-width: 100px;
        }

        .user-info {
            padding: 10px;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            font-size: 16px;
        }

        .dashboard-content {
            padding: 20px 10px;
        }
    }
</style>

<div class="dashboard-layout">
    <button class="mobile-menu-toggle"><i class="fas fa-bars"></i></button>
    
    <aside class="dashboard-sidebar">
        <div class="dashboard-logo">
            <?php
            $logo_path = get_template_directory() . '/assets/img/logo.png';
            if (file_exists($logo_path)) {
                echo '<img src="' . esc_url(get_template_directory_uri() . '/assets/img/logo.png') . '" alt="لوحة التحكم">';
            } else {
                echo '<h2>لوحة التحكم</h2>';
                error_log('Logo file not found: ' . $logo_path);
            }
            ?>
        </div>
        
        <div class="user-info">
            <div class="user-avatar"><?php echo esc_html(substr($current_user->display_name, 0, 1)); ?></div>
            <h3><?php echo esc_html($current_user->display_name); ?></h3>
        </div>
        
        <nav class="dashboard-nav">
            <?php
            wp_nav_menu([
                'theme_location' => 'dashboard-menu',
                'container' => false,
                'items_wrap' => '%3$s',
                'fallback_cb' => false,
                'link_before' => '<span class="fas fa-chevron-left"></span>',
            ]);
            ?>
            
            <a class="logout-btn" href="<?php echo esc_url(wp_logout_url(home_url())); ?>">
                <span class="fas fa-sign-out-alt"></span> تسجيل الخروج
            </a>
        </nav>
    </aside>

    <!-- <main class="dashboard-content"> -->
        <!-- Content will be injected here by other templates -->
    </div>
</main>



<?php get_footer(); ?>