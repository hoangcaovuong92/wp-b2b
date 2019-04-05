//****************************************************************//
/*							Ajax JS								  */
//****************************************************************//
jQuery(document).ready(function($) {
	"use strict";
	wd_ajax_empty_cart(); //Empty Cart
	wd_ajax_search(); //Ajax Search
	wd_ajax_login_validate(); //Login with validate ajax
	wd_ajax_register_validate(); //Register with validate ajax
	wd_ajax_forgot_password(); //Forgot Password with ajax
	wd_ajax_add_to_cart_single_product(); //AJAX ADD TO CART FOR SIMPLE & VARIATION PRODUCT (SINGLE) 
});

//****************************************************************//
/*							FUNCTIONS							  */
//****************************************************************//
//Empty Cart
if (typeof wd_ajax_empty_cart != 'function') { 
	function wd_ajax_empty_cart() {
		jQuery('body').on('click', '.wd-clear-cart-item', function(e){
			e.preventDefault();
			var _this = jQuery(this);
			var _mess = _this.data('mess');
			if (confirm(_mess)) {
				var image_loading =	_this.next('.wd-loading--empty-minicart');
				_this.parents('.wd-cart-flagments').find('.wd-dropdown-container').addClass('wd-showing-cart');
			   	jQuery.ajax({
					type: 'POST',
					url: ajax_object.ajax_url,
					data: { 
						action: "wd_woocommerce_empty_cart",
				 	},
				 	beforeSend: function(){
	                   image_loading.removeClass('hidden');
	               	},
					success: function(data) {
						location.reload();
					}
				});
			} 
		});
	}
}

//Ajax Search
if (typeof wd_ajax_search != 'function') { 
	function wd_ajax_search() {
		var timer;
		jQuery( '.wd-search-with-ajax' ).on('click', function(){
			jQuery('body').trigger('clear_search_result');
		});

		jQuery( '.wd-search-with-ajax' ).on('keydown', function(e){
			//if press ctrl, alt, shift or enter key
			if(e.ctrlKey || e.altKey || e.shiftKey || e.which == 13) return;
			//if press escape key
			if (e.key === "Escape") {
				jQuery('body').trigger('clear_search_result');
				return;
			}

			var $this = jQuery(this);
			jQuery('body').trigger('clear_search_result');

			if (timer) clearTimeout(timer);
			timer = setTimeout(function(){
				var post_type 	= $this.data('post_type');
				var s 	= $this.val();
				if (s) {
					jQuery.ajax({
						type: 'POST',
						url: ajax_object.ajax_url,
						data: { 
							action: "wd_ajax_search",
							post_type: post_type, 
							s: s, 
						},
						beforeSend: function(){
							$this.parents('.wd-search-form-default').find(".wd-loading--search-form").removeClass('hidden');
						},
						success: function(response) {
							$this.parents('.wd-search-form-default').find(".wd-loading--search-form").addClass('hidden');
							if (response.success) {
								$this.parents('.wd-search-form-default').find('.wd-search-form-ajax-result').html(response.data.html);
							}
						}
					});
				}
			}, 1000);
		});
	}
}

