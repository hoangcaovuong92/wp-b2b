<?php
	// Banner Image
	vc_map(array(
		'name' 				=> esc_html__("WD - Instagram", 'wd_package'),
		'base' 				=> 'wd_instagram',
		'description' 		=> esc_html__("Display instagram photos...", 'wd_package'),
		'category' 			=> esc_html__("WD - Content", 'wd_package'),
		'icon'        		=> 'vc_icon-vc-gitem-image',
		"params" 			=> array(
			/*-----------------------------------------------------------------------------------
				Title & DESC
			-------------------------------------------------------------------------------------*/
			array(
				"type" 			=> "textfield",
				"class" 		=> "",
				"heading" 		=> esc_html__("Title Instagram", 'wd_package'),
				"param_name" 	=> "insta_title",
				"description" 	=> '',
			),
			array(
				"type" 			=> "textarea",
				"class" 		=> "",
				"heading" 		=> esc_html__("Description", 'wd_package'),
				"param_name" 	=> "insta_desc",
				"description" 	=> '',
			),
			array(
				"type" 			=> "dropdown",
				"class"			=> "",
				"heading" 		=> esc_html__("Show Follow Me", 'wd_package'),
				"param_name" 	=> "insta_follow",
				"value" 		=> wd_vc_get_list_tvgiao_boolean(),
				'edit_field_class' => 'vc_col-sm-6',
			),
			array(
				"type" 			=> "textfield",
				"class" 		=> "",
				"heading" 		=> esc_html__("Follow Text", 'wd_package'),
				"param_name" 	=> "insta_follow_text",
				'value'			=> 'Follow Me',
				"description" 	=> '',
				'edit_field_class' => 'vc_col-sm-6',
				'dependency'  	=> array(
					'element' => 'insta_follow',
					'value'   => array( '1' ),
				)
			),
			array(
				"type" 			=> "dropdown",
				"class" 		=> "",
				"heading" 		=> esc_html__("Title & Desc Position", 'wd_package'),
				"admin_label" 	=> true,
				"param_name" 	=> "insta_style",
				"value" 		=> array(
					esc_html__('Center + Inner Content', 'wd_package') 	=> 'style-insta-1',
					esc_html__('Before Content', 'wd_package') 		 	=> 'style-insta-2'
				),
				"description" 	=> "",
			),
			array(
				"type" 			=> "dropdown",
				"class" 		=> "",
				"heading" 		=> esc_html__("Hover Style", 'wd_package'),
				"admin_label" 	=> true,
				"param_name" 	=> "insta_hover_style",
				"value" 		=> array(
					esc_html__('None', 'wd_package') 	=> '',
					esc_html__('Zoom', 'wd_package') 	=> 'wd-insta-hover-style-1',
					esc_html__('Overlay', 'wd_package') => 'wd-insta-hover-style-2'
				),
				"description" 	=> "",
			),
			/*-----------------------------------------------------------------------------------
				SETTING
			-------------------------------------------------------------------------------------*/
			array(
				'type' 			=> 'dropdown',
				'heading' 		=> esc_html__( 'Columns', 'wd_package' ),
				'param_name' 	=> 'insta_columns',
				'admin_label' 	=> true,
				'value' 		=> wd_vc_get_list_tvgiao_columns(),
				"std"			=> 4,
				'description' 	=> '',
				"group"			=> esc_html__('Instagram Setting', 'wd_package'),
				'edit_field_class' => 'vc_col-sm-6',
			),
			array(
				'type' 				=> 'dropdown',
				'heading' 			=> esc_html__( 'Columns On Tablet', 'wd_package' ),
				'param_name' 		=> 'columns_tablet',
				'admin_label' 		=> true,
				'value' 			=> wd_vc_get_list_columns_tablet(),
				'std'				=> 2,
				'description' 		=> esc_html__( '', 'wd_package' ),
				"group"				=> esc_html__('Responsive', 'wd_package'),
			),
			array(
				'type' 				=> 'dropdown',
				'heading' 			=> esc_html__( 'Columns On Mobile', 'wd_package' ),
				'param_name' 		=> 'columns_mobile',
				'admin_label' 		=> true,
				'value' 			=> wd_vc_get_list_columns_mobile(),
				'std'				=> 1,
				'description' 		=> esc_html__( '', 'wd_package' ),
				"group"				=> esc_html__('Responsive', 'wd_package'),
			),
			array(
				"type" 			=> "textfield",
				"class" 		=> "",
				"heading" 		=> esc_html__("Number of photos", 'wd_package'),
				"param_name" 	=> "insta_number",
				"value"			=> 4,
				"description" 	=> esc_html__('Max 12 photos', 'wd_package'),
				"group"			=> esc_html__('Instagram Setting', 'wd_package'),
				'edit_field_class' => 'vc_col-sm-6',
			),
			array(
				"type" 			=> "dropdown",
				"class" 		=> "",
				"heading" 		=> esc_html__("Padding", 'wd_package'),
				"admin_label" 	=> true,
				"param_name" 	=> "insta_padding",
				"value" 		=> wd_vc_get_list_columns_padding(),
				"description" 	=> esc_html__('Padding between images.', 'wd_package'),
				"std"			=> 'normal',
				"group"			=> esc_html__('Instagram Setting', 'wd_package'),
				'edit_field_class' => 'vc_col-sm-6',
			),
			array(
				"type" 			=> "dropdown",
				"class" 		=> "",
				"heading" 		=> esc_html__("Photo Size", 'wd_package'),
				"admin_label" 	=> true,
				"param_name" 	=> "insta_size",
				"value" 		=> wd_vc_get_list_instagram_image_size(),
				"description" 	=> "",
				"std"			=> 'low_resolution',
				"group"			=> esc_html__('Instagram Setting', 'wd_package'),
				'edit_field_class' => 'vc_col-sm-6',
			),
			array(
				"type" 			=> "dropdown",
				"class" 		=> "",
				"heading" 		=> esc_html__("Sort By", 'wd_package'),
				"admin_label" 	=> true,
				"param_name" 	=> "insta_sortby",
				"value" 		=> wd_vc_get_list_instagram_sort_by(),
				"description" 	=> "",
				"std"			=> 'none',
				"group"			=> esc_html__('Instagram Setting', 'wd_package'),
				'edit_field_class' => 'vc_col-sm-6',
			),
			array(
				"type" 			=> "dropdown",
				"class"			=> "",
				"heading" 		=> esc_html__("Action When Click Item", 'wd_package'),
				"param_name" 	=> "insta_action_click_item",
				"value" 		=> array(
						esc_html__("Lightbox", 'wd_package') 		=> 'lightbox',
						esc_html__("Instagram Link", 'wd_package') 	=> 'link',
					),
				'std'			=> 'lightbox',
				"group"			=> esc_html__('Instagram Setting', 'wd_package'),
				'edit_field_class' => 'vc_col-sm-6',
			),
			array(
				"type" 			=> "dropdown",
				"class"			=> "",
				"heading" 		=> esc_html__("Open links in", 'wd_package'),
				"param_name" 	=> "insta_open_win",
				"value" 		=> wd_vc_get_list_link_target(),
				"group"			=> esc_html__('Instagram Setting', 'wd_package'),
				'edit_field_class' => 'vc_col-sm-6',
				'dependency'  	=> array('element' => "insta_action_click_item", 'value' => array('link')),
			),
			/*-----------------------------------------------------------------------------------
				SLIDER SETTING
			-------------------------------------------------------------------------------------*/
			array(
				"type" 			=> "dropdown",
				"class" 		=> "",
				"heading" 		=> esc_html__("Is Slider", 'wd_package'),
				"admin_label" 	=> true,
				"param_name" 	=> "is_slider",
				"value" 		=> wd_vc_get_list_tvgiao_boolean(),
				'std'			=> '0',
				"description" 	=> esc_html__('Set "Number of photos" larger than ("Columns" * "Number Rows") to be able to activate Slider mode.', 'wd_package'),
				"group"			=> esc_html__('Slider Setting', 'wd_package'),
			),
			array(
				"type" 			=> "dropdown",
				"class" 		=> "",
				"heading" 		=> esc_html__("Show Load More", 'wd_package'),
				"admin_label" 	=> true,
				"param_name" 	=> "show_loadmore",
				"value" 		=> wd_vc_get_list_tvgiao_boolean(),
				'std'			=> '0',
				"description" 	=> "",
				"group"			=> esc_html__('Slider Setting', 'wd_package'),
				'edit_field_class' => 'vc_col-sm-6',
				'dependency'  	=> array('element' => "is_slider", 'value' => array('0')),
			),
			array(
				"type" 			=> "dropdown",
				"class" 		=> "",
				"heading" 		=> esc_html__("Show Nav", 'wd_package'),
				"admin_label" 	=> true,
				"param_name" 	=> "show_nav",
				"value" 		=> wd_vc_get_list_tvgiao_boolean(),
				"description" 	=> "",
				"group"			=> esc_html__('Slider Setting', 'wd_package'),
				'edit_field_class' => 'vc_col-sm-4',
				'dependency'  	=> array('element' => "is_slider", 'value' => array('1')),
			),
			array(
				"type" 			=> "dropdown",
				"class" 		=> "",
				"heading" 		=> esc_html__("Auto Play", 'wd_package'),
				"admin_label" 	=> true,
				"param_name" 	=> "auto_play",
				"value" 		=> wd_vc_get_list_tvgiao_boolean(),
				"description" 	=> "",
				"group"			=> esc_html__('Slider Setting', 'wd_package'),
				'edit_field_class' => 'vc_col-sm-4',
				'dependency'  	=> array('element' => "is_slider", 'value' => array('1')),
			),
			array(
				'type' 			=> 'textfield',
				'class' 		=> '',
				'heading' 		=> esc_html__("Number Rows", 'wd_package'),
				'description' 	=> esc_html__("", 'wd_package'),
				'admin_label' 	=> true,
				'param_name' 	=> 'per_slide',
				'value' 		=> '1',
				"group"			=> esc_html__('Slider Setting', 'wd_package'),
				'edit_field_class' => 'vc_col-sm-4',
				'dependency'  	=> array('element' => "is_slider", 'value' => array('1')),
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