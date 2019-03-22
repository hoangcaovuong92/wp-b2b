<?php
if( class_exists('WD_Megamenu') ){
	$integrate_specific_menu = wd_vc_get_list_category('nav_menu', false);
	$menu_theme_location     = wd_vc_get_list_menu_registed();
	vc_map(array(
		'name' 				=> esc_html__("WD - Megamenu", 'wd_package'),
		'base' 				=> 'wd_megamenu',
		'description' 		=> esc_html__("Display megamenu by special menu or menu id...", 'wd_package'),
		'category' 			=> esc_html__("WD - Content", 'wd_package'),
		'icon'        		=> 'vc_icon-vc-gitem-post-categories',
		'params' => array()
	));
}