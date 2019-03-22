<?php
if (!function_exists('wd_product_search_function')) {
	function wd_product_search_function($atts) {
		extract(shortcode_atts(array(
			'class'				=> ''
		), $atts));
		return apply_filters('get_product_search_form', $class);
	}
}

if (!function_exists('wd_product_search_vc_map')) {
	function wd_product_search_vc_map() {
		if (!wd_is_woocommerce()) return;
		return array(
			"name"				=> esc_html__("WD - Product Search",'wd_package'),
			"base"				=> 'wd_product_search',
			'description' 		=> esc_html__("Display product search form...", 'wd_package'),
			"category"			=> esc_html__("WD - Product",'wd_package'),
			'icon'        		=> 'icon-wpb-woocommerce',
			"params"=>array(	
				array(
					'type' 			=> 'textfield',
					'class' 		=> '',
					'heading' 		=> esc_html__("Extra class name", 'wd_package'),
					'description'	=> esc_html__("Style particular content element differently - add a class name and refer to it in custom CSS.", 'wd_package'),
					'admin_label' 	=> true,
					'param_name' 	=> 'class',
					'value' 		=> ''
				)
			)
		);
	}
}