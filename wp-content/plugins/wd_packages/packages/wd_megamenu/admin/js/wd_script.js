//****************************************************************//
/*                          BACKEND END MEGAMENU JS               */
//****************************************************************//
jQuery(document).ready(function($) {
    "use strict";
//Media Screen Select
    wd_menu_required_script();
});

//****************************************************************//
/*                          FUNCTIONS                             */
//****************************************************************//

//Media Screen Select
if (typeof wd_menu_required_script != 'function') { 
    function wd_menu_required_script(){
        //Requied Ennable/Disabled Megamenu
        jQuery('.edit-menu-item-custom-megamenu').each(function(){
            jQuery(this).on('change', function(){
                var current_value   = jQuery(this).val();
                var menu_id         = jQuery(this).data('menu_id');
                var required_item   = jQuery('.wd-megamenu-content-wrap-'+menu_id);

                if (current_value == '1') {
                    required_item.show();
                }else{
                    required_item.hide();
                }
            }).trigger('change');
        });

        //Requied submenu custom content display setting
        jQuery('.edit-menu-item-custom-submenu-custom-content-effect').each(function(){
            jQuery(this).on('change', function(){
                var current_value   = jQuery(this).val();
                var menu_id         = jQuery(this).data('menu_id');
                var required_item   = jQuery('.wrap-menu-item-custom-columns-'+menu_id);

                if (current_value == 'normal') {
                    required_item.show();
                }else{
                    required_item.hide();
                }
            }).trigger('change');
        });

        //Requied Submenu Background Setting
        jQuery('.edit-menu-item-custom-bg-submenu').each(function(){
            jQuery(this).on('change', function(){
                var current_value   = jQuery(this).val();
                var menu_id         = jQuery(this).data('menu_id');
                var required_item_1 = jQuery('.wrap-menu-item-custom-submenu-bg-color-'+menu_id);
                var required_item_2 = jQuery('.wrap-menu-item-custom-submenu-bg-image-'+menu_id);

                if (current_value == '0') {
                    required_item_1.hide();
                    required_item_2.hide();
                }else if(current_value == 'bg_image'){
                    required_item_1.hide();
                    required_item_2.show();
                }else if(current_value == 'bg_color'){
                    required_item_1.show();
                    required_item_2.hide();
                }
            }).trigger('change');
        });
       

        //Requied Submenu Content Setting
        jQuery('.edit-menu-item-custom-submenu-content-source').each(function(){
            jQuery(this).on('change', function(){
                var current_value   = jQuery(this).val();
                var menu_id         = jQuery(this).data('menu_id');
                var required_item_1 = jQuery('.wrap-menu-item-custom-submenu-content-widget-'+menu_id);
                var required_item_2 = jQuery('.wrap-menu-item-custom-submenu-content-shortocde-template-'+menu_id);
                var required_item_3 = jQuery('.wrap-menu-item-custom-submenu-content-custom-shortcode-'+menu_id);
                if (current_value == '0') {
                    required_item_1.hide();
                    required_item_2.hide();
                    required_item_3.hide();
                }else if (current_value == 'widget-area') {
                    required_item_1.show();
                    required_item_2.hide();
                    required_item_3.hide();
                }else if(current_value == 'megamenu-template'){
                    required_item_1.hide();
                    required_item_2.show();
                    required_item_3.hide();
                }else if(current_value == 'custom-shortcode'){
                    required_item_1.hide();
                    required_item_2.hide();
                    required_item_3.show();
                }
            }).trigger('change');
        });
    }
}