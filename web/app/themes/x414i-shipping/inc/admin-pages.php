<?php

function render_shipping_requests_admin_page() {
    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have sufficient permissions to access this page.', 'x414i-shipping'));
    }

    $args = [
        'post_type' => 'shipping_request',
        'posts_per_page' => 20,
        'paged' => isset($_GET['paged']) ? intval($_GET['paged']) : 1,
        'orderby' => 'date',
        'order' => 'DESC',
    ];

    $selected_status = !empty($_GET['order_status']) ? sanitize_text_field($_GET['order_status']) : '';

    if ($selected_status) {
        $args['meta_query'] = [
            [
                'key' => '_order_status',
                'value' => $selected_status,
                'compare' => '=',
            ],
        ];
    }

    $shipping_requests = new WP_Query($args);

    $statuses = [
        'new' => __('New', 'x414i-shipping'),
        'processing' => __('Processing', 'x414i-shipping'),
        'shipped' => __('Shipped', 'x414i-shipping'),
        'delivered' => __('Delivered', 'x414i-shipping'),
    ];

    ?>
    <div class="wrap">
        <h1><?php _e('Manage Shipping Requests', 'x414i-shipping'); ?></h1>

        <form method="get" style="margin-bottom:20px;">
            <input type="hidden" name="page" value="manage-shipping-requests" />
            <label for="order_status"><?php _e('Order Status:', 'x414i-shipping'); ?> </label>
            <select name="order_status" id="order_status" onchange="this.form.submit()">
                <option value=""><?php _e('All', 'x414i-shipping'); ?></option>
                <?php foreach ($statuses as $slug => $label) : ?>
                    <option value="<?php echo esc_attr($slug); ?>" <?php selected($selected_status, $slug); ?>><?php echo esc_html($label); ?></option>
                <?php endforeach; ?>
            </select>
        </form>

        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th><?php _e('Order Number', 'x414i-shipping'); ?></th>
                    <th><?php _e('Weight (kg)', 'x414i-shipping'); ?></th>
                    <th><?php _e('Country', 'x414i-shipping'); ?></th>
                    <th><?php _e('Total Price', 'x414i-shipping'); ?></th>
                    <th><?php _e('Order Status', 'x414i-shipping'); ?></th>
                    <th><?php _e('Order Date', 'x414i-shipping'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php if ($shipping_requests->have_posts()) : ?>
                    <?php while ($shipping_requests->have_posts()) : $shipping_requests->the_post(); 
                        $post_id = get_the_ID();
                        $weight = get_post_meta($post_id, '_weight', true);
                        $country_id = get_post_meta($post_id, '_country_id', true);
                        $country_post = get_post($country_id);
                        $country_name = $country_post ? get_translated_country_name($country_post->post_title) : '-';
                        $total_price = get_post_meta($post_id, '_total_price', true);
                        $order_status_slug = get_post_meta($post_id, '_order_status', true);
                        $order_status_display = isset($statuses[$order_status_slug]) ? $statuses[$order_status_slug] : $order_status_slug;
                    ?>
                    <tr>
                        <td><a href="<?php echo get_edit_post_link($post_id); ?>">#<?php echo $post_id; ?></a></td>
                        <td><?php echo esc_html($weight); ?></td>
                        <td><?php echo esc_html($country_name); ?></td>
                        <td><?php echo $total_price ? number_format(floatval($total_price), 2) . ' $' : '-'; ?></td>
                        <td><?php echo esc_html($order_status_display); ?></td>
                        <td><?php echo get_the_date(); ?></td>
                    </tr>
                    <?php endwhile; wp_reset_postdata(); ?>
                <?php else: ?>
                    <tr><td colspan="6"><?php _e('No requests found.', 'x414i-shipping'); ?></td></tr>
                <?php endif; ?>
            </tbody>
        </table>

        <?php
        $total_pages = $shipping_requests->max_num_pages;
        if ($total_pages > 1) {
            echo '<div class="tablenav"><div class="tablenav-pages">';
            echo paginate_links([
                'base' => add_query_arg('paged', '%#%'),
                'format' => '',
                'prev_text' => __('&laquo;'),
                'next_text' => __('&raquo;'),
                'total' => $total_pages,
                'current' => max(1, get_query_var('paged')),
            ]);
            echo '</div></div>';
        }
        ?>
    </div>
    <?php
}
