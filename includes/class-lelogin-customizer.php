<?php
/**
 * LeLogin 登录页面自定义器
 *
 * @package LeLogin
 */

if (!defined('ABSPATH')) {
    exit;
}

class LeLogin_Customizer {
    
    /**
     * 构造函数
     */
    public function __construct() {
        // 检查插件是否启用
        $settings = $this->get_settings();
        if (empty($settings['enable_plugin'])) {
            return; // 如果未启用，不加载任何功能
        }
        
        // 自定义登录页面样式
        add_action('login_head', array($this, 'custom_login_styles'));
        
        // 自定义登录页面Logo
        add_action('login_headerurl', array($this, 'custom_login_logo_url'));
        add_filter('login_headertext', array($this, 'custom_login_logo_title'));
        
        // 添加自定义CSS
        add_action('login_head', array($this, 'add_custom_css'));
        
        // 添加自定义JavaScript
        add_action('login_footer', array($this, 'add_custom_js'));
        
        // 自定义登录表单HTML
        add_action('login_head', array($this, 'add_custom_html'));
    }
    
    /**
     * 获取设置选项
     */
    private function get_settings() {
        return get_option('lelogin_settings', array());
    }
    
    /**
     * 自定义登录页面样式
     */
    public function custom_login_styles() {
        $settings = $this->get_settings();
        
        // 加载自定义CSS文件
        wp_enqueue_style('lelogin-custom', LELOGIN_PLUGIN_URL . 'assets/css/custom-login.css', array(), LELOGIN_VERSION);
        
        // 输出内联样式
        $css = $this->generate_custom_css($settings);
        echo '<style type="text/css">' . $css . '</style>';
    }
    
    /**
     * 生成自定义CSS
     */
    private function generate_custom_css($settings) {
        $css = '';
        
        // 背景颜色和图片
        if (!empty($settings['background_image']) && $settings['enable_background_image']) {
            $css .= 'body.login { background-image: url(' . esc_url($settings['background_image']) . '); background-size: cover; background-position: center; background-repeat: no-repeat; }';
        } elseif (!empty($settings['background_color'])) {
            $css .= 'body.login { background-color: ' . esc_attr($settings['background_color']) . '; }';
        }
        
        // Logo样式
        if (!empty($settings['logo_image']) && $settings['enable_custom_logo']) {
            $logo_width = !empty($settings['logo_width']) ? $settings['logo_width'] . 'px' : '84px';
            $logo_height = !empty($settings['logo_height']) ? $settings['logo_height'] . 'px' : 'auto';
            $css .= '#login h1 a, .login h1 a { 
                background-image: url(' . esc_url($settings['logo_image']) . '); 
                width: ' . esc_attr($logo_width) . '; 
                height: ' . esc_attr($logo_height) . '; 
                background-size: contain; 
                background-position: center; 
                background-repeat: no-repeat; 
                padding-bottom: 20px;
            }';
        }
        
        // 表单样式
        if (!empty($settings['form_background'])) {
            $css .= '#loginform, #registerform, #lostpasswordform { background-color: ' . esc_attr($settings['form_background']) . '; }';
        }
        
        if (!empty($settings['form_border_radius'])) {
            $css .= '#loginform, #registerform, #lostpasswordform { border-radius: ' . esc_attr($settings['form_border_radius']) . '; }';
        }
        
        if (!empty($settings['form_padding'])) {
            $css .= '#loginform, #registerform, #lostpasswordform { padding: ' . esc_attr($settings['form_padding']) . '; }';
        }
        
        // 按钮样式
        if (!empty($settings['button_color'])) {
            $css .= '#wp-submit, .button-primary { background-color: ' . esc_attr($settings['button_color']) . '; border-color: ' . esc_attr($settings['button_color']) . '; }';
        }
        
        if (!empty($settings['button_hover_color'])) {
            $css .= '#wp-submit:hover, .button-primary:hover { background-color: ' . esc_attr($settings['button_hover_color']) . '; border-color: ' . esc_attr($settings['button_hover_color']) . '; }';
        }
        
        if (!empty($settings['button_text_color'])) {
            $css .= '#wp-submit, .button-primary { color: ' . esc_attr($settings['button_text_color']) . '; }';
        }
        
        // 链接样式
        if (!empty($settings['link_color'])) {
            $css .= '#login a, .login a { color: ' . esc_attr($settings['link_color']) . '; }';
        }
        
        if (!empty($settings['link_hover_color'])) {
            $css .= '#login a:hover, .login a:hover { color: ' . esc_attr($settings['link_hover_color']) . '; }';
        }
        
        // 文本颜色
        if (!empty($settings['text_color'])) {
            $css .= '#loginform label, .login form label { color: ' . esc_attr($settings['text_color']) . '; }';
        }
        
        // 自定义CSS
        if (!empty($settings['custom_css'])) {
            $css .= $settings['custom_css'];
        }
        
        return $css;
    }
    
    /**
     * 自定义登录Logo链接
     */
    public function custom_login_logo_url($url) {
        $settings = $this->get_settings();
        if (!empty($settings['login_logo_url'])) {
            return esc_url($settings['login_logo_url']);
        }
        return $url;
    }
    
    /**
     * 自定义登录Logo标题
     */
    public function custom_login_logo_title($title) {
        $settings = $this->get_settings();
        if (!empty($settings['login_logo_title'])) {
            return esc_attr($settings['login_logo_title']);
        }
        return $title;
    }
    
    /**
     * 添加自定义CSS
     */
    public function add_custom_css() {
        // 这个方法已经通过 custom_login_styles 处理
    }
    
    /**
     * 添加自定义JavaScript
     */
    public function add_custom_js() {
        // 可以在这里添加自定义JavaScript
        wp_enqueue_script('lelogin-custom', LELOGIN_PLUGIN_URL . 'assets/js/custom-login.js', array('jquery'), LELOGIN_VERSION, true);
    }
    
    /**
     * 添加自定义HTML
     */
    public function add_custom_html() {
        // 可以在这里添加自定义HTML元素
    }
}

