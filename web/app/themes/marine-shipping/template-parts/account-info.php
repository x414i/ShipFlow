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

<style>
    .account-info-container {
        max-width: 700px;
        margin: 30px auto;
        padding: 30px;
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        font-family: 'Segoe UI', Tahoma, sans-serif;
    }
    
    .account-info-header {
        display: flex;
        align-items: center;
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 1px solid #eee;
    }
    
    .account-info-header h2 {
        margin: 0;
        font-size: 28px;
        color: #2c3e50;
        font-weight: 600;
    }
    
    .account-info-header i {
        background: #3498db;
        color: white;
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-left: 15px;
        font-size: 24px;
    }
    
    .form-group {
        margin-bottom: 25px;
        position: relative;
    }
    
    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 500;
        color: #2c3e50;
        font-size: 16px;
    }
    
    .form-input {
        width: 100%;
        padding: 14px 15px 14px 45px;
        border: 1px solid #ddd;
        border-radius: 8px;
        font-size: 16px;
        transition: all 0.3s;
        box-sizing: border-box;
    }
    
    .form-input:focus {
        border-color: #3498db;
        box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.2);
        outline: none;
    }
    
    .input-icon {
        position: absolute;
        left: 15px;
        top: 42px;
        color: #7f8c8d;
        font-size: 18px;
    }
    
    .form-textarea {
        height: 120px;
        padding: 14px 15px 14px 45px;
    }
    
    .password-note {
        font-size: 14px;
        color: #7f8c8d;
        margin-top: 5px;
        display: block;
    }
    
    .submit-btn {
        background: #3498db;
        color: white;
        border: none;
        padding: 14px 30px;
        font-size: 17px;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        font-weight: 500;
    }
    
    .submit-btn i {
        margin-left: 8px;
    }
    
    .submit-btn:hover {
        background: #2980b9;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(52, 152, 219, 0.3);
    }
    
    .success-message {
        background: #d4edda;
        color: #155724;
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 25px;
        border: 1px solid #c3e6cb;
        display: flex;
        align-items: center;
    }
    
    .success-message i {
        margin-left: 10px;
        font-size: 20px;
    }
    
    .info-note {
        background: #e8f4fd;
        padding: 15px;
        border-radius: 8px;
        margin-top: 25px;
        border-left: 4px solid #3498db;
        font-size: 14px;
        color: #2c3e50;
    }
    
    @media (max-width: 768px) {
        .account-info-container {
            padding: 20px;
            margin: 20px 15px;
        }
        
        .account-info-header h2 {
            font-size: 24px;
        }
        
        .form-input {
            padding: 12px 12px 12px 40px;
        }
        
        .input-icon {
            top: 40px;
            font-size: 16px;
        }
    }
</style>

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