//Login with validate ajax
if (typeof wd_ajax_login_validate != 'function') { 
	function wd_ajax_login_validate() {
		jQuery( '.wd-login-form-with-validate-ajax' ).submit(function(e){
			e.preventDefault();
			var form_id 	= jQuery(this).find('.wd-login-btn').data('form_id');
			var username 	= jQuery('#'+form_id+' .wd-login-username').val();
			var password 	= jQuery('#'+form_id+' .wd-login-password').val();
			var rememberme 	= jQuery('#'+form_id+' .wd-login-rememberme').val();

			jQuery('#'+form_id+' .wd-alert').addClass('hidden')
			if (username == '' || password == '') {
				jQuery('#'+form_id+' .wd-login-error-alert').removeClass('hidden').html('<label class="wd-notice wd-error-message">Please enter username & password!</label>');
				jQuery('#'+form_id+' .wd-my-account-field-wrap').addClass('wd-my-account-field-error').removeClass('wd-my-account-field-success');
			}else{
				jQuery.ajax({
					type: 'POST',
					url: ajax_object.ajax_url,
					data: { 
						action: "login_with_validate_ajax",
					 	username: username, 
					 	password: password, 
					 	rememberme: rememberme, 
				 	},
					beforeSend: function(){
		               jQuery('#'+form_id+' .wd-loading--login-form').removeClass('hidden');
		               jQuery('#'+form_id+' .wd-login-form-field').attr('disabled', 'disabled');
		           	},
					success: function(data) {
						jQuery('#'+form_id+" .wd-loading--login-form").addClass('hidden');
						var data_obj = jQuery.parseJSON(data);

						var class_message = (data_obj.success) ? 'wd-notice wd-success-message' : 'wd-notice wd-error-message' ;
						jQuery('#'+form_id+' .wd-my-account-field-wrap').addClass('wd-my-account-field-success');

						jQuery.each( data_obj.mess, function( i, val ) {
						  	jQuery('#'+form_id+' .wd-login-'+i+'-alert').removeClass('hidden').html('<label class="'+class_message+'">'+val+'</label>');
						  	if (i === 'error' && val) {
						  		jQuery('#'+form_id+' .wd-my-account-field-wrap').addClass('wd-my-account-field-error').removeClass('wd-my-account-field-success');
						  	}
						});
						
						if (data_obj.success == true) {
							jQuery('#'+form_id+' .wd-my-account-field-wrap').addClass('wd-my-account-field-success').removeClass('wd-my-account-field-error');
							location.reload();
						}else{
							jQuery('#'+form_id+' .wd-login-form-field').removeAttr('disabled');
						}
					}
				});
			}
		});
		jQuery('.wd-login-form .wd-login-input').on('focus', function(){
			jQuery(this).parents('.wd-my-account-field-wrap').removeClass('wd-my-account-field-error wd-my-account-field-success');
			jQuery(this).next('.wd-alert').addClass('hidden').html('');
		});
	}
}

//Register with validate ajax
if (typeof wd_ajax_register_validate != 'function') { 
	function wd_ajax_register_validate() {
		jQuery( '.wd-register-form-with-validate-ajax' ).submit(function(e){
			e.preventDefault();
			var form_id 	= jQuery(this).find('.wd-register-btn').data('form_id');
			var username 	= jQuery('#'+form_id+' .wd-register-username').val();
			var email 		= jQuery('#'+form_id+' .wd-register-email').val();
			var password 	= jQuery('#'+form_id+' .wd-register-password').val();
			var password_confirmation 	= jQuery('#'+form_id+' .wd-register-password-confirmation').val();
			var terms 		= jQuery('#'+form_id+' .wd-register-terms:checked').val();

			jQuery('#'+form_id+' .wd-alert').addClass('hidden')
			if (username == '' || email == '' || password == '' || password == '' || password_confirmation == '') {
				jQuery('#'+form_id+' .wd-register-error-alert').removeClass('hidden').html('<label class="wd-notice wd-error-message">Please complete all information!</label>');
			}else{
				jQuery.ajax({
					type: 'POST',
					url: ajax_object.ajax_url,
					data: { 
						action: "register_with_validate_ajax",
					 	username: username, 
					 	email: email, 
					 	password: password, 
					 	password_confirmation: password_confirmation, 
					 	terms: terms, 
				 	},
					beforeSend: function(){
		               jQuery('#'+form_id+' .wd-loading--register-form').removeClass('hidden');
		               jQuery('#'+form_id+' .wd-register-form-field').attr('disabled', 'disabled');
		           	},
					success: function(data) {
						jQuery('#'+form_id+" .wd-loading--register-form").addClass('hidden');

						var data_obj = jQuery.parseJSON(data);
						var class_message = (data_obj.success) ? 'wd-notice wd-success-message' : 'wd-notice wd-error-message' ;
						jQuery('#'+form_id+' .wd-my-account-field-wrap').addClass('wd-my-account-field-success');

						jQuery.each( data_obj.mess, function( i, val ) {
						  	jQuery('#'+form_id+' .wd-register-'+i+'-alert').removeClass('hidden').html('<label class="'+class_message+'">'+val+'</label>');
						  	if (val) {
						  		jQuery('#'+form_id+' .register-'+i).addClass('wd-my-account-field-error').removeClass('wd-my-account-field-success');
						  	}
						});
						if (!data_obj.success) {
							//If an error occurred while registering, remove class disabled on fields.
							jQuery('#'+form_id+' .wd-register-form-field').removeAttr('disabled');
						}
					}
				});
			}
		});
		jQuery('.wd-register-form .wd-register-input').on('focus', function(){
			jQuery(this).parents('.wd-my-account-field-wrap').removeClass('wd-my-account-field-error wd-my-account-field-success');
			jQuery(this).next('.wd-alert').addClass('hidden').html('');
		});
	}
}

