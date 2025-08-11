<?php

// Register Shipping Request CPT
function register_shipping_request_cpt() {
    register_post_type('shipping_request', [
        'labels' => [
            'name' => 'طلبات الشحن',
            'singular_name' => 'طلب شحن',
        ],
        'public' => true,
        'show_in_rest' => true,
        'supports' => ['title', 'editor'],
        'menu_icon' => 'dashicons-archive',
    ]);
}
add_action('init', 'register_shipping_request_cpt');


// Register Country CPT
function register_country_cpt() {
    register_post_type('country', [
        'labels' => [
            'name' => 'الدول',
            'singular_name' => 'دولة',
        ],
        'public' => true,
        'has_archive' => false,
        'menu_icon' => 'dashicons-flag',
        'supports' => ['title', 'custom-fields'],
        'show_in_rest' => true,
    ]);
}
add_action('init', 'register_country_cpt');


function add_country_meta_boxes() {
    add_meta_box(
        'country_shipping_meta',
        'بيانات الشحن',
        'render_country_shipping_meta_box',
        'country',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'add_country_meta_boxes');

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

function calculate_shipping_total_price($post_id) {
    if (get_post_type($post_id) !== 'shipping_request') {
        return;
    }

    // تحقق من الصلاحيات والأمان كما في المثال السابق

    $weight = isset($_POST['weight']) ? floatval($_POST['weight']) : 0;
    $country_id = isset($_POST['country_id']) ? intval($_POST['country_id']) : 0;

    if ($weight > 0 && $country_id > 0) {
        $price_per_kg = get_post_meta($country_id, '_price_per_kg', true);
        if ($price_per_kg) {
            $total_price = $weight * floatval($price_per_kg);
            update_post_meta($post_id, '_total_price', $total_price);
        }
    }
}
add_action('save_post', 'calculate_shipping_total_price');


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
}
add_action('save_post', 'save_shipping_request_meta');

function ajax_get_price_per_kg() {
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
function enqueue_shipping_scripts() {
    wp_enqueue_script('shipping-ajax', get_stylesheet_directory_uri() . '/js/shipping-ajax.js', ['jquery'], '1.0', true);
    wp_localize_script('shipping-ajax', 'shipping_ajax_obj', [
        'ajax_url' => admin_url('admin-ajax.php'),
    ]);
}
add_action('wp_enqueue_scripts', 'enqueue_shipping_scripts');


// إضافة أعمدة جديدة في قائمة طلبات الشحن
function add_shipping_request_columns($columns) {
    $new_columns = [
        'cb' => $columns['cb'], // خانة اختيار العناصر
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


// تعبئة الأعمدة بالبيانات الخاصة بكل طلب
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
            echo $price ? number_format(floatval($price), 2) . ' ريال' : '-';
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

    // جلب الطلبات مع خيارات التصفية والبحث (يمكن تطويرها لاحقًا)
    $args = [
        'post_type' => 'shipping_request',
        'posts_per_page' => 20,
        'paged' => isset($_GET['paged']) ? intval($_GET['paged']) : 1,
        'orderby' => 'date',
        'order' => 'DESC',
    ];

    // إضافة فلترة حسب الحالة (مثال)
    if (!empty($_GET['order_status'])) {
        $args['meta_query'] = [
            [
                'key' => '_order_status',
                'value' => sanitize_text_field($_GET['order_status']),
                'compare' => '=',
            ],
        ];
    }

    // استعلام الطلبات
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
        // عرض أزرار التنقل بين الصفحات
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
function marine_shipping_register_menus() {
    register_nav_menus([
        'primary' => __('القائمة الرئيسية', 'marine-shipping'),
        'footer'  => __('قائمة الفوتر', 'marine-shipping'),
    ]);
}
add_action('after_setup_theme', 'marine_shipping_register_menus');
function add_custom_capabilities() {
    $role = get_role('subscriber');

    if ($role) {
        $role->add_cap('read_shipping_requests');
    }
}
add_action('init', 'add_custom_capabilities');


// منع الوصول إلى wp-admin للمستخدمين العاديين
function restrict_admin_area() {
    if (
        is_admin() && 
        !current_user_can('manage_options') && 
        !(defined('DOING_AJAX') && DOING_AJAX)
    ) {
        wp_redirect(home_url('/dashboard')); // 🔁 عدل الرابط إن أردت
        exit;
    }
}
add_action('admin_init', 'restrict_admin_area');


// إعادة التوجيه بعد تسجيل الدخول
function custom_login_redirect($redirect_to, $request, $user) {
    // المستخدم غير موجود أو لم يتم التحقق منه بعد
    if (!isset($user->roles)) {
        return $redirect_to;
    }

    // المسؤولين → إلى لوحة التحكم
    if (in_array('administrator', $user->roles)) {
        return admin_url();
    }

    // الزبائن → إلى الصفحة الرئيسية أو لوحة التحكم المخصصة
    return home_url('/dashboard'); // ✅ عدل المسار حسب صفحتك
}
add_filter('login_redirect', 'custom_login_redirect', 10, 3);


// إخفاء شريط الإدارة للمستخدمين غير المسؤولين
function hide_admin_bar_for_users() {
    if (!current_user_can('manage_options')) {
        show_admin_bar(false);
    }
}
add_action('after_setup_theme', 'hide_admin_bar_for_users');



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

    ?>
    <p>
        <label for="price_land">سعر الشحن البري لكل كجم:</label><br>
        <input type="number" step="0.01" min="0" name="price_land" id="price_land" value="<?php echo esc_attr($price_land); ?>">
    </p>
    <p>
        <label for="price_sea">سعر الشحن البحري لكل كجم:</label><br>
        <input type="number" step="0.01" min="0" name="price_sea" id="price_sea" value="<?php echo esc_attr($price_sea); ?>">
    </p>
    <?php
}

function save_country_shipping_prices($post_id) {
    // تحقق من nonce للحماية
    if (!isset($_POST['shipping_prices_nonce']) || !wp_verify_nonce($_POST['shipping_prices_nonce'], 'save_shipping_prices')) {
        return;
    }

    // تحقق من صلاحيات المستخدم
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    if (isset($_POST['price_land'])) {
        update_post_meta($post_id, 'price_land', floatval($_POST['price_land']));
    }
    if (isset($_POST['price_sea'])) {
        update_post_meta($post_id, 'price_sea', floatval($_POST['price_sea']));
    }
}
add_action('save_post_country', 'save_country_shipping_prices');
