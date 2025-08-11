<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title><?php wp_title(); ?></title>
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<header>
    <h1><a href="<?php echo esc_url(home_url('/')); ?>"><?php bloginfo('name'); ?></a></h1>

    <nav>
        <ul>
            <li><a href="<?php echo esc_url(home_url('/')); ?>">الرئيسية</a></li>
            <li><a href="<?php echo esc_url(home_url('/shipping-request-form')); ?>">طلب شحن جديد</a></li>
            <li><a href="<?php echo esc_url(home_url('/my-requests')); ?>">طلباتي</a></li>
            <!-- <li><a href="<?php echo esc_url(admin_url('edit.php?post_type=shipping_request')); ?>">إدارة الطلبات (للأدمن)</a></li> -->
            <!-- <li><a href="<?php echo esc_url(admin_url('edit.php?post_type=country')); ?>">إدارة الدول (للأدمن)</a></li> -->
            <!-- <li><a href="<?php echo esc_url(wp_logout_url(home_url())); ?>">تسجيل خروج</a></li> -->
        </ul>
    </nav>
</header>
<main>
