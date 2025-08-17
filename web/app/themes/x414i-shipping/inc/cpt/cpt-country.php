<?php
// تسجيل نوع المحتوى المخصص للدول
function register_country_cpt() {
    $labels = [
        'name'                  => _x('الدول', 'Post type general name', 'x414i-shipping'),
        'singular_name'         => _x('دولة', 'Post type singular name', 'x414i-shipping'),
        'menu_name'             => _x('الدول', 'Admin Menu text', 'x414i-shipping'),
        'name_admin_bar'        => _x('دولة', 'Add New on Toolbar', 'x414i-shipping'),
        'add_new'               => __('إضافة جديد', 'x414i-shipping'),
        'add_new_item'          => __('إضافة دولة جديدة', 'x414i-shipping'),
        'new_item'              => __('دولة جديدة', 'x414i-shipping'),
        'edit_item'             => __('تعديل الدولة', 'x414i-shipping'),
        'view_item'             => __('عرض الدولة', 'x414i-shipping'),
        'all_items'             => __('جميع الدول', 'x414i-shipping'),
        'search_items'          => __('بحث الدول', 'x414i-shipping'),
        'parent_item_colon'     => __('الدول التابعة:', 'x414i-shipping'),
        'not_found'             => __('لم يتم العثور على دول.', 'x414i-shipping'),
        'not_found_in_trash'    => __('لا توجد دول في سلة المحذوفات.', 'x414i-shipping'),
        'archives'              => __('أرشيف الدول', 'x414i-shipping'),
        'filter_items_list'     => __('تصفية قائمة الدول', 'x414i-shipping'),
        'items_list_navigation' => __('تنقل قائمة الدول', 'x414i-shipping'),
        'items_list'            => __('قائمة الدول', 'x414i-shipping'),
    ];

    $args = [
        'labels'              => $labels,
        'public'              => true,
        'exclude_from_search' => true,
        'publicly_queryable'  => false,
        'show_ui'            => true,
        'show_in_menu'        => true,
        'show_in_nav_menus'   => false,
        'query_var'           => true,
        'rewrite'             => ['slug' => 'country'],
        'capability_type'     => 'post',
        'has_archive'         => false,
        'hierarchical'        => false,
        'menu_position'       => 56,
        'menu_icon'           => 'dashicons-admin-site-alt3',
        'supports'            => ['title', 'thumbnail', 'custom-fields'],
        'show_in_rest'        => true,
    ];

    register_post_type('country', $args);
}
add_action('init', 'register_country_cpt');

// ======== تحسين واجهة إدارة الدول ======== //

// إضافة أعمدة مخصصة لقائمة الدول
function add_country_columns($columns) {
    unset($columns['date']);
    return array_merge($columns, [
        'flag'         => __('العلم', 'x414i-shipping'),
        'country_code' => __('كود الدولة', 'x414i-shipping'),
        'cities_count' => __('عدد المدن', 'x414i-shipping'),
        'last_updated' => __('آخر تحديث', 'x414i-shipping'),
    ]);
}
add_filter('manage_country_posts_columns', 'add_country_columns');

// ملء محتوى الأعمدة
function country_column_content($column, $post_id) {
    switch ($column) {
        case 'flag':
            if (has_post_thumbnail($post_id)) {
                echo get_the_post_thumbnail($post_id, [40, 30], ['style' => 'border:1px solid #ddd;']);
            } else {
                echo '<span style="color:#ccc;">' . __('بدون علم', 'x414i-shipping') . '</span>';
            }
            break;
            
        case 'country_code':
            $code = get_post_meta($post_id, 'country_code', true);
            echo $code ? esc_html($code) : '--';
            break;
            
        case 'cities_count':
            // يمكن استبدال هذا بعدد المدن الفعلي إذا كنت تستخدم نوع محتوى للمدن
            $count = get_post_meta($post_id, 'cities_count', true);
            echo $count ? absint($count) : '0';
            break;
            
        case 'last_updated':
            $updated = get_the_modified_time('Y/m/d H:i', $post_id);
            printf(
                '<time datetime="%s">%s</time>',
                esc_attr(get_the_modified_date('c', $post_id)),
                esc_html($updated)
            );
            break;
    }
}
add_action('manage_country_posts_custom_column', 'country_column_content', 10, 2);

// جعل الأعمدة قابلة للترتيب
function make_country_columns_sortable($columns) {
    $columns['country_code'] = 'country_code';
    $columns['cities_count'] = 'cities_count';
    $columns['last_updated'] = 'last_updated';
    return $columns;
}
add_filter('manage_edit-country_sortable_columns', 'make_country_columns_sortable');

// فلترة حسب كود الدولة
function add_country_code_filter() {
    global $post_type;
    if ('country' !== $post_type) return;

    $current_code = isset($_GET['country_code_filter']) ? $_GET['country_code_filter'] : '';
    
    echo '<input type="text" name="country_code_filter" placeholder="' 
        . esc_attr__('تصفية بالكود', 'x414i-shipping') 
        . '" value="' . esc_attr($current_code) . '">';
}
add_action('restrict_manage_posts', 'add_country_code_filter');

// تطبيق الفلترة
function apply_country_code_filter($query) {
    if (!is_admin() || !$query->is_main_query()) return;

    $code = isset($_GET['country_code_filter']) ? sanitize_text_field($_GET['country_code_filter']) : '';
    if ($code) {
        $query->set('meta_key', 'country_code');
        $query->set('meta_value', $code);
        $query->set('meta_compare', 'LIKE');
    }
}
add_filter('pre_get_posts', 'apply_country_code_filter');

// تحسين واجهة إضافة/تعديل الدولة
function customize_country_admin() {
    // إضافة تعليمات للعنوان
    add_filter('enter_title_here', function($title) {
        $screen = get_current_screen();
        if ('country' == $screen->post_type) {
            $title = __('أدخل اسم الدولة هنا', 'x414i-shipping');
        }
        return $title;
    });

    // إضافة مربع تلميح تحت العنوان
    add_action('edit_form_after_title', function($post) {
        if ('country' !== $post->post_type) return;
        
        echo '<div class="notice notice-info inline">';
        echo '<p>' . __('يرجى إدخال الاسم الرسمي للدولة كما تظهر في الوثائق الرسمية.', 'x414i-shipping') . '</p>';
        echo '</div>';
    });
}
add_action('admin_head', 'customize_country_admin');