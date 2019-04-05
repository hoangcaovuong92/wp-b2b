//**************************************************************//
/*							METABOX JS							*/
//**************************************************************//
jQuery(document).ready(function($) {
	"use strict";
    wd_copy_shortcode();
    
    wd_metabox_datepicker();
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


//Datepicker expand
if (typeof wd_get_date != 'function') {
    function wd_get_date(element) {
       var date;
       try {
           date = jQuery.datepicker.parseDate(dateFormat, element.value);
       } catch (error) {
           date = null;
       }
       return date;
   }
}

//Metabox Datepicker
if (typeof wd_metabox_datepicker != 'function') {
   function wd_metabox_datepicker() {
       //Timepicker normal
       jQuery(".wd-timepicker").timepicker({
            dropdown: true,
            scrollbar: true
        });

       var dateFormat = "dd/mm/yy";

       //Datepicker normal
       jQuery(".wd-datepicker").datepicker({
           dateFormat: dateFormat,
           //numberOfMonths: 3,
           showButtonPanel: true
       });

       //Datepicker from/to
       var from = jQuery(".wd-datepicker-from").datepicker({
           dateFormat: dateFormat,
           defaultDate: "+1w",
           changeMonth: true,
           //numberOfMonths: 3
       }).on("change", function(e) {
           to.datepicker("option", "minDate", wd_get_date(this));
       });

       var to = jQuery(".wd-datepicker-to").datepicker({
           dateFormat: dateFormat,
           defaultDate: "+1w",
           changeMonth: true,
           //numberOfMonths: 3
       }).on("change", function(e) {
           from.datepicker("option", "maxDate", wd_get_date(this));
       });
   }
}

if (typeof wd_google_map_distance != 'function') {
    function wd_google_map_distance(lat1, lon1, lat2, lon2) {
        var p = 0.017453292519943295;    // Math.PI / 180
        var c = Math.cos;
        var a = 0.5 - c((lat2 - lat1) * p)/2 + 
                c(lat1 * p) * c(lat2 * p) * 
                (1 - c((lon2 - lon1) * p))/2;
    
        return 12742 * Math.asin(Math.sqrt(a)); // 2 * R; R = 6371 km
    }
}
