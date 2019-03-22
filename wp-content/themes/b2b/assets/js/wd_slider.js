//****************************************************************//
/*							MOBILE JS							  */
//****************************************************************//
jQuery(window).ready(function($) {
	"use strict";
	wd_auto_detect_slider(); //Auto Slider
	//On window Resize
	window.addEventListener('resize', wd_slider_window_resize);
});

//****************************************************************//
/*							FUNCTIONS							  */
//****************************************************************//
//On window Resize
if (typeof wd_slider_window_resize != 'function') { 
	function wd_slider_window_resize() {
	}
}

if (typeof wd_slick_slider_call != 'function') {
	function wd_slick_slider_call($sliderWrap, custom_options) {
		var default_options = {
			slider_type : 'owl',
			column_mobile : 1,
			column_tablet : 2,
			column_desktop : 4,
			autoplay : true,
			autoplaySpeed : 2000,
			arrows : true,
			dots : false,
			infinite : true,
			centerMode : false,
			centerPadding : '60px',
			vertical : false,
			autoHeight:true
		}
		custom_options = jQuery.extend(default_options, custom_options);
		if (custom_options.slider_type === 'slick') {
			if ($sliderWrap.hasClass('slick-initialized')) {
				$sliderWrap.slick('unslick');
			}
	
			var options = {
				centerMode		: custom_options.centerMode,
				centerPadding	: custom_options.centerPadding,
				autoplay 		: custom_options.autoplay,
				autoplaySpeed	: custom_options.autoplaySpeed,
				arrows			: custom_options.arrows,
				dots			: custom_options.dots,
				vertical 		: custom_options.vertical,
				infinite 		: custom_options.infinite,
				slidesToShow	: custom_options.column_desktop,
				slidesToScroll	: custom_options.column_desktop,
				prevArrow 		: '<button type="button" class="slick-prev slick-arrow"></button>',
				nextArrow 		: '<button type="button" class="slick-next slick-arrow"></button>',
				responsive 		:  [
					{
						breakpoint			: responsive.tablet,
						settings 			: {
							slidesToShow	: custom_options.column_tablet,
							slidesToScroll	: custom_options.column_tablet,
						}
					},
					{
						breakpoint			: responsive.mobile,
						settings			: {
							slidesToShow	: custom_options.column_mobile,
							slidesToScroll	: custom_options.column_mobile,
						}
					}
				]
			}
	
			if (options.vertical !== undefined && options.vertical) {
				options.prevArrow = '<button type="button" class="slick-up slick-arrow"></button>';
				options.nextArrow = '<button type="button" class="slick-down slick-arrow"></button>';
			}
			return $sliderWrap.not('.slick-initialized').slick(options);
		}else{
			var options = {
				loop : custom_options.infinite,
				items : custom_options.column_desktop,
				slideBy: custom_options.column_desktop,
				nav : custom_options.arrows,
				margin: 30,
				dots : custom_options.dots,
				navSpeed : 1000,
				rtl: jQuery('body').hasClass('rtl'),
				navRewind: false,
				autoplay: custom_options.autoplay,
				autoplayTimeout: 5000,
				autoplayHoverPause: true,
				autoplaySpeed: custom_options.autoplaySpeed,
				mouseDrag: true,
				touchDrag: false,
				responsiveBaseElement: $sliderWrap,
				responsiveRefreshRate: 1000,
				responsive:{
					0:{
						items : custom_options.column_mobile,
						slideBy : custom_options.column_mobile,
					},
					480:{
						items : custom_options.column_tablet,
						slideBy : custom_options.column_tablet,
						margin: 15,
					},
					768:{
						items : custom_options.column_desktop,
						slideBy : custom_options.column_desktop,
						margin: 30,
					}
				},
				onInitialized: function(){}
			}
			return $sliderWrap.owlCarousel(options);
		}
	}
}

// Set class wd-slider-wrap to slider wrapper
// Set data-slider-type="slick" //or owl
// Set data-slider-options='{}' //array of slider options
// default_options = {
// 		"column_mobile" : 1,
// 		"column_tablet" : 2,
// 		"column_desktop" : 4,
// 		"autoplay" : true,
// 		"autoplaySpeed" : 2000,
// 		"arrows" : true,
// 		"dots" : false,
// 		"infinite" : true,
// 		"centerMode" : false,
// 		"centerPadding" : '60px',
// 		"vertical" : false,
// 	}
if (typeof wd_auto_detect_slider != 'function') { 
	function wd_auto_detect_slider(){
		if(jQuery('.wd-slider-wrap').length > 0 ){
			jQuery('.wd-slider-wrap').each(function(i, item){
				$slider_wrap = jQuery(item);
				var options = $slider_wrap.data('slider-options');
				options = jQuery.type(options) !== 'object' ? {} : options;
				wd_slick_slider_call($slider_wrap, options);
			})
		}
	}
}