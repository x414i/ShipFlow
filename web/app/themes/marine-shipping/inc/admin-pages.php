<?php

function register_shipping_requests_admin_page() {
    add_menu_page(
        'إدارة طلبات الشحن',
        'طلبات الشحن',
        'manage_options',
        'manage-shipping-requests',
        'render_shipping_requests_admin_page',
        'dashicons-archive',
        6
    );
}
add_action('admin_menu', 'register_shipping_requests_admin_page');

function render_shipping_requests_admin_page() {
    if (!current_user_can('manage_options')) {
        wp_die('ليس لديك صلاحية للوصول لهذه الصفحة');
    }

    $args = [
        'post_type' => 'shipping_request',
        'posts_per_page' => 20,
        'paged' => isset($_GET['paged']) ? intval($_GET['paged']) : 1,
        'orderby' => 'date',
        'order' => 'DESC',
    ];

    if (!empty($_GET['order_status'])) {
        $args['meta_query'] = [
            [
                'key' => '_order_status',
                'value' => sanitize_text_field($_GET['order_status']),
                'compare' => '=',
            ],
        ];
    }

    $shipping_requests = new WP_Query($args);

    ?>
    <div class="wrap">
        <h1>إدارة طلبات الشحن</h1>

        <form method="get" style="margin-bottom:20px;">
            <input type="hidden" name="page" value="manage-shipping-requests" />
            <label>حالة الطلب: </label>
            <select name="order_status" onchange="this.form.submit()">
                <option value="">الكل</option>
                <option value="جديد" <?php selected($_GET['order_status'] ?? '', 'جديد'); ?>>جديد</option>
                <option value="قيد المعالجة" <?php selected($_GET['order_status'] ?? '', 'قيد المعالجة'); ?>>قيد المعالجة</option>
                <option value="تم الشحن" <?php selected($_GET['order_status'] ?? '', 'تم الشحن'); ?>>تم الشحن</option>
                <option value="تم التوصيل" <?php selected($_GET['order_status'] ?? '', 'تم التوصيل'); ?>>تم التوصيل</option>
            </select>
        </form>

        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th>رقم الطلب</th>
                    <th>الوزن (كجم)</th>
                    <th>الدولة</th>
                    <th>السعر الإجمالي</th>
                    <th>حالة الطلب</th>
                    <th>تاريخ الطلب</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($shipping_requests->have_posts()) : ?>
                    <?php while ($shipping_requests->have_posts()) : $shipping_requests->the_post(); 
                        $post_id = get_the_ID();
                        $weight = get_post_meta($post_id, '_weight', true);
                        $country_id = get_post_meta($post_id, '_country_id', true);
                        $country = get_post($country_id);
                        $total_price = get_post_meta($post_id, '_total_price', true);
                        $order_status = get_post_meta($post_id, '_order_status', true);
                    ?>
                    <tr>
                        <td><a href="<?php echo get_edit_post_link($post_id); ?>">#<?php echo $post_id; ?></a></td>
                        <td><?php echo esc_html($weight); ?></td>
                        <td><?php echo $country ? esc_html($country->post_title) : '-'; ?></td>
                        <td><?php echo $total_price ? number_format(floatval($total_price), 2) . ' ريال' : '-'; ?></td>
                        <td><?php echo esc_html($order_status); ?></td>
                        <td><?php echo get_the_date(); ?></td>
                    </tr>
                    <?php endwhile; wp_reset_postdata(); ?>
                <?php else: ?>
                    <tr><td colspan="6">لا توجد طلبات.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>

        <?php
        $total_pages = $shipping_requests->max_num_pages;
        $current_page = max(1, get_query_var('paged'));

        if ($total_pages > 1) {
            echo '<div class="tablenav"><div class="tablenav-pages">';
            echo paginate_links([
                'base' => add_query_arg('paged', '%#%'),
                'format' => '',
                'prev_text' => __('&laquo;'),
                'next_text' => __('&raquo;'),
                'total' => $total_pages,
                'current' => $current_page,
            ]);
            echo '</div></div>';
        }
        ?>
    </div>
    <?php
}
