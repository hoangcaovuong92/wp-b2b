//****************************************************************//
/*							Main JS								  */
//****************************************************************//
jQuery(document).ready(function($) {
	"use strict";
	var window_width 	= jQuery(window).width();
	wd_blog_grid_list_toggle(); //PRODUCT GRID/LIST TOGGLE
	wd_sidebar(); //Sidebar
	wd_comment_form(); //Custom comment form
	wd_search_form(); //Search Form
	wd_back_to_top_button(); //Scroll Button
	wd_messenger_notice(); //WD Messager notice
	wd_mansory_layout(); //Post masonry layout script
	wd_fancybox_script(); //Fancybox
	wd_tooltip(); //tooltip bootstrap
	wd_select2_form(); //select2 script
	window.addEventListener('resize', wd_main_window_resize); //On window Resize
	//console.log(responsive);
});

jQuery(window).ready(function($) {
	"use strict";
	//Add custom class to body element
	wd_add_class_to_body();
});

//****************************************************************//
/*							FUNCTIONS							  */
//****************************************************************//
//On window Resize
if (typeof wd_main_window_resize != 'function') { 
	function wd_main_window_resize() {
		//wd_back_to_top_button();
	}
}

//Highlight keyword from text
if (typeof wd_highlight_string != 'function') { 
	function wd_highlight_string(string, key){
		String.prototype.insertAt = function(index, string) { 
			return this.substr(0, index) + string + this.substr(index);
		}
		string = string.toUpperCase();
		key = key.toUpperCase();
		var key_pos = string.indexOf(key);
		string = string.insertAt(key_pos, '<span class="wd-search-highlight">');
		key_pos = string.indexOf(key) + key.length;
		string = string.insertAt(key_pos, '</span>');
		return string;
	}
}

//Sidebar collapse 
if (typeof wd_sidebar != 'function') { 
	function wd_sidebar(){
	  	jQuery('.wd-sidebar').on('click', function(){
		});
	}
}

//Custom comment form
if (typeof wd_comment_form != 'function') { 
	function wd_comment_form(){
		if(jQuery('.wd-blog-comment-tab').length > 0 ){
			jQuery('body').on('click', '.wd-blog-comment-tab .nav-tabs > li', function(){
				jQuery('#cancel-comment-reply-link').click();
			});
		}
	}
}

//Search form
if (typeof wd_search_form != 'function') { 
	function wd_search_form(){
		//Search Hover
		var _form_text = jQuery( ".wd-search-form-default .wd-search-form-text" );
		var _form_wrap = _form_text.parents(".wd-search-form-wrapper");

		//On focus input field
		_form_text.focus(function() {
			if (!_form_wrap.hasClass("wd-search-typing")) {
				_form_wrap.addClass('wd-search-typing');
			}
		}).blur(function() {
			if (_form_wrap.hasClass("wd-search-typing")) {
				setTimeout(function(){ 
				  	_form_wrap.removeClass('wd-search-typing');
				}, 500);
			}
		});
		
		//Trigger clear search result
		jQuery('body').on('clear_search_result', function(){
			jQuery('.wd-search-form-ajax-result').html('');
		});

		//Search Popup
		//jQuery(".popup").hide();

		jQuery(".wd-click-popup-search").click(function (e) {
			e.preventDefault();
			jQuery('body').trigger('clear_search_result');
			jQuery(this).parents('.wd-header-content-wrap').find('.wd-popup-search-result').toggleClass("wd-search-open");

			var $input_field = jQuery(this).parents('.wd-header-content-wrap').find('.wd-search-form-text');
			var tmp = $input_field.val(); 
			$input_field.focus().val("").blur().focus().val(tmp);
			$input_field.focus();
		});

		jQuery('.wd-popup-search-close').on('click', function(e){
			e.preventDefault();
			//Clear search ajax results
			jQuery('body').trigger('clear_search_result');
			//Close search ajax form
			jQuery('.wd-popup-search-result').removeClass("wd-search-open");
		})
	}
}

//BLOG GRID/LIST TOGGLE
if (typeof wd_blog_grid_list_toggle != 'function') { 
	function wd_blog_grid_list_toggle(){
		var wrap = '.wd-layout-toggle-wrap.wd-layout-toggle-blog';
		if(jQuery(wrap).length > 0 ){
			var $action = jQuery(wrap + ' .wd-grid-list-toggle-action');
			var $grid = jQuery(wrap + ' .wd-grid-list-toggle-action[data-layout="grid"]');
			var $list = jQuery(wrap + ' .wd-grid-list-toggle-action[data-layout="list"]');
			var $blog_wrap = jQuery('.wd-blog-wrapper.wd-blog-switchable-layout');

			//Default cookie
			if (Cookies.get('blog-layout-cookie') === undefined) {
				Cookies.set('blog-layout-cookie','grid', { path: '/' });
			}
			$action.on('click', function(e) {
				e.preventDefault();
				var layout = jQuery(this).data('layout');
				$action.removeClass('active');
				jQuery(this).addClass('active')
				if (layout === 'grid') {
					Cookies.set('blog-layout-cookie','grid', { path: '/' });
					$blog_wrap.addClass('grid').removeClass('list');
				}else{
					Cookies.set('blog-layout-cookie','list', { path: '/' });
					$blog_wrap.removeClass('grid').addClass('list');
				}
				wd_mansory_layout();
			});

			if (Cookies.get('blog-layout-cookie') == 'grid') {
				$grid.trigger('click');
			}

			if (Cookies.get('blog-layout-cookie') == 'list') {
				$list.trigger('click');
			}
		}
	}
}

