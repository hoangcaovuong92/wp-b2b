<?php
if (!function_exists('wd_title_function')) {
	function wd_title_function($atts) {
		extract(shortcode_atts(array(
			'title'				=> '',
			'title_highlight'	=> '',
			'description'		=> '',
			'title_color'		=> '',
			'title_size'		=> '',
			'highlight_color'	=> '',
			'highlight_size'	=> '',
			'desc_color'		=> '',
			'desc_size'			=> '',
			'heading_element'	=> 'h2',
			'text_align'		=> '',
			'display_button'	=> '0',
			'button_text'		=> 'View All',
			'button_url'		=> '#',
			'fullwidth_mode' 	=> false,
			'class' 			=> ''
		), $atts));
		$title_class = '';
		$highlight_class = '';
		$desc_class = '';

		if ($title_color || $title_size) {
			$title_class .= 'style="';
			$title_class .= $title_color != '' ? 'color: '.$title_color.';' : '';
			$title_class .= $title_size != '' ? 'font-size: '.$title_size.';' : '';
			$title_class .= '"';
		}

		if ($highlight_color || $highlight_size) {
			$highlight_class .= 'style="';
			$highlight_class .= $highlight_color != '' ? 'color: '.$highlight_color.';' : '';
			$highlight_class .= $highlight_size != '' ? 'font-size: '.$highlight_size.';' : '';
			$highlight_class .= '"';
		}

		if ($desc_color || $desc_size) {
			$desc_class .= 'style="';
			$desc_class .= $desc_color != '' ? 'color: '.$desc_color.';' : '';
			$desc_class .= $desc_size != '' ? 'font-size: '.$desc_size.';' : '';
			$desc_class .= '"';
		}

		//Fullwidth mode class (gutenberg)
		$class .= ($fullwidth_mode) ? ' alignfull' : '';

		ob_start(); ?>
		<?php if($title != "" || $description != "" || $display_button) : ?>
			<div class="wd-shortcode wd-shortcode-title wd-title <?php echo esc_attr($class); ?>">
				<?php if ($title != ''): ?>
					<?php $title = ($title_highlight != '') ? str_replace($title_highlight, '<span class="wd-title-highlight" '.$highlight_class.'>'.$title_highlight.'</span>', $title) : esc_html($title); ?>
					<<?php echo esc_html($heading_element); ?> class="wd-title-heading <?php echo esc_html($text_align); ?>" <?php echo $title_class; ?>><?php echo $title; ?></<?php echo esc_html($heading_element); ?>>		
				<?php endif ?>		
				<?php if($description != "" || $display_button) : ?>
					<div class="wd-title-description <?php echo esc_attr($text_align); ?>" <?php echo $desc_class; ?>>
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
					"type" 			=> "colorpicker",
					"class" 			=> "",
					"heading" 		=> __( "Title Color", 'wd_package' ),
					"param_name"		=> "title_color",
					"value" 			=> '', 
					"description" 	=> __( "Choose text color...", 'wd_package' ),
					'edit_field_class' => 'vc_col-sm-6',
					'dependency'  	=> array('element' => "title", 'not_empty' => true),
				),
				array(
					"type" 			=> "textfield",
					"class" 		=> "",
					"heading" 		=> __( "Title Font Size", 'wd_package' ),
					"param_name" 	=> "title_size",
					"value" 		=> '', 
					"description" 	=> __( "Ex: 14px", 'wd_package' ),
					'edit_field_class' => 'vc_col-sm-6',
					'dependency'  	=> array('element' => "title", 'not_empty' => true),
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
					"type" 			=> "textfield",
					"class" 		=> "",
					"heading" 		=> __( "Highlight Font Size", 'wd_package' ),
					"param_name" 	=> "highlight_size",
					"value" 		=> '', 
					"description" 	=> __( "Ex: 14px", 'wd_package' ),
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
					'dependency'  	=> array('element' => "description", 'not_empty' => true),
				),
				array(
					"type" 			=> "textfield",
					"class" 		=> "",
					"heading" 		=> __( "Description Font Size", 'wd_package' ),
					"param_name" 	=> "desc_size",
					"value" 		=> '', 
					"description" 	=> __( "Ex: 14px", 'wd_package' ),
					'edit_field_class' => 'vc_col-sm-6',
					'dependency'  	=> array('element' => "description", 'not_empty' => true),
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