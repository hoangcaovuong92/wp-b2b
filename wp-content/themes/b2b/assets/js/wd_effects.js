//****************************************************************//
/*							Effect JS							  */
//****************************************************************//
jQuery(document).ready(function($) {
	"use strict";

    //Sidebar scroll fixed
	if (effects_status.sidebar_scroll) {
		wd_sidebar_sticky();
    }
    //On window Resize
	window.addEventListener('resize', wd_effect_window_resize);
});

//****************************************************************//
/*                          FUNCTIONS                             */
//****************************************************************//
//On window Resize
if (typeof wd_effect_window_resize != 'function') { 
	function wd_effect_window_resize() {
		//wd_sidebar_sticky();
	}
}

 //Sidebar scroll fixed
if (typeof wd_sidebar_sticky != 'function') { 
    function wd_sidebar_sticky() {
        var admin_bar_height = jQuery('#wpadminbar').height() ? jQuery('#wpadminbar').height() : 0;
        var top = (jQuery('.wd-sticky-menu').length > 0) ? admin_bar_height + 55 : admin_bar_height + 5;
        jQuery('.wd-sidebar').hcSticky({
            top: top,
            followScroll: true,
            offResolutions: -991,
            responsive: true,
            stickTo: '.wd-content-page.wd-main-content'
        });
    }
}