//select2 script
if (typeof wd_select2_form != 'function') { 
	function wd_select2_form(){
		if (jQuery('.wd-select2-element').length > 0) {
			jQuery('.wd-select2-element').select2();
		}
	}	
}

if (typeof wd_tooltip != 'function') { 
	function wd_tooltip(){
		jQuery('[data-toggle="tooltip"]').tooltip();
	}
}

if (typeof wd_add_class_to_body != 'function') { 
	function wd_add_class_to_body(){
		jQuery('body').addClass('loaded');
	}
}

//WD Messager notice
if (typeof wd_messenger_notice != 'function') { 
	function wd_messenger_notice(){
		if(jQuery('.wd-message-closebtn').length > 0 ){
			jQuery('.wd-message-closebtn').click(function(){
				jQuery(this).parents('.wd-message').slideUp();
			});
		}
	}
}

//Blog Masonry
if (typeof wd_mansory_layout != 'function') { 
	function wd_mansory_layout(parent_element = '.wd-masonry-wrap', item_element = '.wd-masonry-item'){
		setTimeout(function(){
			if(jQuery(parent_element).length > 0 ){
				jQuery(parent_element).each(function(index,value){
					jQuery(value).isotope({
						layoutMode: 'masonry',
						itemSelector: item_element,
						masonry: {
							columnWidth: item_element
						}
					});
					jQuery('img').load(function(){
						jQuery(value).isotope({
							layoutMode: 'masonry',
							itemSelector: item_element,
							masonry: {
								columnWidth: item_element
							}
						});
					});
				});	
			}
		}, 400);
	}
}

//Fancybox
if (typeof wd_fancybox_script != 'function') {
	function wd_fancybox_script() {
		// jQuery(".wd-click-popup-search").click(function () {
		// 	var target = jQuery(this).data('target');
		// 	var ajax_search = jQuery(this).data('ajax');
		// 	if (target) {
		// 		jQuery.fancybox('#'+target, {
		// 			openEffect: 'fade',
		// 			closeEffect: 'fade',
		// 			padding: ajax_search == 1 ? [15, 15, 15, 15] : [75, 15, 15, 15],
		// 			margin: [0, 0, 0, 0],
		// 			fitToView: false,
		// 			autoSize: false,
		// 			width: '85%',
		// 			height: ajax_search == 1 ? '85%' : 'auto',
		// 			closeBtn: true,
		// 			arrows: true,
		// 			helpers: {
		// 				overlay: {
		// 					css: {
		// 						'background': 'rgba(58, 42, 45, 0.5)'
		// 					},
		// 				}
		// 			},
		// 			onComplete: function () {},
		// 			beforeShow: function () {
		// 				jQuery('.wd-search-form-text').focus();
		// 			},
		// 			afterClose: function () {},
		// 			afterLoad: function () {}
		// 		});
		// 	}
		// });

		jQuery(".wd-fancybox-image, .wd-fancybox-image-gallery").fancybox({
			openEffect: 'fade',
			closeEffect: 'fade',
			margin: [20, 20, 20, 20],
			padding: 10,
			width: 'auto',
			height: 'auto',
			fitToView: true,
			autoSize: false,
			closeBtn: true,
			arrows: true,
			type: 'image',
			helpers: {
				overlay: {
					css: {
						'background': 'rgba(58, 42, 45, 0.5)'
					},
				}
			},
			onComplete: function () {},
			beforeShow: function () {},
			afterClose: function () {},
			afterLoad: function () {}
		});
	}
}

//Back to top Button
if (typeof wd_back_to_top_button != 'function') { 
	function wd_back_to_top_button(){
		if (jQuery('#wd-back-to-top').length && jQuery('#wd-header-main-breadcrumb').length) {
			var btt_btn = jQuery("#wd-back-to-top");
			var timer;
			jQuery(window).scroll(function () {
				if (timer) clearTimeout(timer);
				timer = setTimeout(function(){
					var win_width = jQuery(window).width();
					var winTop = jQuery(window).scrollTop();
					var $main_content = jQuery("#wd-header-main-breadcrumb");
					var top = $main_content.offset().top;
					if (winTop > top && win_width >= responsive.desktop) {
						btt_btn.fadeIn();
					} else {
						btt_btn.fadeOut();
					}
				}, 200);
			});
			// Trigger the scroll event
			jQuery(window).scroll();
			btt_btn.click(function(){
				jQuery('body,html').animate({
					scrollTop: '0px'
				}, 1000);
				return false;
			});
		}
	}
}

//Scroll to a element
if (typeof wd_scroll_to_element != 'function') { 
	function wd_scroll_to_element(element){
		var position = jQuery(element).offset().top - 40;
	    jQuery('html,body').animate({
	        scrollTop: position}, 'slow');
	}
}

//Return True if is touch device
if (typeof checkIfTouchDevice != 'function') { 
    function checkIfTouchDevice(){
        touchDevice = !!("ontouchstart" in window) ? 1 : 0; 
		if( jQuery.browser.wd_mobile ) {
			touchDevice = 1;
		}
		return touchDevice;      
    }
}