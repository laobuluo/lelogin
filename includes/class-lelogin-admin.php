<?php
/**
 * LeLogin 管理后台类
 *
 * @package LeLogin
 */

if (!defined('ABSPATH')) {
    exit;
}

class LeLogin_Admin {
    
    /**
     * 构造函数
     */
    public function __construct() {
        // 添加管理菜单
        add_action('admin_menu', array($this, 'add_admin_menu'));
        
        // 注册设置
        add_action('admin_init', array($this, 'register_settings'));
        
        // 添加设置链接到插件页面
        add_filter('plugin_action_links_' . plugin_basename(LELOGIN_PLUGIN_FILE), array($this, 'add_plugin_action_links'));
        
        // 加载管理资源
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_assets'));
    }
    
    /**
     * 添加管理菜单
     */
    public function add_admin_menu() {
        add_options_page(
            __('LeLogin 设置', 'lelogin'),
            __('LeLogin', 'lelogin'),
            'manage_options',
            'lelogin-settings',
            array($this, 'render_settings_page')
        );
    }
    
    /**
     * 注册设置
     */
    public function register_settings() {
        register_setting('lelogin_settings_group', 'lelogin_settings', array($this, 'sanitize_settings'));
    }
    
    /**
     * 清理设置数据
     */
    public function sanitize_settings($input) {
        $sanitized = array();
        
        // 清理所有输入
        $sanitized['background_color'] = sanitize_hex_color($input['background_color'] ?? '');
        $sanitized['background_image'] = esc_url_raw($input['background_image'] ?? '');
        $sanitized['logo_image'] = esc_url_raw($input['logo_image'] ?? '');
        $sanitized['logo_width'] = absint($input['logo_width'] ?? 84);
        $sanitized['logo_height'] = !empty($input['logo_height']) ? absint($input['logo_height']) : '';
        $sanitized['form_background'] = sanitize_hex_color($input['form_background'] ?? '');
        $sanitized['form_border_radius'] = sanitize_text_field($input['form_border_radius'] ?? '');
        $sanitized['form_padding'] = sanitize_text_field($input['form_padding'] ?? '');
        $sanitized['button_color'] = sanitize_hex_color($input['button_color'] ?? '');
        $sanitized['button_hover_color'] = sanitize_hex_color($input['button_hover_color'] ?? '');
        $sanitized['button_text_color'] = sanitize_hex_color($input['button_text_color'] ?? '');
        $sanitized['link_color'] = sanitize_hex_color($input['link_color'] ?? '');
        $sanitized['link_hover_color'] = sanitize_hex_color($input['link_hover_color'] ?? '');
        $sanitized['text_color'] = sanitize_hex_color($input['text_color'] ?? '');
        $sanitized['custom_css'] = wp_strip_all_tags($input['custom_css'] ?? '');
        $sanitized['enable_plugin'] = isset($input['enable_plugin']) ? 1 : 0;
        $sanitized['enable_custom_logo'] = isset($input['enable_custom_logo']) ? 1 : 0;
        $sanitized['enable_background_image'] = isset($input['enable_background_image']) ? 1 : 0;
        $sanitized['login_logo_url'] = esc_url_raw($input['login_logo_url'] ?? '');
        $sanitized['login_logo_title'] = sanitize_text_field($input['login_logo_title'] ?? '');
        $sanitized['custom_login_slug'] = sanitize_title($input['custom_login_slug'] ?? '');
        
        // 如果自定义登录slug发生变化，需要刷新重写规则
        $old_settings = get_option('lelogin_settings', array());
        if (isset($old_settings['custom_login_slug']) && $old_settings['custom_login_slug'] !== $sanitized['custom_login_slug']) {
            flush_rewrite_rules();
        }
        
        return $sanitized;
    }
    
    /**
     * 加载管理资源
     */
    public function enqueue_admin_assets($hook) {
        if ($hook !== 'settings_page_lelogin-settings') {
            return;
        }
        
        // 加载颜色选择器
        wp_enqueue_style('wp-color-picker');
        wp_enqueue_script('wp-color-picker');
        
        // 加载媒体上传器
        wp_enqueue_media();
        
        // 加载自定义管理脚本
        wp_enqueue_script('lelogin-admin', LELOGIN_PLUGIN_URL . 'assets/js/admin.js', array('jquery', 'wp-color-picker'), LELOGIN_VERSION, true);
        wp_enqueue_style('lelogin-admin', LELOGIN_PLUGIN_URL . 'assets/css/admin.css', array(), LELOGIN_VERSION);
    }
    
    /**
     * 渲染设置页面
     */
    public function render_settings_page() {
        if (!current_user_can('manage_options')) {
            return;
        }
        
        $settings = get_option('lelogin_settings', array());
        
        include LELOGIN_PLUGIN_DIR . 'admin/settings-page.php';
    }
    
    /**
     * 添加插件操作链接
     */
    public function add_plugin_action_links($links) {
        $settings_link = '<a href="' . admin_url('options-general.php?page=lelogin-settings') . '">' . __('设置', 'lelogin') . '</a>';
        array_unshift($links, $settings_link);
        return $links;
    }
}

