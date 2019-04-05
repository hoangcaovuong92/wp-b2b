//****************************************************************//
/*							Ajax JS								  */
//****************************************************************//
jQuery(document).ready(function($) {
    "use strict";
    //Check server active theme status
    wd_ajax_check_server_status();

    //Check purchase code
    wd_ajax_activation_purchase_code();
    
    //Install plugins action
	wd_ajax_install_plugin();

    //Reset activation
    wd_ajax_reset_activation();

	//Create page template
    wd_ajax_create_page_template();

    //Create sidebar widgets
    wd_ajax_create_sidebar_template();

    //Create template parts for other post type
    wd_ajax_create_other_template();
});

//****************************************************************//
/*							FUNCTIONS							  */
//****************************************************************//
//Check server active theme status
if (typeof wd_ajax_check_server_status != 'function') { 
	function wd_ajax_check_server_status() {
		jQuery('#wd_update_checker_server_status').on('click', function(e){
            e.preventDefault();
            var _this = jQuery(this);
            var button_text = _this.text();
            jQuery.ajax({
                type: 'POST',
                url: ajax_object.ajax_url,
                data: { 
                    action: "wd_update_checker_server_status",
                },
                beforeSend: function(){
                    _this.text(button_text+' (Checking...)');
                    _this.attr('disabled', 'disabled');
                },
                success: function(response) {
                    _this.text(button_text);
                    _this.removeAttr('disabled');
                    if (response.data.connected) {
                        alert('Server is still working ^^!');
                    }else{
                        alert('Connection errors! Please contact the theme author for more information.');
                    };
                }
            });
        });
	}
}

//Check purchase code
if (typeof wd_ajax_activation_purchase_code != 'function') { 
	function wd_ajax_activation_purchase_code() {
		jQuery('#wd-activation-submit-button').on('click', function(e){
            e.preventDefault();
            var _this = jQuery(this);
            var button_text = _this.text();
            var username = jQuery('#wd-activation-username').val();
            var purchase_code = jQuery('#wd-activation-purchase-code').val();
            jQuery.ajax({
                type: 'POST',
                url: ajax_object.ajax_url,
                data: { 
                    action: "wd_check_activation_purchase_code",
                    username: username,
                    purchase_code: purchase_code,
                },
                beforeSend: function(){
                    _this.text('Checking...');
                    _this.attr('disabled', 'disabled');
                },
                success: function(response) {
                    _this.text(button_text);
                    _this.removeAttr('disabled');
                    if (typeof response.data.customer_info['verify-purchase'] === 'object') {
                        if ("buyer" in response.data.customer_info['verify-purchase']) {
                            jQuery('#wd-activation-form').submit();
                        }else{
                            alert('Something is wrong. Please check your information again!!!');
                        }
                    }else if (response.data.customer_info['error']){
                        alert(response.data.customer_info['error']);
                    }else{
                        alert('Something is wrong. Please check your information again!!!');
                        location.reload();
                    }
                }
            });
        });
	}
}

//Check purchase code
if (typeof wd_ajax_reset_activation != 'function') { 
	function wd_ajax_reset_activation() {
		jQuery('#wd-activation-reset').on('click', function(e){
            e.preventDefault();
            var _this = jQuery(this);
            jQuery.ajax({
                type: 'POST',
                url: ajax_object.ajax_url,
                data: { 
                    action: "wd_reset_activation",
                },
                beforeSend: function(){
                    _this.text('Removing...');
                },
                success: function(response) {
                    location.reload();
                }
            });
        });
	}
}

