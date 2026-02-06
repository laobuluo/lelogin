/**
 * LeLogin 自定义登录页面脚本
 */

(function($) {
    'use strict';
    
    $(document).ready(function() {
        // 可以在这里添加登录页面的交互效果
        // 例如：表单验证、动画效果等
        
        // 添加淡入动画
        $('#loginform, #registerform, #lostpasswordform').hide().fadeIn(500);
        
        // 输入框焦点效果
        $('#loginform input[type="text"], #loginform input[type="password"]').on('focus', function() {
            $(this).parent().addClass('focused');
        }).on('blur', function() {
            $(this).parent().removeClass('focused');
        });
    });
})(jQuery);

