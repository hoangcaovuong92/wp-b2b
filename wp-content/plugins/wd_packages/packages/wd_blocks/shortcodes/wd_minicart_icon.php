<?php
if (!function_exists('wd_minicart_icon_function')) {
	function wd_minicart_icon_function($atts) {
		extract(shortcode_atts(array(
			'show_icon'		=> '1',
			'show_text'		=> '0',
			'dropdown_position' => 'left',
			'class' 		=> ''
		), $atts));
		
		if (!wd_is_woocommerce()) return;
		return apply_filters('wd_filter_minicart', array('show_icon' => $show_icon, 
														'show_text' => $show_text, 
														'dropdown_position' => $dropdown_position,
														'class' => $class ));
	}
}

if (!function_exists('wd_minicart_icon_vc_map')) {
	function wd_minicart_icon_vc_map() {
		if (!wd_is_woocommerce()) return;
		return array(
			'name' 				=> esc_html__("WD - Woo DropdownCart", 'wd_package'),
			'base' 				=> 'wd_minicart_icon',
			'description' 		=> esc_html__("Display icon dropdown cart (mini cart).", 'wd_package'),
			'category' 			=> esc_html__("WD - Nav User", 'wd_package'),
			'icon'        		=> 'icon-wpb-woocommerce',
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
					"type" 			=> "dropdown",
					"class" 		=> "",
					"heading" 		=> esc_html__("Dropdown Position", 'wd_package'),
					"admin_label" 	=> true,
					"param_name" 	=> "dropdown_position",
					"value" 		=> array(
						esc_html__( 'Left', 'wd_package' ) 	=> 'left',
						esc_html__( 'Right', 'wd_package' ) => 'right',
					),
					"description" 		=> "",
				),
				array(
					'type' 			=> 'textfield',
					'class' 		=> '',
					'heading' 		=> esc_html__("Extra class name", 'wd_package'),
					'description' 	=> esc_html__("Style particular content element differently - add a class name and refer to it in custom CSS.", 'wd_package'),
					'admin_label' 	=> true,
					'param_name' 	=> 'class',
					'value' 		=> ''
				)
			)
		);
	}
}