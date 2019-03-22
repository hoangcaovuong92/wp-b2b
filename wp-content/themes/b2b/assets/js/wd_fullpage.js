//****************************************************************//
/*							Main JS								  */
//****************************************************************//
jQuery(document).ready(function($) {
	"use strict";
	wd_fullpage_js(); //Fullpage JS
});

//****************************************************************//
/*							FUNCTIONS							  */
//****************************************************************//
//Fullpage JS
if (typeof wd_fullpage_js != 'function') { 
	function wd_fullpage_js(){
		if(jQuery('#fullpage').length > 0 ){
			var device 			= jQuery('#fullpage').data('device');
			if (device == 'desktop') {
				var element = jQuery('#fullpage .wd-shortcode-fullpage-wrap');
				jQuery('#header, #footer').addClass('section fp-auto-height');
				jQuery('#header').prependTo(element);
				jQuery('#footer').appendTo(element);
				element.find('.section').css('opacity', '1');
				element.fullpage({
					scrollOverflow: true,
					scrollBar: true,
					scrollHorizontally: false,
					autoScrolling: true,
					verticalCentered: false,
					css3: true,
					lazyLoading: true,
					parallax: true,
					parallaxOptions: {type: 'reveal', percentage: 62, property: 'translate'},
					scrollingSpeed: 1000,
					fitToSection: true,
					fitToSectionDelay: 500,
					navigation: true,
					navigationPosition: 'right',
					sectionSelector: '.section',
					/*menu: '.wd-navmenu',*/
					loopBottom: false,
				});
			}else{
				var element = jQuery('#fullpage .wd-shortcode-fullpage-wrap');
				jQuery('#header, #footer').addClass('section fp-auto-height');
				jQuery('#header').prependTo(element);
				jQuery('#footer').appendTo(element);
				element.find('.section').css('opacity', '1');
				element.fullpage({
					scrollOverflow: false,
					scrollBar: false,
					autoScrolling: false,
					scrollHorizontally: false,
					verticalCentered: false,
					css3: true,
					lazyLoading: true,
					parallax: true,
					parallaxOptions: {type: 'reveal', percentage: 62, property: 'translate'},
					scrollingSpeed: 1000,
					fitToSection: true,
					fitToSectionDelay: 500,
					navigation: true,
					navigationPosition: 'right',
					/*menu: '.wd-navmenu',*/
					loopBottom: false,
				});
			}
		}
	}
}