//Forgot Password with ajax
if (typeof wd_ajax_forgot_password != 'function') { 
	function wd_ajax_forgot_password() {
		jQuery( '.wd-forgot-password-form-with-validate-ajax' ).submit(function(e){
			e.preventDefault();
			var form_id 	= jQuery(this).find('.wd-forgot-password-btn').data('form_id');
			var username 	= jQuery('#'+form_id+' .wd-forgot-password-username').val();
			var action 		= jQuery('#'+form_id+' .wd-forgot-password-action').val();
			
			jQuery('#'+form_id+' .wd-alert').addClass('hidden')
			if (username == '') {
				jQuery('#'+form_id+' .wd-forgot-password-error-alert').removeClass('hidden').html('<label class="wd-notice wd-error-message">Please complete all information!</label>');
			}else{
				jQuery.ajax({
					type: 'POST',
					url: ajax_object.ajax_url,
					data: { 
						action: "forgot_password_with_ajax",
					 	username: username, 
					 	action_form: action, 
				 	},
					beforeSend: function(){
		               jQuery('#'+form_id+' .wd-loading--forgot-password-form').removeClass('hidden');
		               jQuery('#'+form_id+' .wd-forgot-password-form-field').attr('disabled', 'disabled');
		           	},
					success: function(data) {
						jQuery('#'+form_id+" .wd-loading--forgot-password-form").addClass('hidden');

						var data_obj = jQuery.parseJSON(data);
						var class_message = (data_obj.success) ? 'wd-notice wd-success-message' : 'wd-notice wd-error-message' ;
						jQuery('#'+form_id+' .wd-my-account-field-wrap').addClass('wd-my-account-field-success');

						jQuery.each( data_obj.mess, function( i, val ) {
						  	jQuery('#'+form_id+' .wd-forgot-password-'+i+'-alert').removeClass('hidden').html('<label class="'+class_message+'">'+val+'</label>');
						  	if (val) {
						  		jQuery('#'+form_id+' .forgot-password-'+i).addClass('wd-my-account-field-error').removeClass('wd-my-account-field-success');
						  	}
						});

						if (!data_obj.success) {
							jQuery('#'+form_id+' .wd-forgot-password-form-field').removeAttr('disabled');
						}
					}
				});
			}
		});
		jQuery('.wd-forgot-password-form .wd-forgot-password-input').on('focus', function(){
			jQuery(this).parents('.wd-my-account-field-wrap').removeClass('wd-my-account-field-error wd-my-account-field-success');
			jQuery(this).next('.wd-alert').addClass('hidden').html('');
		});
	}
}

//AJAX ADD TO CART FOR SIMPLE & VARIATION PRODUCT (SINGLE) 
if (typeof wd_ajax_add_to_cart_single_product != 'function') { 
	function wd_ajax_add_to_cart_single_product() {
		jQuery(".single_add_to_cart_button").on('click', function(e) {
			var _this			= jQuery(this);
			if (_this.hasClass('disabled')) {
				return;
			}
			var _mini_cart_wrap = '.wd-cart-flagments';
		    var product_id 		= _this.val();
		    var variation_id 	= jQuery('input[name="variation_id"]').val();
		    var quantity 		= jQuery('input[name="quantity"]').val();
		    var product_type	= '';

		    if (variation_id) {
		    	product_type	= 'variation';
		    }else if (product_id) {
		    	product_type	= 'simple';
		    }
		    var ajax_add_to_cart = (product_type == 'variation' || product_type == 'simple') ? true : false;

		    if (ajax_add_to_cart) {
		    	e.preventDefault();
		    	_this.addClass('loading').removeClass('added');
		    	jQuery.ajax ({
		            url: ajax_object.ajax_url,  
		            type:'POST',
		            data: { 
						action: "update_tini_cart_single_product",
					 	product_id: product_id, 
					 	variation_id: variation_id, 
					 	quantity: quantity, 
					 	product_type: product_type, 
				 	},
					success:function(data) {
						_this.removeClass('loading').addClass('added');
		                if( jQuery(_mini_cart_wrap).length > 0 ){
							jQuery(_mini_cart_wrap).replaceWith(data);
							wd_scroll_to_element(_mini_cart_wrap);
							setTimeout(function(){
								jQuery(_mini_cart_wrap+' .wd-dropdown-container').addClass('wd-showing-cart');
							},600);
							setTimeout(function(){
								jQuery(_mini_cart_wrap+' .wd-dropdown-container').removeClass('wd-showing-cart');
							},4000);
						}
		            }
		        });
		    } 
		});
	}
}