//****************************************************************//
/*							Menu JS								  */
//****************************************************************//
jQuery(document).ready(function($) {
	"use strict";
	wd_menu_general_script(); //Menu mega, menu mobile
	wd_sticky_menu(); //Sticky menu
	//Mobile
	wd_panel_mobile();
	wd_menu_mobile();
	wd_sticky_menu_mobile();
	wd_fix_wpadminbar_position_on_mobile();
	//On window Resize
	window.addEventListener('resize', wd_menu_window_resize);
	//console.log(responsive);
});

//****************************************************************//
/*							FUNCTIONS							  */
//****************************************************************//
//On window Resize
if (typeof wd_menu_window_resize != 'function') { 
	function wd_menu_window_resize() {
		wd_sticky_menu();
		wd_sticky_menu_mobile();
		wd_fix_wpadminbar_position_on_mobile();
		wd_menu_general_script();
	}
}

//Sticky menu
//Add class wd-sticky-menu to menu wrap element (row or column)
//Add class wd-special-sticky-menu if is special sticky (Use in case of not using the main menu as a sticky menu and the use of a separate sticky is necessary.)
if (typeof wd_sticky_menu != 'function') { 
	function wd_sticky_menu() {
		if (jQuery('#header').length && jQuery('#wd-header-main-breadcrumb').length && jQuery('.wd-sticky-menu').length) {
			var mode = 'normal'; //or visual_composer
			//Get header height (position from content element)
			var $main_content = jQuery("#wd-header-main-breadcrumb");
			var top = $main_content.offset().top;

		    //Sticky element
			var $header_content = jQuery(".wd-header-desktop .wd-sticky-menu");

			//visual_composer only
		    var special_menu	= $header_content.hasClass('wd-special-sticky-menu');
		    //get default style of sticky element
			var default_style 	= $header_content.attr('style');
			  
		  	//Logo element (if available)
		    var $logo_image 	= $header_content.find('.header-main-logo img');
		   
		    //get current position of scroll top
			var winTop 			= jQuery(window).scrollTop();
			
		    jQuery( '#wpadminbar' ).attr('style', '');
    		
		    var timer;
		    jQuery(window).scroll(function() {
		    	//get current position of scroll top after croll
				winTop = jQuery(window).scrollTop();
				var win_width = jQuery(window).width();
				if (win_width < responsive.desktop) return;

		        if (timer) clearTimeout(timer);
		        timer = setTimeout(function() {
					//Top padding if existing admin bar (logged in)
					var admin_bar_height = jQuery('#wpadminbar').height() ? jQuery('#wpadminbar').height() : 0;
					//Style for sticky element
					var sticky_css		= 	{
						'position': 'fixed', 
						'top': admin_bar_height,
						'background': 'rgba(255, 255, 255, 1)', 
						'width': '100vw',
						'z-index': '99',
						'left': '0',
						'right': '0',
					};

					//get default style of sticky element after croll
					if (mode === 'visual_composer') { //if sticky element is row
						default_style 	= !default_style ? $header_content.attr('style') : default_style;
					}

		            if (winTop > top) {
						//If scroll over header, add attributes to sticky menu
		                $header_content.css(sticky_css);
		                $logo_image.css('height', '20px');
						$header_content.css('display', 'block');
						
						if (jQuery(window).width() >= responsive.desktop) {
							jQuery( '#wpadminbar' ).css('position', 'fixed');
						}
		            }else{
		            	//Returns the default styles of the menu if at the top.
		            	if (!$header_content.hasClass('wpb_column')  && mode === 'visual_composer') { //if sticky element is row
		            		$header_content.attr('style', default_style).css('top', '');
		            	}else{ //if sticky element is column, remove all style inline!
		            		$header_content.attr('style', '');
						}
						
						$logo_image.css('height', '');
						jQuery( '#wpadminbar' ).css('position', '');
						   
						
		            	if (special_menu && mode === 'visual_composer') {
			            	$header_content.css('display', 'none');
			            }
		            }
		        }, 200);
			});
			// Trigger the scroll event
			jQuery(window).scroll();
		}
	}
}

