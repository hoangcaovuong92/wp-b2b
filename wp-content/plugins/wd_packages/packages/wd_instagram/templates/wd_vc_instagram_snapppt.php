<?php
	// Banner Image
	vc_map(array(
		'name' 				=> esc_html__("WD - Instagram Snapppt", 'wd_package'),
		'base' 				=> 'wd_instagram_snapppt',
		'description' 		=> esc_html__("WD Instagram Snapppt", 'wd_package'),
		'category' 			=> esc_html__("WD - Content", 'wd_package'),
		'icon'        		=> 'vc_icon-vc-gitem-image',
		"params" 			=> array(
			array(
				'type' 			=> 'textarea',
				'class' 		=> '',
				'heading' 		=> esc_html__("Instagram Snapppt Script", 'wd_package'),
				'description'	=> esc_html__("", 'wd_package'),
				'admin_label' 	=> true,
				'param_name' 	=> 'instagram_snapppt',
				'value' 		=> '',
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
	));
?>