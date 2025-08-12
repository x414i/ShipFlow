<?php

// function add_country_meta_boxes() {
//     add_meta_box(
//         'country_shipping_meta',
//         'بيانات الشحن',
//         'render_country_shipping_meta_box',
//         'country',
//         'normal',
//         'high'
//     );
// }
// add_action('add_meta_boxes', 'add_country_meta_boxes');

function render_country_shipping_meta_box($post) {
    // nonce للأمان
    wp_nonce_field('save_country_shipping_meta', 'country_shipping_meta_nonce');

    $shipping_type = get_post_meta($post->ID, '_shipping_type', true);
    $price_per_kg = get_post_meta($post->ID, '_price_per_kg', true);

    ?>
    <label for="shipping_type">نوع الشحن:</label>
    <select name="shipping_type" id="shipping_type">
        <option value="بحري" <?php selected($shipping_type, 'بحري'); ?>>بحري</option>
        <option value="بري" <?php selected($shipping_type, 'بري'); ?>>بري</option>
    </select>

    <br><br>

    <label for="price_per_kg">سعر الكيلو (بالعملة):</label>
    <input type="number" name="price_per_kg" id="price_per_kg" value="<?php echo esc_attr($price_per_kg); ?>" step="0.01" min="0" />
    <?php
}

function save_country_shipping_meta($post_id) {
    if (!isset($_POST['country_shipping_meta_nonce'])) {
        return;
    }
    if (!wp_verify_nonce($_POST['country_shipping_meta_nonce'], 'save_country_shipping_meta')) {
        return;
    }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    if (isset($_POST['shipping_type'])) {
        update_post_meta($post_id, '_shipping_type', sanitize_text_field($_POST['shipping_type']));
    }

    if (isset($_POST['price_per_kg'])) {
        update_post_meta($post_id, '_price_per_kg', floatval($_POST['price_per_kg']));
    }
}
add_action('save_post', 'save_country_shipping_meta');

function country_add_meta_boxes() {
    add_meta_box(
        'shipping_prices_meta_box',     // ID
        'أسعار الشحن',                 // Title
        'country_shipping_prices_html', // Callback
        'country',                     // Screen (post type)
        'normal',                      // Context
        'high'                        // Priority
    );
}
add_action('add_meta_boxes', 'country_add_meta_boxes');

function country_shipping_prices_html($post) {
    // استخدام nonce للحماية
    wp_nonce_field('save_shipping_prices', 'shipping_prices_nonce');

    $price_land = get_post_meta($post->ID, 'price_land', true);
    $price_sea = get_post_meta($post->ID, 'price_sea', true);
    $price_air = get_post_meta($post->ID, 'price_air', true);
    $price_fast = get_post_meta($post->ID, 'price_fast', true);

    ?>
    <p>
        <label for="price_land">سعر الشحن البري لكل كجم:</label><br>
        <input type="number" step="0.01" min="0" name="price_land" id="price_land" value="<?php echo esc_attr($price_land); ?>">
    </p>
    <p>
        <label for="price_sea">سعر الشحن البحري لكل كجم:</label><br>
        <input type="number" step="0.01" min="0" name="price_sea" id="price_sea" value="<?php echo esc_attr($price_sea); ?>">
    </p>
    <p>
        <label for="price_air">سعر الشحن الجوي لكل كجم:</label><br>
        <input type="number" step="0.01" min="0" name="price_air" id="price_air" value="<?php echo esc_attr($price_air); ?>">
    </p>
    <p>
        <label for="price_fast">سعر الشحن السريع لكل كجم:</label><br>
        <input type="number" step="0.01" min="0" name="price_fast" id="price_fast" value="<?php echo esc_attr($price_fast); ?>">
    </p>
    <?php
}

function save_country_shipping_prices($post_id) {
    if (!isset($_POST['shipping_prices_nonce']) || !wp_verify_nonce($_POST['shipping_prices_nonce'], 'save_shipping_prices')) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    if (isset($_POST['price_land'])) {
        update_post_meta($post_id, 'price_land', floatval($_POST['price_land']));
    }
    if (isset($_POST['price_sea'])) {
        update_post_meta($post_id, 'price_sea', floatval($_POST['price_sea']));
    }
    if (isset($_POST['price_air'])) {
        update_post_meta($post_id, 'price_air', floatval($_POST['price_air']));
    }
    if (isset($_POST['price_fast'])) {
        update_post_meta($post_id, 'price_fast', floatval($_POST['price_fast']));
    }
}
add_action('save_post_country', 'save_country_shipping_prices');
