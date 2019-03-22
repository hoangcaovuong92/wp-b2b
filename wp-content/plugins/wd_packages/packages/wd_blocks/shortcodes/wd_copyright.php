<?php
if (!function_exists('wd_copyright_function')) {
	function wd_copyright_function($atts) {
		extract(shortcode_atts(array(
			'class' 				=> '',
		), $atts));

		ob_start();
			echo apply_filters('wd_filter_copyright', array('custom_class' => $class));
		$content = ob_get_clean();
		
		return $content;
	}
}

if (!function_exists('wd_copyright_vc_map')) {
	function wd_copyright_vc_map() {
		return array(
			'name' 				=> esc_html__("WD - Copyright", 'wd_package'),
			'base' 				=> 'wd_copyright',
			'description' 		=> esc_html__("Display site copyright text on the header", 'wd_package'),
			'category' 			=> esc_html__("WD - Content", 'wd_package'),
			'icon'        		=> 'vc_icon-vc-gitem-post-categories',
			'params' => array(
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