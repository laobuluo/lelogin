/**
 * LeLogin 管理后台脚本
 */

(function($) {
    'use strict';
    
    $(document).ready(function() {
        // 初始化颜色选择器
        $('.color-picker').wpColorPicker();
        
        // 插件启用/禁用切换
        var $enablePlugin = $('#enable_plugin');
        var $tabsContainer = $('#lelogin-tabs-container');
        
        function toggleSettingsState() {
            if ($enablePlugin.is(':checked')) {
                $tabsContainer.removeClass('disabled');
            } else {
                $tabsContainer.addClass('disabled');
            }
        }
        
        // 初始化状态
        toggleSettingsState();
        
        // 监听开关变化
        $enablePlugin.on('change', function() {
            toggleSettingsState();
        });
        
        // Tab切换功能
        $('.nav-tab').on('click', function(e) {
            e.preventDefault();
            
            var target = $(this).attr('href');
            
            // 更新Tab状态
            $('.nav-tab').removeClass('nav-tab-active');
            $(this).addClass('nav-tab-active');
            
            // 更新内容显示
            $('.tab-content').removeClass('active');
            $(target).addClass('active');
        });
        
        // 媒体上传器
        $('.lelogin-upload-button').on('click', function(e) {
            e.preventDefault();
            
            var button = $(this);
            var targetInput = $('#' + button.data('target'));
            
            var mediaUploader = wp.media({
                title: '选择图片',
                button: {
                    text: '使用此图片'
                },
                multiple: false
            });
            
            mediaUploader.on('select', function() {
                var attachment = mediaUploader.state().get('selection').first().toJSON();
                targetInput.val(attachment.url);
                
                // 如果是Logo，显示预览
                if (button.data('target') === 'logo_image') {
                    var preview = $('.lelogin-preview');
                    if (preview.length === 0) {
                        targetInput.after('<div class="lelogin-preview"><img src="' + attachment.url + '" alt="Logo预览" style="max-width: 200px; margin-top: 10px;" /></div>');
                    } else {
                        preview.find('img').attr('src', attachment.url);
                    }
                }
            });
            
            mediaUploader.open();
        });
        
        // 背景图片预览
        $('#background_image').on('change', function() {
            var url = $(this).val();
            if (url) {
                // 可以在这里添加背景图片预览功能
            }
        });
        
        // 自定义登录URL预览
        var $loginSlugInput = $('#custom_login_slug');
        var $loginSlugPreview = $('#login-slug-preview');
        var baseUrl = $loginSlugPreview.parent().text().match(/https?:\/\/[^\s]+/);
        baseUrl = baseUrl ? baseUrl[0] : window.location.origin + '/';
        
        function updateLoginUrlPreview() {
            var slug = $loginSlugInput.val().trim();
            if (slug) {
                // 清理slug（只允许字母、数字、连字符）
                slug = slug.replace(/[^a-z0-9-]/gi, '').toLowerCase();
                $loginSlugPreview.text(slug || 'my-login');
            } else {
                $loginSlugPreview.text('my-login');
            }
        }
        
        $loginSlugInput.on('input', function() {
            updateLoginUrlPreview();
        });
        
        // 初始化预览
        updateLoginUrlPreview();
    });
})(jQuery);

