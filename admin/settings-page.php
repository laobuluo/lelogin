<?php
/**
 * LeLogin 设置页面
 *
 * @package LeLogin
 */

if (!defined('ABSPATH')) {
    exit;
}

// 显示保存成功消息
if (isset($_GET['settings-updated'])) {
    echo '<div class="notice notice-success is-dismissible"><p>' . __('设置已保存！', 'lelogin') . '</p></div>';
}
?>

<div class="wrap">
    <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
    
    <form method="post" action="options.php">
        <?php settings_fields('lelogin_settings_group'); ?>
        
        <div class="lelogin-settings-container">
            <!-- 插件启用开关 -->
            <div class="lelogin-enable-section" style="background: #fff; border: 1px solid #ccd0d4; box-shadow: 0 1px 1px rgba(0,0,0,.04); padding: 20px; margin-bottom: 20px;">
                <h2 style="margin-top: 0;"><?php _e('插件状态', 'lelogin'); ?></h2>
                <table class="form-table">
                    <tr>
                        <th scope="row">
                            <label for="enable_plugin"><?php _e('启用插件', 'lelogin'); ?></label>
                        </th>
                        <td>
                            <label>
                                <input type="checkbox" id="enable_plugin" name="lelogin_settings[enable_plugin]" value="1" <?php checked(isset($settings['enable_plugin']) ? $settings['enable_plugin'] : 0, 1); ?> />
                                <strong><?php _e('启用 LeLogin 自定义登录界面功能', 'lelogin'); ?></strong>
                            </label>
                            <p class="description" style="margin-top: 10px;">
                                <?php _e('勾选此选项后，插件才会对登录页面应用自定义样式。取消勾选将恢复WordPress默认登录界面。', 'lelogin'); ?>
                            </p>
                        </td>
                    </tr>
                </table>
            </div>
            
            <div class="lelogin-tabs <?php echo (empty($settings['enable_plugin']) ? 'disabled' : ''); ?>" id="lelogin-tabs-container">
                <nav class="nav-tab-wrapper">
                    <a href="#general" class="nav-tab nav-tab-active"><?php _e('常规设置', 'lelogin'); ?></a>
                    <a href="#logo" class="nav-tab"><?php _e('Logo设置', 'lelogin'); ?></a>
                    <a href="#colors" class="nav-tab"><?php _e('颜色设置', 'lelogin'); ?></a>
                    <a href="#form" class="nav-tab"><?php _e('表单样式', 'lelogin'); ?></a>
                    <a href="#advanced" class="nav-tab"><?php _e('高级设置', 'lelogin'); ?></a>
                </nav>
                
                <!-- 常规设置 -->
                <div id="general" class="tab-content active">
                    <h2><?php _e('背景设置', 'lelogin'); ?></h2>
                    <table class="form-table">
                        <tr>
                            <th scope="row">
                                <label for="background_color"><?php _e('背景颜色', 'lelogin'); ?></label>
                            </th>
                            <td>
                                <input type="text" id="background_color" name="lelogin_settings[background_color]" value="<?php echo esc_attr($settings['background_color'] ?? '#f1f1f1'); ?>" class="color-picker" />
                                <p class="description"><?php _e('选择登录页面的背景颜色', 'lelogin'); ?></p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label for="enable_background_image"><?php _e('启用背景图片', 'lelogin'); ?></label>
                            </th>
                            <td>
                                <input type="checkbox" id="enable_background_image" name="lelogin_settings[enable_background_image]" value="1" <?php checked(isset($settings['enable_background_image']) ? $settings['enable_background_image'] : 0, 1); ?> />
                                <label for="enable_background_image"><?php _e('使用背景图片替代背景颜色', 'lelogin'); ?></label>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label for="background_image"><?php _e('背景图片', 'lelogin'); ?></label>
                            </th>
                            <td>
                                <input type="text" id="background_image" name="lelogin_settings[background_image]" value="<?php echo esc_url($settings['background_image'] ?? ''); ?>" class="regular-text" />
                                <button type="button" class="button lelogin-upload-button" data-target="background_image"><?php _e('选择图片', 'lelogin'); ?></button>
                                <p class="description"><?php _e('上传或输入背景图片URL', 'lelogin'); ?></p>
                            </td>
                        </tr>
                    </table>
                </div>
                
                <!-- Logo设置 -->
                <div id="logo" class="tab-content">
                    <h2><?php _e('Logo设置', 'lelogin'); ?></h2>
                    <table class="form-table">
                        <tr>
                            <th scope="row">
                                <label for="enable_custom_logo"><?php _e('启用自定义Logo', 'lelogin'); ?></label>
                            </th>
                            <td>
                                <input type="checkbox" id="enable_custom_logo" name="lelogin_settings[enable_custom_logo]" value="1" <?php checked(isset($settings['enable_custom_logo']) ? $settings['enable_custom_logo'] : 1, 1); ?> />
                                <label for="enable_custom_logo"><?php _e('使用自定义Logo替代WordPress默认Logo', 'lelogin'); ?></label>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label for="logo_image"><?php _e('Logo图片', 'lelogin'); ?></label>
                            </th>
                            <td>
                                <input type="text" id="logo_image" name="lelogin_settings[logo_image]" value="<?php echo esc_url($settings['logo_image'] ?? ''); ?>" class="regular-text" />
                                <button type="button" class="button lelogin-upload-button" data-target="logo_image"><?php _e('选择图片', 'lelogin'); ?></button>
                                <?php if (!empty($settings['logo_image'])): ?>
                                    <div class="lelogin-preview">
                                        <img src="<?php echo esc_url($settings['logo_image']); ?>" alt="Logo预览" style="max-width: 200px; margin-top: 10px;" />
                                    </div>
                                <?php endif; ?>
                                <p class="description"><?php _e('上传或输入Logo图片URL', 'lelogin'); ?></p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label for="logo_width"><?php _e('Logo宽度 (px)', 'lelogin'); ?></label>
                            </th>
                            <td>
                                <input type="number" id="logo_width" name="lelogin_settings[logo_width]" value="<?php echo esc_attr($settings['logo_width'] ?? '84'); ?>" class="small-text" min="50" max="500" />
                                <p class="description"><?php _e('设置Logo的宽度（像素）', 'lelogin'); ?></p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label for="logo_height"><?php _e('Logo高度 (px)', 'lelogin'); ?></label>
                            </th>
                            <td>
                                <input type="number" id="logo_height" name="lelogin_settings[logo_height]" value="<?php echo esc_attr($settings['logo_height'] ?? ''); ?>" class="small-text" min="30" max="500" />
                                <p class="description"><?php _e('设置Logo的高度（像素），留空则自动适应', 'lelogin'); ?></p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label for="login_logo_url"><?php _e('Logo链接地址', 'lelogin'); ?></label>
                            </th>
                            <td>
                                <input type="url" id="login_logo_url" name="lelogin_settings[login_logo_url]" value="<?php echo esc_url($settings['login_logo_url'] ?? home_url()); ?>" class="regular-text" />
                                <p class="description"><?php _e('点击Logo时跳转的URL', 'lelogin'); ?></p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label for="login_logo_title"><?php _e('Logo标题', 'lelogin'); ?></label>
                            </th>
                            <td>
                                <input type="text" id="login_logo_title" name="lelogin_settings[login_logo_title]" value="<?php echo esc_attr($settings['login_logo_title'] ?? get_bloginfo('name')); ?>" class="regular-text" />
                                <p class="description"><?php _e('Logo的alt和title属性', 'lelogin'); ?></p>
                            </td>
                        </tr>
                    </table>
                </div>
                
                <!-- 颜色设置 -->
                <div id="colors" class="tab-content">
                    <h2><?php _e('颜色设置', 'lelogin'); ?></h2>
                    <table class="form-table">
                        <tr>
                            <th scope="row">
                                <label for="button_color"><?php _e('按钮颜色', 'lelogin'); ?></label>
                            </th>
                            <td>
                                <input type="text" id="button_color" name="lelogin_settings[button_color]" value="<?php echo esc_attr($settings['button_color'] ?? '#2271b1'); ?>" class="color-picker" />
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label for="button_hover_color"><?php _e('按钮悬停颜色', 'lelogin'); ?></label>
                            </th>
                            <td>
                                <input type="text" id="button_hover_color" name="lelogin_settings[button_hover_color]" value="<?php echo esc_attr($settings['button_hover_color'] ?? '#135e96'); ?>" class="color-picker" />
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label for="button_text_color"><?php _e('按钮文字颜色', 'lelogin'); ?></label>
                            </th>
                            <td>
                                <input type="text" id="button_text_color" name="lelogin_settings[button_text_color]" value="<?php echo esc_attr($settings['button_text_color'] ?? '#ffffff'); ?>" class="color-picker" />
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label for="link_color"><?php _e('链接颜色', 'lelogin'); ?></label>
                            </th>
                            <td>
                                <input type="text" id="link_color" name="lelogin_settings[link_color]" value="<?php echo esc_attr($settings['link_color'] ?? '#2271b1'); ?>" class="color-picker" />
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label for="link_hover_color"><?php _e('链接悬停颜色', 'lelogin'); ?></label>
                            </th>
                            <td>
                                <input type="text" id="link_hover_color" name="lelogin_settings[link_hover_color]" value="<?php echo esc_attr($settings['link_hover_color'] ?? '#135e96'); ?>" class="color-picker" />
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label for="text_color"><?php _e('文本颜色', 'lelogin'); ?></label>
                            </th>
                            <td>
                                <input type="text" id="text_color" name="lelogin_settings[text_color]" value="<?php echo esc_attr($settings['text_color'] ?? '#3c434a'); ?>" class="color-picker" />
                            </td>
                        </tr>
                    </table>
                </div>
                
                <!-- 表单样式 -->
                <div id="form" class="tab-content">
                    <h2><?php _e('表单样式设置', 'lelogin'); ?></h2>
                    <table class="form-table">
                        <tr>
                            <th scope="row">
                                <label for="form_background"><?php _e('表单背景颜色', 'lelogin'); ?></label>
                            </th>
                            <td>
                                <input type="text" id="form_background" name="lelogin_settings[form_background]" value="<?php echo esc_attr($settings['form_background'] ?? '#ffffff'); ?>" class="color-picker" />
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label for="form_border_radius"><?php _e('表单圆角', 'lelogin'); ?></label>
                            </th>
                            <td>
                                <input type="text" id="form_border_radius" name="lelogin_settings[form_border_radius]" value="<?php echo esc_attr($settings['form_border_radius'] ?? '4px'); ?>" class="small-text" />
                                <p class="description"><?php _e('例如: 4px, 8px, 10px', 'lelogin'); ?></p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label for="form_padding"><?php _e('表单内边距', 'lelogin'); ?></label>
                            </th>
                            <td>
                                <input type="text" id="form_padding" name="lelogin_settings[form_padding]" value="<?php echo esc_attr($settings['form_padding'] ?? '30px'); ?>" class="small-text" />
                                <p class="description"><?php _e('例如: 30px, 20px 30px', 'lelogin'); ?></p>
                            </td>
                        </tr>
                    </table>
                </div>
                
                <!-- 高级设置 -->
                <div id="advanced" class="tab-content">
                    <h2><?php _e('高级设置', 'lelogin'); ?></h2>
                    <table class="form-table">
                        <tr>
                            <th scope="row">
                                <label for="custom_login_slug"><?php _e('自定义登录入口URL', 'lelogin'); ?></label>
                            </th>
                            <td>
                                <input type="text" id="custom_login_slug" name="lelogin_settings[custom_login_slug]" value="<?php echo esc_attr($settings['custom_login_slug'] ?? ''); ?>" class="regular-text" placeholder="例如: my-login" />
                                <p class="description">
                                    <?php _e('设置自定义登录页面URL，例如输入 "my-login" 后，登录地址将变为：', 'lelogin'); ?>
                                    <code><?php echo home_url('/'); ?><span id="login-slug-preview">my-login</span></code>
                                    <br>
                                    <?php _e('留空则使用默认的 wp-login.php。设置后请保存并刷新固定链接设置。', 'lelogin'); ?>
                                    <br>
                                    <strong style="color: #d63638;"><?php _e('注意：设置后请记住新的登录地址，否则可能无法登录！', 'lelogin'); ?></strong>
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label for="custom_css"><?php _e('自定义CSS', 'lelogin'); ?></label>
                            </th>
                            <td>
                                <textarea id="custom_css" name="lelogin_settings[custom_css]" rows="10" class="large-text code"><?php echo esc_textarea($settings['custom_css'] ?? ''); ?></textarea>
                                <p class="description"><?php _e('添加自定义CSS代码来进一步美化登录页面', 'lelogin'); ?></p>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            
            <p class="submit">
                <?php submit_button(__('保存设置', 'lelogin'), 'primary', 'submit', false); ?>
                <a href="<?php echo wp_login_url(); ?>" target="_blank" class="button"><?php _e('预览登录页面', 'lelogin'); ?></a>
            </p>
        </div>
    </form>
</div>

