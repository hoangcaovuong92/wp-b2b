<?php
if (!function_exists('wd_blog_search_function')) {
	function wd_blog_search_function($atts) {
		extract(shortcode_atts(array(
			'style' 		=> 'normal',
			'class'			=> ''
		), $atts));
	
		ob_start();
			echo apply_filters('wd_filter_search_form', array('style' => $style, 'class' => $class));
		$content = ob_get_clean();
		wp_reset_postdata();
		return $content;
	}
}

if (!function_exists('wd_blog_search_vc_map')) {
	function wd_blog_search_vc_map() {
		return array(
			'name' 				=> esc_html__("WD - Blog Search", 'wd_package'),
			'base' 				=> 'wd_blog_search',
			'description' 		=> esc_html__("Display blog search form...", 'wd_package'),
			'category' 			=> esc_html__("WD - Blog", 'wd_package'),
			'icon'        		=> 'icon-wpb-toggle-small-expand',
			'params' => array(
				array(
					"type" 			=> "dropdown",
					"class" 		=> "",
					"heading" 		=> esc_html__("Style", 'wd_package'),
					"admin_label" 	=> true,
					"param_name" 	=> "style",
					"value" => array(
							esc_html__('Normal', 'wd_package') 	=> 'normal',
							esc_html__('Popup', 'wd_package') 	=> 'popup',
						),
					"description" 	=> "",
				),
				array(
					'type' 			=> 'textfield',
					'class' 		=> '',
					'heading' 		=> esc_html__("Extra class name", 'wd_package'),
					'description'	=> esc_html__("Style particular content element differently - add a class name and refer to it in custom CSS.", 'wd_package'),
					'admin_label' 	=> true,
					'param_name' 	=> 'class',
					'value' 		=> '',
				)
			)
		);
	}
}