//Menu mobile
if (typeof wd_menu_mobile != 'function') { 
	function wd_menu_mobile(){
		var menu_has_children 	= jQuery(".wd-menu-mobile li.menu-item-has-children > a, .wd-menu-mobile li.page_item_has_children > a");
		menu_has_children.click(function(){
			menu_has_children.not(this).parents('li').removeClass('wd-submenu-opened');
			jQuery(this).parents('li').toggleClass('wd-submenu-opened');
		});

		var options = {};
		if (jQuery('.menu-item-has-children').length > 0) {
			options = {
				classParent: 'menu-item-has-children', //Class of parent menu item
				classActive: 'wd-submenu-opened', // Class of active parent link
				classArrow: 'dcjq-icon', // Class of span tag for parent arrows
				classCount: 'dcjq-count', // Class of span tag containing count (if addCount: true)
				classExpand: 'current-menu-item', // Class of parent li tag for auto-expand option
				eventType: 'click', // Event for activating menu - options are "click" or "hover"
				hoverDelay: 300, // Hover delay for hoverIntent plugin
				menuClose: true, // If set "true" with event "hover" menu will close fully when mouseout
				autoClose: true, // If set to "true" only one sub-menu open at any time
				autoExpand: false, // If set to "true" all sub-menus of parent tags with class 'classExpand' will expand on page load
				speed: 'medium', // Speed of animation
				saveState: false, // Save menu state using cookies
				disableLink: true, // Disable all links of parent items
				showCount: false, // If "true" will add a count of the number of links under each parent menu item
				cookie: 'dcjq-accordion' // Sets the cookie name for saving menu state - each menu instance on a single page requires a unique cookie name.
			}
		}else if (jQuery('.page_item_has_children').length > 0){
			options = {
				classParent: 'page_item_has_children', //Class of parent menu item
				classActive: 'wd-submenu-opened', // Class of active parent link
				classArrow: 'dcjq-icon', // Class of span tag for parent arrows
				classCount: 'dcjq-count', // Class of span tag containing count (if addCount: true)
				classExpand: 'current_page_item', // Class of parent li tag for auto-expand option
				eventType: 'click', // Event for activating menu - options are "click" or "hover"
				hoverDelay: 300, // Hover delay for hoverIntent plugin
				menuClose: true, // If set "true" with event "hover" menu will close fully when mouseout
				autoClose: true, // If set to "true" only one sub-menu open at any time
				autoExpand: false, // If set to "true" all sub-menus of parent tags with class 'classExpand' will expand on page load
				speed: 'medium', // Speed of animation
				saveState: false, // Save menu state using cookies
				disableLink: true, // Disable all links of parent items
				showCount: false, // If "true" will add a count of the number of links under each parent menu item
				cookie: 'dcjq-accordion' // Sets the cookie name for saving menu state - each menu instance on a single page requires a unique cookie name.
			}
		}

		if (options) {
			//jQuery('.wd-menu-mobile > ul, .wd-menu-mobile > div > ul').dcAccordion(options);
		}
	}
}

//Panel mobile
if (typeof wd_panel_mobile != 'function') { 
	function wd_panel_mobile(){
		var pushmenu_open_class = "wd-panel-opened";

		jQuery( ".wd-panel-mobile-wrap" ).each(function( index ) {
			var id = jQuery(this).attr('id');
			//Close button
			jQuery(this).find('.wd-panel-title').append('<a data-panel-target="#'+id+'" class="wd-navUser-action wd-navUser-action--pushmenu wd-panel-action wd-panel-action--close"><span class="fa fa-times-circle-o wd-icon"></span></a>');
			//Overlay
		});

		jQuery(".wd-panel-action").on('click', function(e){
			e.preventDefault();
			//If overlay element is not exist, append it to body
			if (jQuery('#wd-panel-overlay').length == 0) {
				jQuery('body').append('<div id="wd-panel-overlay"></div>');
			}

			//Get panel content element
			var target = jQuery(this).data('panel-target');
			//Hide other panels
			jQuery(".wd-panel-mobile-wrap."+pushmenu_open_class).not(target).removeClass(pushmenu_open_class);
			jQuery('.wd-panel-action.'+pushmenu_open_class).not('[data-panel-target="'+target+'"]').removeClass(pushmenu_open_class);

			//Toggle open class
			jQuery(target).toggleClass(pushmenu_open_class);
			jQuery('.wd-panel-action[data-panel-target="'+target+'"]').toggleClass(pushmenu_open_class);
			jQuery(".body-wrapper, body").toggleClass(pushmenu_open_class);
		});

		jQuery('body').on('click', '#wd-panel-overlay', function(){
			//Hide all of panels
			jQuery(".wd-panel-mobile-wrap."+pushmenu_open_class).removeClass(pushmenu_open_class);
			jQuery('.wd-panel-action.'+pushmenu_open_class).removeClass(pushmenu_open_class);
			jQuery(".body-wrapper, body").removeClass(pushmenu_open_class);
		})

		//https://stackoverflow.com/questions/12661797/jquery-click-anywhere-in-the-page-except-on-1-div
		// jQuery('body').on('click', function(e){
		// 	if(jQuery('body').hasClass(pushmenu_open_class)) {
		// 		console.log(e.target.class);
		// 		console.log(jQuery(e.target).closest('.wd-panel-mobile-wrap').length);
		// 		if(e.target.class == ".wd-panel-mobile-wrap")
		// 		return;
		// 		//For descendants of menu_content being clicked, remove this check if you do not want to put constraint on descendants.
		// 		if(jQuery(e.target).closest('.wd-panel-mobile-wrap').length)
		// 		return;             
		
		// 		//Do processing of click event here for every element except with class wd-panel-mobile-wrap
		// 		jQuery(".wd-panel-mobile-wrap, .wd-panel-action, .body-wrapper, body").removeClass(pushmenu_open_class);
		// 	}
	 	// });
	}
}

