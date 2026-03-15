<?php
/**
 * Plugin Name: LeLogin - 自定义登录界面美化
 * Plugin URI: https://www.laojiang.me/7200.html
 * Description: 一款强大的WordPress登录界面自定义美化插件，允许您完全自定义登录页面的外观和样式。
 * Version: 1.0.0
 * Author: 老蒋和他的伙伴们
 * Author URI: https://www.laojiang.me
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: lelogin
 * Domain Path: /languages
 */

// 防止直接访问
if (!defined('ABSPATH')) {
    exit;
}

// 定义插件常量
define('LELOGIN_VERSION', '1.0.0');
define('LELOGIN_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('LELOGIN_PLUGIN_URL', plugin_dir_url(__FILE__));
define('LELOGIN_PLUGIN_FILE', __FILE__);

// 引入核心类
require_once LELOGIN_PLUGIN_DIR . 'includes/class-lelogin.php';
require_once LELOGIN_PLUGIN_DIR . 'includes/class-lelogin-admin.php';
require_once LELOGIN_PLUGIN_DIR . 'includes/class-lelogin-customizer.php';

// 早期拦截wp-login.php访问（在WordPress完全加载之前）
add_action('plugins_loaded', 'lelogin_early_login_redirect', 1);
function lelogin_early_login_redirect() {
    $settings = get_option('lelogin_settings', array());
    
    // 如果插件未启用或没有自定义登录slug，不处理
    if (empty($settings['enable_plugin']) || empty($settings['custom_login_slug'])) {
        return;
    }
    
    // 检查是否是访问wp-login.php
    $request_uri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
    $script_name = isset($_SERVER['SCRIPT_NAME']) ? basename($_SERVER['SCRIPT_NAME']) : '';
    $php_self = isset($_SERVER['PHP_SELF']) ? basename($_SERVER['PHP_SELF']) : '';
    
    // 如果访问wp-login.php，重定向到自定义URL
    if ($script_name === 'wp-login.php' || 
        $php_self === 'wp-login.php' || 
        strpos($request_uri, '/wp-login.php') !== false ||
        strpos($request_uri, 'wp-login.php') !== false) {
        
        $custom_login_url = home_url('/' . $settings['custom_login_slug'] . '/');
        
        // 保留查询参数
        if (!empty($_SERVER['QUERY_STRING'])) {
            $custom_login_url .= '?' . $_SERVER['QUERY_STRING'];
        }
        
        wp_redirect($custom_login_url, 301);
        exit;
    }
}

// 初始化插件
function lelogin_init() {
    $lelogin = new LeLogin();
    $lelogin->init();
}
add_action('plugins_loaded', 'lelogin_init');

// 激活插件时的操作
register_activation_hook(__FILE__, 'lelogin_activate');
function lelogin_activate() {
    // 设置默认选项
    $defaults = array(
        'enable_plugin' => false,  // 默认未启用
        'background_color' => '#f1f1f1',
        'background_image' => '',
        'logo_image' => '',
        'logo_width' => '84',
        'logo_height' => '',
        'form_background' => '#ffffff',
        'form_border_radius' => '4px',
        'form_padding' => '30px',
        'button_color' => '#2271b1',
        'button_hover_color' => '#135e96',
        'button_text_color' => '#ffffff',
        'link_color' => '#2271b1',
        'link_hover_color' => '#135e96',
        'text_color' => '#3c434a',
        'custom_css' => '',
        'enable_custom_logo' => true,
        'enable_background_image' => false,
        'login_logo_url' => home_url(),
        'login_logo_title' => get_bloginfo('name'),
        'custom_login_slug' => '',
    );
    
    add_option('lelogin_settings', $defaults);
}

// 停用插件时的操作
register_deactivation_hook(__FILE__, 'lelogin_deactivate');
function lelogin_deactivate() {
    // 清理临时数据（可选）
}

