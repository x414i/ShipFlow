<?php

// function calculate_shipping_total_price($post_id) {
//     if (get_post_type($post_id) !== 'shipping_request') {
//         return;
//     }


//     $weight = isset($_POST['weight']) ? floatval($_POST['weight']) : 0;
//     $country_id = isset($_POST['country_id']) ? intval($_POST['country_id']) : 0;

//     if ($weight > 0 && $country_id > 0) {
//         $price_per_kg = get_post_meta($country_id, '_price_per_kg', true);
//         if ($price_per_kg) {
//             $total_price = $weight * floatval($price_per_kg);
//             update_post_meta($post_id, '_total_price', $total_price);
//         }
//     }
// }
// add_action('save_post', 'calculate_shipping_total_price');


function add_shipping_request_meta_boxes() {
    add_meta_box(
        'shipping_request_details',
        'تفاصيل طلب الشحن',
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
    $notes = get_post_meta($post->ID, '_notes', true); // إضافة جلب الملاحظات

    // جلب الدول الموجودة
    $countries = get_posts([
        'post_type' => 'country',
        'posts_per_page' => -1,
        'orderby' => 'title',
        'order' => 'ASC',
    ]);
    ?>

    <label for="weight">الوزن (كجم):</label>
    <input type="number" name="weight" id="weight" value="<?php echo esc_attr($weight); ?>" step="0.01" min="0" />

    <br><br>

    <label for="country_id">اختر الدولة:</label>
    <select name="country_id" id="country_id">
        <option value="">-- اختر الدولة --</option>
        <?php
        foreach ($countries as $country) {
            printf(
                '<option value="%d" %s>%s</option>',
                $country->ID,
                selected($country_id, $country->ID, false),
                esc_html($country->post_title)
            );
        }
        ?>
    </select>

    <br><br>

    <label for="order_status">حالة الطلب:</label>
    <select name="order_status" id="order_status">
        <option value="جديد" <?php selected($order_status, 'جديد'); ?>>جديد</option>
        <option value="قيد المعالجة" <?php selected($order_status, 'قيد المعالجة'); ?>>قيد المعالجة</option>
        <option value="تم الشحن" <?php selected($order_status, 'تم الشحن'); ?>>تم الشحن</option>
        <option value="تم التوصيل" <?php selected($order_status, 'تم التوصيل'); ?>>تم التوصيل</option>
    </select>

    <br><br>

    <label for="notes">الملاحظات:</label>
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
