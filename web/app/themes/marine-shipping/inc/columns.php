<?php

function add_shipping_request_columns($columns) {
    $new_columns = [
        'cb' => $columns['cb'], 
        'title' => 'عنوان الطلب',
        'weight' => 'الوزن (كجم)',
        'country' => 'الدولة',
        'total_price' => 'السعر الإجمالي',
        'order_status' => 'حالة الطلب',
        'date' => $columns['date'],
    ];
    return $new_columns;
}
add_filter('manage_shipping_request_posts_columns', 'add_shipping_request_columns');


function fill_shipping_request_columns($column, $post_id) {
    switch ($column) {
        case 'weight':
            $weight = get_post_meta($post_id, '_weight', true);
            echo $weight ? esc_html($weight) : '-';
            break;

        case 'country':
            $country_id = get_post_meta($post_id, '_country_id', true);
            if ($country_id) {
                $country = get_post($country_id);
                echo $country ? esc_html($country->post_title) : '-';
            } else {
                echo '-';
            }
            break;

        case 'total_price':
            $price = get_post_meta($post_id, '_total_price', true);
            echo $price ? number_format(floatval($price), 2) . '$' : '-';
            break;

        case 'order_status':
            $status = get_post_meta($post_id, '_order_status', true);
            echo $status ? esc_html($status) : '-';
            break;
    }
}
add_action('manage_shipping_request_posts_custom_column', 'fill_shipping_request_columns', 10, 2);


function shipping_request_sortable_columns($columns) {
    $columns['weight'] = 'weight';
    $columns['total_price'] = 'total_price';
    $columns['order_status'] = 'order_status';
    return $columns;
}
add_filter('manage_edit-shipping_request_sortable_columns', 'shipping_request_sortable_columns');



function shipping_request_orderby($query) {
    if (!is_admin()) return;

    $orderby = $query->get('orderby');

    if ('weight' === $orderby) {
        $query->set('meta_key', '_weight');
        $query->set('orderby', 'meta_value_num');
    } elseif ('total_price' === $orderby) {
        $query->set('meta_key', '_total_price');
        $query->set('orderby', 'meta_value_num');
    } elseif ('order_status' === $orderby) {
        $query->set('meta_key', '_order_status');
        $query->set('orderby', 'meta_value');
    }
}
add_action('pre_get_posts', 'shipping_request_orderby');
