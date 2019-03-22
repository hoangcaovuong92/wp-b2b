//**************************************************************//
/*							METABOX JS							*/
//**************************************************************//
jQuery(document).ready(function($) {
	"use strict";
	wd_copy_shortcode();
});

//****************************************************************//
/*							FUNCTIONS							  */
//****************************************************************//
//Copy content
if (typeof wd_copy_shortcode != 'function') { 
	function wd_copy_shortcode() {
        jQuery('.wd-copy-text').on('click', function(e){
            e.preventDefault();
            var target = jQuery(this).data('target_id');
            if (jQuery('#'+target).length > 0 && jQuery('#'+target).val()) {
                clipboard.writeText(jQuery('#'+target).val());
                jQuery(this).next('.wd-copy-status').show();
                setTimeout(() => {
                    jQuery(this).next('.wd-copy-status').hide();
                }, 1000);
            }
        })
	}
}