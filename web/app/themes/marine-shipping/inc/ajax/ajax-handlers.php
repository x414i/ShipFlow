<?php

function ajax_get_price_per_kg() {
    check_ajax_referer('marine_shipping_nonce', 'nonce');

    if (!isset($_POST['country_id'])) {
        wp_send_json_error('لم يتم تحديد الدولة');
    }

    $country_id = intval($_POST['country_id']);
    $price_per_kg = get_post_meta($country_id, '_price_per_kg', true);

    if (!$price_per_kg) {
        wp_send_json_error('سعر الكيلو غير متوفر');
    }
    wp_send_json_success(['price_per_kg' => floatval($price_per_kg)]);
}
add_action('wp_ajax_get_price_per_kg', 'ajax_get_price_per_kg');
add_action('wp_ajax_nopriv_get_price_per_kg', 'ajax_get_price_per_kg');
