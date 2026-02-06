<?php
/**
 * LeLogin 自定义登录URL处理类
 *
 * @package LeLogin
 */

if (!defined('ABSPATH')) {
    exit;
}

class LeLogin_Login_URL {
    
    /**
     * 构造函数
     */
    public function __construct() {
        $settings = $this->get_settings();
        
        // 检查插件是否启用且有自定义登录slug
        if (empty($settings['enable_plugin']) || empty($settings['custom_login_slug'])) {
            return;
        }
        
        // 添加重写规则
        add_action('init', array($this, 'add_rewrite_rules'));
        
        // 在init阶段检查并处理登录URL（更早执行）
        add_action('init', array($this, 'handle_login_redirects'), 1);
        
        // 拦截自定义登录URL - 使用template_redirect
        add_action('template_redirect', array($this, 'handle_custom_login_url'), 1);
        
        // 更新登录URL
        add_filter('login_url', array($this, 'custom_login_url'), 10, 2);
        add_filter('site_url', array($this, 'custom_login_url'), 10, 2);
    }
    
    /**
     * 获取设置选项
     */
    private function get_settings() {
        return get_option('lelogin_settings', array());
    }
    
    /**
     * 添加重写规则
     */
    public function add_rewrite_rules() {
        $settings = $this->get_settings();
        if (empty($settings['custom_login_slug'])) {
            return;
        }
        
        $login_slug = $settings['custom_login_slug'];
        add_rewrite_rule(
            '^' . $login_slug . '/?$',
            'index.php?lelogin_custom_login=1',
            'top'
        );
        
        // 添加查询变量
        add_rewrite_tag('%lelogin_custom_login%', '([^&]+)');
    }
    
    /**
     * 处理登录重定向（在init阶段执行，拦截直接访问wp-login.php）
     */
    public function handle_login_redirects() {
        $settings = $this->get_settings();
        if (empty($settings['custom_login_slug'])) {
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
            
            // 保留查询参数（如action=logout等）
            if (!empty($_SERVER['QUERY_STRING'])) {
                $custom_login_url .= '?' . $_SERVER['QUERY_STRING'];
            }
            
            wp_redirect($custom_login_url, 301);
            exit;
        }
    }
    
    /**
     * 处理自定义登录URL
     */
    public function handle_custom_login_url() {
        $settings = $this->get_settings();
        if (empty($settings['custom_login_slug'])) {
            return;
        }
        
        // 获取请求URI（只取路径部分）
        $request_uri = isset($_SERVER['REQUEST_URI']) ? parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) : '';
        $request_uri = trim($request_uri, '/');
        $login_slug = $settings['custom_login_slug'];
        
        // 检查是否是自定义登录URL
        if ($request_uri === $login_slug || $request_uri === $login_slug . '/') {
            // 保存查询字符串
            $query_string = isset($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING'] : '';
            
            // 解析查询参数到$_GET和$_REQUEST
            if (!empty($query_string)) {
                parse_str($query_string, $query_params);
                foreach ($query_params as $key => $value) {
                    $_GET[$key] = $value;
                    $_REQUEST[$key] = $value;
                }
            }
            
            // 初始化可能未定义的变量，避免wp-login.php中的警告
            if (!isset($_GET['user_login'])) {
                $_GET['user_login'] = '';
            }
            if (!isset($_REQUEST['user_login'])) {
                $_REQUEST['user_login'] = '';
            }
            if (!isset($_GET['user_email'])) {
                $_GET['user_email'] = '';
            }
            if (!isset($_REQUEST['user_email'])) {
                $_REQUEST['user_email'] = '';
            }
            
            // 设置必要的环境变量，模拟访问wp-login.php
            $_SERVER['REQUEST_URI'] = '/wp-login.php';
            if (!empty($query_string)) {
                $_SERVER['REQUEST_URI'] .= '?' . $query_string;
            }
            $_SERVER['SCRIPT_NAME'] = '/wp-login.php';
            $_SERVER['PHP_SELF'] = '/wp-login.php';
            
            // 确保WordPress已加载
            if (!defined('ABSPATH')) {
                return;
            }
            
            // 在全局作用域中声明并初始化wp-login.php可能用到的全局变量
            global $user_login, $user_email, $error, $redirect_to, $interim_login, $action, $reauth, $login, $key, $checkemail, $registration;
            
            // 初始化全局变量
            $user_login = isset($_GET['user_login']) ? $_GET['user_login'] : '';
            $user_email = isset($_GET['user_email']) ? $_GET['user_email'] : '';
            $error = isset($_GET['error']) ? $_GET['error'] : '';
            $redirect_to = isset($_GET['redirect_to']) ? $_GET['redirect_to'] : '';
            $interim_login = isset($_GET['interim_login']) ? $_GET['interim_login'] : '';
            $action = isset($_GET['action']) ? $_GET['action'] : '';
            $reauth = isset($_GET['reauth']) ? $_GET['reauth'] : '';
            $login = isset($_GET['login']) ? $_GET['login'] : '';
            $key = isset($_GET['key']) ? $_GET['key'] : '';
            $checkemail = isset($_GET['checkemail']) ? $_GET['checkemail'] : '';
            $registration = isset($_GET['registration']) ? $_GET['registration'] : '';
            
            // 临时禁用错误显示，避免变量未定义的警告
            $old_error_reporting = error_reporting();
            error_reporting($old_error_reporting & ~E_WARNING);
            
            // 清除输出缓冲区，确保干净的输出
            while (ob_get_level()) {
                ob_end_clean();
            }
            
            // 加载WordPress登录页面
            include(ABSPATH . 'wp-login.php');
            
            // 恢复错误报告
            error_reporting($old_error_reporting);
            
            exit;
        }
    }
    
    /**
     * 自定义登录URL
     */
    public function custom_login_url($url, $path) {
        $settings = $this->get_settings();
        if (empty($settings['custom_login_slug'])) {
            return $url;
        }
        
        // 如果是登录URL，替换为自定义URL
        if ($path === 'wp-login.php' || (is_string($path) && strpos($path, 'wp-login.php') !== false)) {
            $custom_slug = $settings['custom_login_slug'];
            $url = home_url('/' . $custom_slug . '/');
            
            // 解析原始URL的查询参数
            $parsed_url = parse_url($url);
            if (isset($parsed_url['query'])) {
                $url .= '?' . $parsed_url['query'];
            }
        }
        
        return $url;
    }
}