//Menu fixed on mobile
if (typeof wd_sticky_menu_mobile != 'function') { 
	function wd_sticky_menu_mobile(){
		if (jQuery('#header').length && jQuery('.wd-panel-mobile-wrap').length && jQuery('.wd-header-mobile').length && jQuery('#wd-header-main-breadcrumb').length) {
			var $panel_mobile = jQuery(".wd-panel-mobile-wrap");
			var $header_content = jQuery(".wd-header-mobile");
			var $main_content = jQuery("#wd-header-main-breadcrumb");
		    var top = $main_content.length > 0 ? $main_content.offset().top : 10;
			
			var timer;
			jQuery(window).scroll(function () {
				if (timer) clearTimeout(timer);
				timer = setTimeout(function(){
					var win_width = jQuery(window).width();
					var admin_bar_height = jQuery('#wpadminbar').height() ? jQuery('#wpadminbar').height() : 0;
					var winTop 		= jQuery(window).scrollTop();
					if (win_width < responsive.tablet) {
						if (winTop > top) { //if scroll over header
							jQuery( '#wpadminbar' ).css('position', 'absolute');
							$panel_mobile.css({"position": 'fixed', "top": 0});
							$header_content.css({"top": 0});
						}else{
							jQuery( '#wpadminbar' ).css('position', 'fixed');
							$panel_mobile.css({"position": 'fixed', "top": admin_bar_height});
							$header_content.css({"position": ''});
						}
					}else if(win_width >= responsive.tablet && win_width < responsive.desktop){
						if (winTop > top) { //if scroll over header
							$panel_mobile.css({"position": 'fixed', "top": admin_bar_height});
							$header_content.css({"top": admin_bar_height});
						}else{
							$panel_mobile.css({"position": 'fixed', "top": admin_bar_height});
							$header_content.css({"position": '', "top": 0});
						}
					}else{
						$panel_mobile.css({"position": '',"top": ''});
					}
				}, 200);
			});
			// Trigger the scroll event
			jQuery(window).scroll();
		}
	}
}

//Fix wpadminbar position on mobile
if (typeof wd_fix_wpadminbar_position_on_mobile != 'function') { 
	function wd_fix_wpadminbar_position_on_mobile(){
		var win_width = jQuery(window).width();
        // get adminbar height, 'css' will be true if bar is present, false if not
		var ah 		= jQuery( '#wpadminbar' ).outerHeight();
        var mobile  = (win_width <= responsive.desktop && typeof( ah ) !== 'undefined') ? true : false;
      	var $head 	= jQuery('head');
        // if this has been written before, remove old version
        $head.find('#wpadfx').remove();
        if (mobile) {
        	 // if 'css' is true, change value to CSS rules
	        css = 'html{margin-top:initial !important}'
	                + 'body::before{content:"";height:' + ah + 'px;display:block}' 
					+ '.mobile{position:fixed;width:100%;top:0;left:0;}'
					+ 'body.logged-in{padding-top:60px;}'
	        // append new if bar is present
	        $head.append('<style id="wpadfx">' + css + '</style>');
        }
	}
}

//Menu general
if (typeof wd_menu_general_script != 'function') { 
	function wd_menu_general_script(){
		//Mega menu
		jQuery('a.wd-mega-menu').next().addClass('wd-mega-menu-wrap');
		if (jQuery('#header').length && jQuery('#wd-header-main-breadcrumb').length) {
			//Add class to header
			var win_width = jQuery(window).width();
			var $header = jQuery('header#header');
			var header_content_height = win_width > responsive.desktop ? $header.find('.wd-header-desktop').height() : $header.find('.wd-header-mobile').height();

			var $main_content = jQuery("#wd-header-main-breadcrumb");
			var top = $main_content.offset().top;

			var $stickymenu_placeholder = jQuery(".wd-stickymenu-placeholder");

			if (jQuery('.wd-sticky-menu').length > 0) {
				$stickymenu_placeholder.css('height', header_content_height);
			}

			var timer;
			jQuery(window).scroll(function() {
				//get current position of scroll top after croll
				winTop = jQuery(window).scrollTop();
				if (timer) clearTimeout(timer);
				timer = setTimeout(function() {
					if (winTop > top) {
						//add class sticky to header element after croll
						$header.addClass('menu-sticky');
						$stickymenu_placeholder.addClass('menu-sticky');
					}else{
						//add class sticky to header element after croll
						$header.removeClass('menu-sticky');
						$stickymenu_placeholder.removeClass('menu-sticky');
					}
				}, 200);
			});

			// Trigger the scroll event
			jQuery(window).scroll();
		}
	}
}