//Install plugins action
if (typeof wd_ajax_install_plugin != 'function') { 
	function wd_ajax_install_plugin() {
        var plugin_item = jQuery('.wd-plugins-item-checkbox');
        var select_all_btn = jQuery('#wd-select-all-plugin-package');
        var list_plugins_selected = [];

        jQuery('body').on('selected_plugins', function(){
            list_plugins_selected = [];
            jQuery.each(plugin_item, function(i, e){
                if(this.checked) {
                    list_plugins_selected.push(jQuery(e).val());
                }
            })
            if (list_plugins_selected.length) {
                jQuery('.wd-install-selected-plugins-action').removeAttr('disabled');
            }else{
                jQuery('.wd-install-selected-plugins-action').attr('disabled', 'disabled');
            }
        });

        select_all_btn.on('change', function(){
            if(this.checked) {
                plugin_item.attr('checked', 'checked');
            }else{
                plugin_item.removeAttr('checked');
            }
            jQuery('body').trigger('selected_plugins');
        });

        plugin_item.on('change', function(){
            jQuery('body').trigger('selected_plugins');
        });

        function ajax_action($button, list_plugins_selected, plugin_action, confirm){
            if (!list_plugins_selected.length) return;
            var plugin_slug = list_plugins_selected[0];
            if (!plugin_slug) return;
            var next_item = list_plugins_selected[1];
            var button_text = $button.text();
            var $checkbox = jQuery('#'+plugin_slug+'-package');
            jQuery('table.wd-page-admin-page-form').find('.wd-form-desc').remove();
            if (plugin_slug && confirm) {
                jQuery.ajax({
                    type: 'POST',
                    url: ajax_object.ajax_url,
                    data: { 
                        action: "wd_install_plugin",
                        plugin_slug: plugin_slug,
                        plugin_action: plugin_action,
                    },
                    beforeSend: function(){
                        $button.text('Working...');
                        $checkbox.next('label').html('...');
                        $button.parents('p').find('.button').attr('disabled', 'disabled');
                        jQuery('.wd-install-plugin-action').attr('disabled', 'disabled');
                    },
                    success: function(response) {
                        $button.parents('p').find('.button').removeAttr('disabled');
                        jQuery('.wd-install-plugin-action').removeAttr('disabled');
                        $button.removeAttr('disabled').text(button_text);
                        $checkbox.next('label').html('');
                        if (response.success) {
                            if (!next_item) {
                                $button.parents('p').append('<p class="wd-form-desc"><a href=""onClick="location.reload()">Refresh page</a></p>');
                            }else{
                                list_plugins_selected.shift();
                                ajax_action($button, list_plugins_selected, plugin_action, confirm);
                            }
                        }
                    }
                });
			} 
        }

        jQuery('.wd-install-selected-plugins-action').on('click', function(e){
			e.preventDefault();
			var $button = jQuery(this);
            var plugin_action = $button.data('action');
            var confirm = (plugin_action !== 'delete') ? true : window.confirm("Do you want to delete this plugin?");
            jQuery('table.wd-page-admin-page-form').find('.wd-form-desc').remove();
            ajax_action($button, list_plugins_selected, plugin_action, confirm);
		});


		jQuery('.wd-install-plugin-action').on('click', function(e){
			e.preventDefault();
			var _this = jQuery(this);
            var plugin_slug = _this.data('plugin-slug');
            var plugin_action = _this.data('action');
            var confirm = (plugin_action !== 'delete') ? true : window.confirm("Do you want to delete this plugin?");
            jQuery('table.wd-page-admin-page-form').find('.wd-form-desc').remove();
            if (plugin_slug && confirm) {
                jQuery.ajax({
                    type: 'POST',
                    url: ajax_object.ajax_url,
                    data: { 
                        action: "wd_install_plugin",
                        plugin_slug: plugin_slug,
                        plugin_action: plugin_action,
                    },
                    beforeSend: function(){
                        _this.text('Working...');
                        _this.parents('p').find('.button').attr('disabled', 'disabled');
                    },
                    success: function(response) {
                        _this.text('Success');
                        if (response.success) {
                            //_this.parents('tr').find('td.wd-plugin-status').html(response.data.status);
                            _this.parents('p').append('<p class="wd-form-desc"><a href=""onClick="location.reload()">Refresh page</a></p>');
                        }
                    }
                });
			} 
		});
	}
}

