<?php
if (!function_exists('wd_title_function')) {
	function wd_title_function($atts) {
		extract(shortcode_atts(array(
			'title'				=> '',
			'title_highlight'	=> '',
			'description'		=> '',
			'heading_type'		=> 'wd-title-style-1',
			'title_color'		=> '',
			'highlight_color'	=> '',
			'desc_color'		=> '',
			'heading_element'	=> 'h2',
			'text_align'		=> '',
			'display_button'	=> '0',
			'button_text'		=> 'View All',
			'button_url'		=> '#',
			'class' 			=> ''
		), $atts));
		$title_color 	= $title_color != '' ? 'style="color: '.$title_color.';"' : '';
		$highlight_color 	= $highlight_color != '' ? 'style="color: '.$highlight_color.';"' : '';
		$desc_color 	= $desc_color != '' ? 'style="color: '.$desc_color.';"' : '';
		ob_start(); ?>
			<?php if($title != "" || $description != "" || $display_button) : ?>
				<div class="wd-title <?php echo esc_attr($heading_type); ?> <?php echo esc_attr($class); ?>">
					<?php if ($title != ''): ?>
						<?php $title = ($title_highlight != '') ? str_replace($title_highlight, '<span class="wd-title-highlight" '.$highlight_color.'>'.$title_highlight.'</span>', $title) : esc_html($title); ?>
						<<?php echo esc_html($heading_element); ?> class="wd-title-heading <?php echo esc_html($text_align); ?>" <?php echo $title_color; ?>><?php echo $title; ?></<?php echo esc_html($heading_element); ?>>		
					<?php endif ?>		
					<?php if($description != "" || $display_button) : ?>
						<div class="wd-title-description <?php echo esc_attr($text_align); ?>" <?php echo $desc_color; ?>>
							<?php if ($description != ''): ?>
								<?php echo $description; ?>
							<?php endif ?>
							<?php if($description != "" && $display_button) : ?>
								<?php _e(' | ','wd_package') ?>
							<?php endif; ?>
							<?php if($display_button) : ?>
								<a target="_blank" href="<?php echo esc_url($button_url);?>"><?php echo esc_html($button_text); ?></a>
							<?php endif; ?>
						</div>	
					<?php endif ?>	
				</div> 
			<?php endif ?>	
		<?php
		$output = ob_get_clean();
		wp_reset_postdata();
		return $output;
	}
}

if (!function_exists('wd_title_vc_map')) {
	function wd_title_vc_map() {
		return array(
			'name' 				=> esc_html__("WD - Title/Heading", 'wd_package'),
			'base' 				=> 'wd_title',
			'description' 		=> esc_html__("Custom title for everywhere", 'wd_package'),
			'category' 			=> esc_html__("WD - Content", 'wd_package'),
			'icon'        		=> 'icon-wpb-ui-custom_heading',
			"params" => array(
				/*-----------------------------------------------------------------------------------
					Title & DESC
				-------------------------------------------------------------------------------------*/
				array(
					"type" 			=> "textfield",
					"class" 		=> "",
					"heading" 		=> esc_html__("Title", 'wd_package'),
					"param_name" 	=> "title",
					"description" 	=> '',
				),
				array(
					"type" 			=> "textfield",
					"class" 		=> "",
					"heading" 		=> esc_html__("Title HighLight", 'wd_package'),
					"param_name" 	=> "title_highlight",
					"description" 	=> esc_html__("Enter the keyword of the title, where you want to highlight.", 'wd_package'),
					'dependency'  	=> array('element' => "title", 'not_empty' => true),
				),
				array(
					"type" 			=> "textarea",
					"class" 		=> "",
					"heading" 		=> esc_html__("Description", 'wd_package'),
					"param_name" 	=> "description",
					"description" 	=> '',
				),
				/*-----------------------------------------------------------------------------------
					SETTING
				-------------------------------------------------------------------------------------*/
				array(
					'type' 				=> 'dropdown',
					'heading' 			=> esc_html__( 'Heading Style', 'wd_package' ),
					'param_name' 		=> 'heading_type',
					'admin_label' 		=> true,
					'value' 			=> wd_vc_get_list_style_class(1, 'wd-title-style-'),
					'description' 		=> '',
				),
				array(
					  "type" 			=> "colorpicker",
					  "class" 			=> "",
					  "heading" 		=> __( "Title Color", 'wd_package' ),
					  "param_name"		=> "title_color",
					  "value" 			=> '', 
					  "description" 	=> __( "Choose text color...", 'wd_package' ),
					  'edit_field_class' => 'vc_col-sm-6',
				),
				array(
					"type" 			=> "colorpicker",
					"class" 			=> "",
					"heading" 		=> __( "Hightlight Color", 'wd_package' ),
					"param_name"		=> "highlight_color",
					"value" 			=> '', 
					"description" 	=> __( "Choose text highlight color...", 'wd_package' ),
					'edit_field_class' => 'vc_col-sm-6',
					'dependency'  	=> array('element' => "title_highlight", 'not_empty' => true),
			  ),
				array(
					  "type" 			=> "colorpicker",
					  "class" 			=> "",
					  "heading" 		=> __( "Description Color", 'wd_package' ),
					  "param_name"		=> "desc_color",
					  "value" 			=> '', 
					  "description" 	=> __( "Choose text color...", 'wd_package' ),
					  'edit_field_class' => 'vc_col-sm-6',
				),
				array(
					'type' 			=> 'dropdown',
					'heading' 		=> esc_html__( 'Heading Element', 'wd_package' ),
					'param_name' 	=> 'heading_element',
					'admin_label' 	=> true,
					'value' 		=> wd_vc_get_list_heading_tag(),
					'edit_field_class' => 'vc_col-sm-6',
				),
				array(
					'type' 			=> 'dropdown',
					'heading' 		=> esc_html__( 'Text Align', 'wd_package' ),
					'param_name' 	=> 'text_align',
					'admin_label' 	=> true,
					'value' 		=> wd_vc_get_list_text_align_bootstrap(),
					'description' 	=> '',
					'edit_field_class' => 'vc_col-sm-6',
				),
				array(
					'type'        	=> 'dropdown',
					'heading'     	=> __( 'Display Button', 'wd_package' ),
					'description' 	=> __( 'The button with custom link will display after the description', 'wd_package' ),
					'param_name'  	=> 'display_button',
					'value' 		=> wd_vc_get_list_tvgiao_boolean(),
					'std'			=> '0',
					'save_always' 	=> true,
				),
				array(
					"type" 			=> "textfield",
					"class" 		=> "",
					"heading" 		=> __( "Button Text", 'wd_package' ),
					"param_name" 	=> "button_text",
					"value" 		=> 'View All', 
					"description" 	=> __( "", 'wd_package' ),
					'edit_field_class' => 'vc_col-sm-6',
					'dependency'  	=> array('element' => "display_button", 'value' => array('1')),
				),
				array(
					"type" 			=> "textfield",
					"class" 		=> "",
					"heading" 		=> __( "Button URL", 'wd_package' ),
					"param_name" 	=> "button_url",
					"value" 		=> '#', 
					"description" 	=> __( "", 'wd_package' ),
					'edit_field_class' => 'vc_col-sm-6',
					'dependency'  	=> array('element' => "display_button", 'value' => array('1'))
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