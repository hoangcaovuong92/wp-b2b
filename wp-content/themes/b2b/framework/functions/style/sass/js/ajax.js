//****************************************************************//
/*							Ajax JS								  */
//****************************************************************//
jQuery(document).ready(function($) {
	"use strict";
	//Compile SASS
	wd_ajax_compile_sass();
});

//****************************************************************//
/*							FUNCTIONS							  */
//****************************************************************//
//Empty Cart
if (typeof wd_ajax_compile_sass != 'function') { 
	function wd_ajax_compile_sass() {
		jQuery( document ).on('click', '.wd-ajax-compile-sass', function(e){
            e.preventDefault();
            var _this = jQuery(this);
            jQuery.ajax({
                type: 'POST',
                url: ajax_object.ajax_url,
                data: { 
                    action: "wd_ajax_compile_sass",
                },
                beforeSend: function(){
                    _this.attr("disabled","disabled").css('pointer-events', 'none').after(' (working...)');
                },
                success: function(response) {
                    if (response.success && response.data) {
                        _this.parents('.wd-compile-sass-notice').remove();
                        alert("Complete!!!");
                    }
                }
            });
        });
        
        jQuery( document ).on('click', '.wd-ajax-dismiss-compile-sass-notice', function(e){
            e.preventDefault();
            var _this = jQuery(this);
            jQuery.ajax({
                type: 'POST',
                url: ajax_object.ajax_url,
                data: { 
                    action: "wd_ajax_dismiss_compile_sass_notice",
                },
                beforeSend: function(){
                    _this.attr("disabled","disabled").css('pointer-events', 'none').after(' (working...)');
                },
                success: function(response) {
                    //If success, remove notice element
                    if (response.success && response.data) {
                        _this.parents('.wd-compile-sass-notice').remove();
                    }
                }
            });
		});
	}
}