//Create page template
if (typeof wd_ajax_create_page_template != 'function') { 
	function wd_ajax_create_page_template() {
		jQuery('.wd-create-page-template').on('click', function(e){
			e.preventDefault();
			var _this = jQuery(this);
            var template = _this.data('template');
            var template_exist = _this.data('template-exist');
            var editor = _this.parents('td').find('input[name="set_editor_action_'+template.replace(/ /g, '-')+'"]:checked').val();
            var set_homepage = _this.parents('td').find('input[name="set_at_homepage"]').prop('checked');
            var create_sidebar = _this.parents('td').find('input[name="create_sidebar"]').prop('checked') ? _this.data('sidebar-template') : '';
            var create_banner = _this.parents('td').find('input[name="create_banner"]').prop('checked') ? _this.data('banner-template') : '';
            var confirm = (!template_exist) ? true : window.confirm("The content of the page will be restored to the default. Are you sure?");

            if (template && confirm) {
                jQuery.ajax({
                    type: 'POST',
                    url: ajax_object.ajax_url,
                    data: { 
                        action: "create_page_template",
                        template: template,
                        set_homepage: set_homepage,
                        create_sidebar: create_sidebar,
                        create_banner: create_banner,
                        editor: editor,
                    },
                    beforeSend: function(){
                        _this.find('.wd-image-loading').show();
                        _this.text('Working...');
                        _this.parents('p').find('.button').attr('disabled', 'disabled');
                    },
                    success: function(response) {
                        _this.find('.wd-image-loading').hide();
                        if (response.success) {
                            _this.addClass('disabled').html('Success!');
                            location.reload();
                        }
                    }
                });
			} 
		});
	}
}

//Create template parts for other post type
if (typeof wd_ajax_create_other_template != 'function') { 
	function wd_ajax_create_other_template() {
		jQuery('.wd-template-parts-template').on('click', function(e){
			e.preventDefault();
			var _this = jQuery(this);
            var template = _this.data('template');
            var template_exist = _this.data('template-exist');
            var editor = _this.parents('td').find('input[name="set_editor_action_'+template.replace(/ /g, '-')+'"]:checked').val();
            var confirm = (!template_exist) ? true : window.confirm("The content of the page will be restored to the default. Are you sure?");
            if (template && confirm) {
                jQuery.ajax({
                    type: 'POST',
                    url: ajax_object.ajax_url,
                    data: { 
                        action: "create_template_part",
                        template: template,
                        editor: editor,
                    },
                    beforeSend: function(){
                        _this.find('.wd-image-loading').show();
                        _this.text('Working...');
                        _this.parents('p').find('.button').attr('disabled', 'disabled');
                    },
                    success: function(response) {
                        _this.find('.wd-image-loading').hide();
                        if (response.success) {
                            _this.addClass('disabled').html('Success!');
                            location.reload();
                        }
                    }
                });
			} 
		});
	}
}


//Create sidebar widgets
if (typeof wd_ajax_create_sidebar_template != 'function') { 
	function wd_ajax_create_sidebar_template() {
		jQuery('.wd-create-sidebar-template').on('click', function(e){
			e.preventDefault();
			var _this = jQuery(this);
            var sidebar_id = _this.data('sidebar-id');
            var sidebar_action = _this.parents('td').find('input[name="set_sidebar_action_'+sidebar_id+'"]:checked').val();
            if (sidebar_action) {
                jQuery.ajax({
                    type: 'POST',
                    url: ajax_object.ajax_url,
                    data: { 
                        action: "sidebar_manager_action",
                        sidebar_id: sidebar_id,
                        sidebar_action: sidebar_action
                    },
                    beforeSend: function(){
                        _this.find('.wd-image-loading').show();
                        _this.text('Working...');
                        _this.parents('p').find('.button').attr('disabled', 'disabled');
                    },
                    success: function(response) {
                        _this.find('.wd-image-loading').hide();
                        if (response.success) {
                            _this.addClass('disabled').html('Success!');
                            location.reload();
                        }
                    }
                });
			} 
		});
	}
}