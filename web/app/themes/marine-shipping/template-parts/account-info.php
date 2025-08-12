<?php
/*
Template Name: معلومات الحساب
*/
ob_start(); // ✅ يبدأ التخزين المؤقت

if (isset($_GET['updated'])) {
    echo '<p style="color:green;">تم تحديث البيانات بنجاح.</p>';
}

include('dashboard-wrapper.php');

$current_user = wp_get_current_user();
$user_id = get_current_user_id();

// جلب بيانات الهاتف والعنوان من user meta
$user_phone = get_user_meta($user_id, 'phone', true);
$user_address = get_user_meta($user_id, 'address', true);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['account_info_nonce']) && wp_verify_nonce($_POST['account_info_nonce'], 'update_account_info')) {
    
    $new_display_name = sanitize_text_field($_POST['display_name']);
    $new_password = $_POST['password'];
    $new_phone = sanitize_text_field($_POST['phone']);
    $new_address = sanitize_textarea_field($_POST['address']);

    wp_update_user([
        'ID' => $user_id,
        'display_name' => $new_display_name,
        'first_name' => $new_display_name,
    ]);

    update_user_meta($user_id, 'phone', $new_phone);
    update_user_meta($user_id, 'address', $new_address);

    if (!empty($new_password)) {
        wp_set_password($new_password, $user_id);
        wp_safe_redirect(add_query_arg('updated', '1', get_permalink()));
        exit;
    }

    echo '<p style="color:green;">تم تحديث البيانات بنجاح.</p>';
}
?>

<h2>⚙️ معلومات الحساب</h2>

<form method="post" action="">
    <?php wp_nonce_field('update_account_info', 'account_info_nonce'); ?>

    <label for="display_name">الاسم:</label><br>
    <input type="text" name="display_name" id="display_name" value="<?php echo esc_attr($current_user->display_name); ?>" required><br><br>

    <label for="user_email">البريد الإلكتروني:</label><br>
    <input type="email" id="user_email" value="<?php echo esc_attr($current_user->user_email); ?>" disabled><br><br>

    <label for="phone">الهاتف:</label><br>
    <input type="text" name="phone" id="phone" value="<?php echo esc_attr($user_phone); ?>"><br><br>

    <label for="address">العنوان:</label><br>
    <textarea name="address" id="address" rows="4"><?php echo esc_textarea($user_address); ?></textarea><br><br>

    <label for="password">كلمة المرور الجديدة:</label><br>
    <input type="password" name="password" id="password"><br>
    <small>اتركه فارغًا إن لم ترغب في تغييره.</small><br><br>

    <button type="submit">💾 حفظ التغييرات</button>
</form>

<?php
ob_end_flush(); // ✅ ينهي التخزين المؤقت
?>

<?php include('dashboard-footer.php'); ?>
