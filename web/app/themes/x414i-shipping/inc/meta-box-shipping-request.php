<?php


function add_shipping_request_meta_boxes() {
    add_meta_box(
        'shipping_request_details',
        __('Shipping Request Details', 'x414i-shipping'),
        'render_shipping_request_meta_box',
        'shipping_request',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'add_shipping_request_meta_boxes');

function render_shipping_request_meta_box($post) {
    wp_nonce_field('save_shipping_request_meta', 'shipping_request_meta_nonce');

    $weight = get_post_meta($post->ID, '_weight', true);
    $country_id = get_post_meta($post->ID, '_country_id', true);
    $order_status = get_post_meta($post->ID, '_order_status', true);
    $notes = get_post_meta($post->ID, '_notes', true);

    $countries = get_posts([
        'post_type' => 'country',
        'posts_per_page' => -1,
        'orderby' => 'title',
        'order' => 'ASC',
    ]);
    ?>

    <label for="weight"><?php echo __('Weight (kg):', 'x414i-shipping'); ?></label>
    <input type="number" name="weight" id="weight" value="<?php echo esc_attr($weight); ?>" step="0.01" min="0" />

    <br><br>

    <label for="country_id"><?php echo __('Select Country:', 'x414i-shipping'); ?></label>
    <select name="country_id" id="country_id">
        <option value=""><?php echo __('-- Select Country --', 'x414i-shipping'); ?></option>
        <?php
        foreach ($countries as $country) {
            printf(
                '<option value="%d" %s>%s</option>',
                $country->ID,
                selected($country_id, $country->ID, false),
                esc_html(get_translated_country_name($country->post_title))
            );
        }
        ?>
    </select>

    <br><br>

    <label for="order_status"><?php echo __('Order Status:', 'x414i-shipping'); ?></label>
    <select name="order_status" id="order_status">
        <option value="new" <?php selected($order_status, 'new'); ?>><?php echo __('New', 'x414i-shipping'); ?></option>
        <option value="processing" <?php selected($order_status, 'processing'); ?>><?php echo __('Processing', 'x414i-shipping'); ?></option>
        <option value="shipped" <?php selected($order_status, 'shipped'); ?>><?php echo __('Shipped', 'x414i-shipping'); ?></option>
        <option value="delivered" <?php selected($order_status, 'delivered'); ?>><?php echo __('Delivered', 'x414i-shipping'); ?></option>
    </select>

    <br><br>

    <label for="notes"><?php echo __('Notes:', 'x414i-shipping'); ?></label>
    <textarea name="notes" id="notes" rows="3" style="width:100%;"><?php echo esc_textarea($notes); ?></textarea>

    <?php
}

function save_shipping_request_meta($post_id) {
    if (!isset($_POST['shipping_request_meta_nonce'])) return;
    if (!wp_verify_nonce($_POST['shipping_request_meta_nonce'], 'save_shipping_request_meta')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    if (isset($_POST['weight'])) {
        update_post_meta($post_id, '_weight', floatval($_POST['weight']));
    }

    if (isset($_POST['country_id'])) {
        update_post_meta($post_id, '_country_id', intval($_POST['country_id']));
    }

    if (isset($_POST['order_status'])) {
        update_post_meta($post_id, '_order_status', sanitize_text_field($_POST['order_status']));
    }

    if (isset($_POST['notes'])) {
        update_post_meta($post_id, '_notes', sanitize_textarea_field($_POST['notes']));
    }
}
add_action('save_post', 'save_shipping_request_meta');
