<?php
/*
Template Name: معلومات الحساب
*/
ob_start();

get_header();

$current_user = wp_get_current_user();
$user_id = get_current_user_id();
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

    $success_message = 'تم تحديث البيانات بنجاح.';
}
?>



<div class="account-info-container">
    <div class="account-info-header">
        <i class="fas fa-user-cog"></i>
        <h2>معلومات الحساب</h2>
    </div>
    
    <?php if (isset($_GET['updated']) || isset($success_message)) : ?>
        <div class="success-message">
            <i class="fas fa-check-circle"></i>
            <span><?php echo isset($success_message) ? $success_message : 'تم تحديث البيانات بنجاح.'; ?></span>
        </div>
    <?php endif; ?>
    
    <form method="post" action="">
        <?php wp_nonce_field('update_account_info', 'account_info_nonce'); ?>
        
        <div class="form-group">
            <label for="display_name">الاسم:</label>
            <i class="fas fa-user input-icon"></i>
            <input type="text" name="display_name" id="display_name" class="form-input" value="<?php echo esc_attr($current_user->display_name); ?>" required>
        </div>
        
        <div class="form-group">
            <label for="user_email">البريد الإلكتروني:</label>
            <i class="fas fa-envelope input-icon"></i>
            <input type="email" id="user_email" class="form-input" value="<?php echo esc_attr($current_user->user_email); ?>" disabled>
        </div>
        
        <div class="form-group">
            <label for="phone">الهاتف:</label>
            <i class="fas fa-phone input-icon"></i>
            <input type="text" name="phone" id="phone" class="form-input" value="<?php echo esc_attr($user_phone); ?>">
        </div>
        
        <div class="form-group">
            <label for="address">العنوان:</label>
            <i class="fas fa-map-marker-alt input-icon"></i>
            <textarea name="address" id="address" class="form-input form-textarea"><?php echo esc_textarea($user_address); ?></textarea>
        </div>
        
        <div class="form-group">
            <label for="password">كلمة المرور الجديدة:</label>
            <i class="fas fa-lock input-icon"></i>
            <input type="password" name="password" id="password" class="form-input">
            <span class="password-note">اتركه فارغًا إن لم ترغب في تغييره</span>
        </div>
        
        <button type="submit" class="submit-btn">
            حفظ التغييرات
            <i class="fas fa-save"></i>
        </button>
    </form>
    
    <div class="info-note">
        <i class="fas fa-info-circle"></i> يمكنك تحديث معلومات حسابك الشخصية في أي وقت. سيتم استخدام هذه المعلومات لتوصيل الطلبات والاتصال بك عند الحاجة.
    </div>
</div>

<?php
ob_end_flush();
get_footer();
?>