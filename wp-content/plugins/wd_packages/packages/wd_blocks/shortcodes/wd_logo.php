<?php
if (!function_exists('wd_logo_function')) {
	function wd_logo_function($atts) {
		extract(shortcode_atts(array(
			'logo_id'		=> '',
			'show_logo_title'		=> '0',
			'width'					=> '',
			'class' 				=> '',
		), $atts));
		
		$logo_url 			= '';
		if($logo_id != ""){
			$image	  		= wp_get_attachment_image_src($logo_id, 'full');
			$logo_url 		= $image[0];
		}
	
		ob_start();
			echo apply_filters('wd_filter_logo', array('logo_url' => $logo_url, 'show_logo_title' => $show_logo_title, 'width' => $width, 'custom_class' => $class));
		$content = ob_get_clean();
		return $content;
	}
}

if (!function_exists('wd_logo_vc_map')) {
	function wd_logo_vc_map() {
		return array(
			'name' 				=> esc_html__("WD - Logo", 'wd_package'),
			'base' 				=> 'wd_logo',
			'description' 		=> esc_html__("Display site info (title, tagline, logo...) on the header", 'wd_package'),
			'category' 			=> esc_html__("WD - Content", 'wd_package'),
			'icon'        		=> 'vc_icon-vc-gitem-post-categories',
			'params' => array(
				array(
					"type" 			=> "attach_image",
					"class" 		=> "",
					"heading" 		=> esc_html__("Logo Image", 'wd_package'),
					"description" 	=> esc_html__("If you do not want the default logo, you add another logo here", 'wd_package'),
					"param_name" 	=> "logo_id",
					"value" 		=> '',
				),
				array(
					"type" 			=> "dropdown",
					"class" 		=> "",
					"heading" 		=> esc_html__("Show Site Title", 'wd_package'),
					"admin_label" 	=> true,
					"param_name" 	=> "show_logo_title",
					'value' 		=> wd_vc_get_list_tvgiao_boolean(),
					'std'			=> 0,
					"description" 	=> "",
					'edit_field_class' => 'vc_col-sm-6',
				),
				array(
					'type' 			=> 'textfield',
					'class' 		=> '',
					'heading' 		=> esc_html__("Logo width", 'wd_package'),
					'description'	=> esc_html__("Exam: 100px, 100%...", 'wd_package'),
					'admin_label' 	=> true,
					'param_name' 	=> 'width',
					'value' 		=> '',
					'edit_field_class' => 'vc_col-sm-6',
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