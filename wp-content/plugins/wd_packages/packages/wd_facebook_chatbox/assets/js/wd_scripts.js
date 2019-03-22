//****************************************************************//
/*                              JS                               */
//****************************************************************//
jQuery(document).ready(function($) {
    "use strict";
    wd_accessibility_chatbox_facebook();
});

//****************************************************************//
/*							FUNCTIONS							  */
//****************************************************************//
//Chatbox facebook script
if (typeof wd_accessibility_chatbox_facebook != 'function') { 
	function wd_accessibility_chatbox_facebook() {
        if (jQuery(".wd-facebook-chatbox-wrap").length > 0) {
            var raido = jQuery(".wd-facebook-chatbox-wrap").data("toggle");
            if (raido == 1) {
                jQuery(".wd-facebook-chatbox-footer").css("display", "none");
                jQuery(".wd-facebook-chatbox-close-btn").click(function() {
                    jQuery(".wd-facebook-chatbox-wrap").slideToggle();
                    jQuery(".wd-facebook-chatbox-footer").slideToggle();
                });
                jQuery(".wd-facebook-chatbox-footer").click(function() {
                    jQuery(".wd-facebook-chatbox-wrap").slideToggle();
                    jQuery(this).slideToggle();
                });
            } else {
                jQuery(".wd-facebook-chatbox-wrap").css("display", "none");
                jQuery(".wd-facebook-chatbox-close-btn").click(function() {
                    jQuery(".wd-facebook-chatbox-wrap").slideToggle();
                    jQuery(".wd-facebook-chatbox-footer").slideToggle();
                });
                jQuery(".wd-facebook-chatbox-footer").click(function() {
                    jQuery(".wd-facebook-chatbox-wrap").slideToggle();
                    jQuery(this).slideToggle();
                });
            }
        }
	}
}