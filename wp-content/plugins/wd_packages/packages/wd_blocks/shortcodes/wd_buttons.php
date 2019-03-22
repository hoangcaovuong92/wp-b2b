<?php
if (!function_exists('wd_buttons_function')) {
	function wd_buttons_function($atts) {
		extract(shortcode_atts(array(
			'button_style'		=> 'style-1',
			'link_type'			=> 'category_link',
			'url'				=> '#',
			'id_category' 		=> '-1',
			'bg_color'			=> '',
			'text_color'		=> '',
			'button_text' 		=> 'View Category',
			'button_class' 		=> '',
			'class' 			=> ''
		), $atts));
		$color_style 	= ($bg_color != '' || $text_color != '') ? 'style="' : '';
		$color_style 	.= $bg_color != '' ? 'background-color: '.$bg_color.';' : '';
		$color_style 	.= $text_color != '' ? 'color: '.$text_color.';' : '';
		$color_style 	.= ($bg_color != '' || $text_color != '') ? '"' : '';

		if ($link_type == 'category_link' && wd_is_woocommerce()) {
			if ($id_category != '') {
				$link_url = ($id_category == -1 || !term_exists( $id_category, 'product_cat' )) ? get_permalink( wc_get_page_id( 'shop' ) ) : get_term_link( get_term_by( 'id', $id_category, 'product_cat' ), 'product_cat' );
			}else{
				$link_url = '#';
			}
		}else{
			$link_url = $url;
		}
		$button_style_class 	= 'wd-banner-image-button-'.$button_style;
		$title_image			= get_bloginfo('name');
		ob_start(); ?>
		<div class="wd-shortcode-buttons <?php echo esc_attr($class); ?>">
			<a class="<?php echo esc_attr($button_style_class); ?> <?php echo esc_attr($button_class); ?>" href="<?php echo esc_url($link_url); ?>" title="<?php echo esc_attr($title_image); ?>" <?php echo $color_style; ?>><?php echo esc_attr($button_text); ?></a>
		</div>
		<?php
		$output = ob_get_clean();
		wp_reset_postdata();
		return $output;
	}
}

if (!function_exists('wd_buttons_vc_map')) {
	function wd_buttons_vc_map() {
		return array(
			'name' 				=> esc_html__("WD - Button", 'wd_package'),
			'base' 				=> 'wd_buttons',
			'description' 		=> esc_html__("", 'wd_package'),
			'category' 			=> esc_html__("WD - Content", 'wd_package'),
			'icon'        		=> 'icon-wpb-ui-button',
			"params" => array(
				
				/*-----------------------------------------------------------------------------------
					BUTTON SETTING
				-------------------------------------------------------------------------------------*/
				array(
					'type' 			=> 'dropdown',
					'heading' 		=> esc_html__( 'Button Style', 'wd_package' ),
					'param_name' 	=> 'button_style',
					'admin_label' 	=> true,
					'value' 		=> wd_vc_get_list_style_class(10),
					'description' 	=> '',
				),
				array(
					'type' 			=> 'dropdown',
					'heading' 		=> esc_html__( 'Link type', 'wd_package' ),
					'param_name' 	=> 'link_type',
					'admin_label' 	=> true,
					'value' 		=> array(
						esc_html__( 'Category Link', 'wd_package' )	=> 'category_link',
						esc_html__( 'Another Url', 'wd_package' )	=> 'url',
					),
					'description' 	=> '',
					'edit_field_class' => 'vc_col-sm-6',
				),
				array(
					'type' 			=> 'dropdown',
					'heading' 		=> esc_html__( 'Select Category', 'wd_package' ),
					'param_name' 	=> 'id_category',
					'admin_label' 	=> true,
					'value' 		=> wd_vc_get_list_category(),
					'description' 	=> esc_html__('Select "All Category" to use the link to the shop page.', 'wd_package'),
					'edit_field_class' => 'vc_col-sm-6',
					'dependency'  	=> array('element' => "link_type", 'value' => array('category_link'))
				),
				array(
					"type" 			=> "textfield",
					"class" 		=> "",
					"heading" 		=> esc_html__("URL", 'wd_package'),
					"param_name" 	=> "url",
					"description" 	=> esc_html__('', 'wd_package'),
					'value' 		=> esc_html__('#', 'wd_package'),
					'edit_field_class' => 'vc_col-sm-6',
					'dependency'  	=> array('element' => "link_type", 'value' => array('url'))
				),
				array(
					  "type" 			=> "colorpicker",
					  "class" 		=> "",
					  "heading" 		=> __( "Background Color", 'wd_package' ),
					  "param_name"	=> "bg_color",
					  "value" 		=> '', 
					  "description" 	=> __( "Choose button's background color...", 'wd_package' ),
					  'edit_field_class' => 'vc_col-sm-6',
				),
				array(
					  "type" 			=> "colorpicker",
					  "class" 		=> "",
					  "heading" 		=> __( "Text Color", 'wd_package' ),
					  "param_name"	=> "text_color",
					  "value" 		=> '', 
					  "description" 	=> __( "Choose button's text color...", 'wd_package' ),
					  'edit_field_class' => 'vc_col-sm-6',
				),
				array(
					"type" 			=> "textfield",
					"class" 		=> "",
					"heading" 		=> esc_html__("Button text", 'wd_package'),
					"param_name" 	=> "button_text",
					"description" 	=> esc_html__('', 'wd_package'),
					'value' 		=> esc_html__('View Category', 'wd_package'),
				),
				array(
					"type" 			=> "textfield",
					"class" 		=> "",
					"heading" 		=> esc_html__("Button class", 'wd_package'),
					"param_name" 	=> "button_class",
					"description" 	=> '',
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