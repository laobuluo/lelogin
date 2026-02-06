<?php
/**
 * LeLogin 主类
 *
 * @package LeLogin
 */

if (!defined('ABSPATH')) {
    exit;
}

class LeLogin {
    
    /**
     * 初始化插件
     */
    public function init() {
        // 加载文本域
        add_action('init', array($this, 'load_textdomain'));
        
        // 如果是管理员，加载管理类
        if (is_admin()) {
            new LeLogin_Admin();
        }
        
        // 加载自定义器
        new LeLogin_Customizer();
        
        // 加载自定义登录URL处理
        require_once LELOGIN_PLUGIN_DIR . 'includes/class-lelogin-login-url.php';
        new LeLogin_Login_URL();
    }
    
    /**
     * 加载文本域
     */
    public function load_textdomain() {
        load_plugin_textdomain('lelogin', false, dirname(plugin_basename(LELOGIN_PLUGIN_FILE)) . '/languages');
    }
}

