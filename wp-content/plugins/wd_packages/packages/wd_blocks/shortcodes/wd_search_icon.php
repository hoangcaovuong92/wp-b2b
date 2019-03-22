<?php
if ( ! function_exists( 'wd_search_icon_function' ) ) {
	function wd_search_icon_function($atts){
		extract(shortcode_atts(array(
			'show_icon'		=> '1',
			'show_text'		=> '0',
			'show_avatar'	=> '1',
			'class' 		=> '',
		), $atts));

		if (!wd_is_woocommerce()) return;
		return apply_filters('wd_filter_search_icon', array('show_icon' => $show_icon, 
															'show_text' => $show_text, 
															'class' => $class ));
	}
}

if (!function_exists('wd_search_icon_vc_map')) {
	function wd_search_icon_vc_map() {
		return array(
			'name' 				=> esc_html__("WD - Search Icon", 'wd_package'),
			'base' 				=> 'wd_search_icon',
			'description' 		=> esc_html__("Display search icon", 'wd_package'),
			'category' 			=> esc_html__("WD - Nav User", 'wd_package'),
			'icon'        		=> 'icon-wpb-information-white',
			'params' => array(
				array(
					'type' 			=> 'dropdown',
					'heading' 		=> esc_html__( 'Show Icon', 'wd_package' ),
					'param_name' 	=> 'show_icon',
					'admin_label' 	=> true,
					'value' 		=> wd_vc_get_list_tvgiao_boolean(),
					'std'			=> '0',
					'description' 	=> esc_html__('Display icon in front of text', 'wd_package')
				),
				array(
					'type' 			=> 'dropdown',
					'heading' 		=> esc_html__( 'Show Text', 'wd_package' ),
					'param_name' 	=> 'show_text',
					'admin_label' 	=> true,
					'value' 		=> wd_vc_get_list_tvgiao_boolean(),
						'std'			=> '1',
					'description' 	=> esc_html__('Display title', 'wd_package')
				),
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