<?php
if (!function_exists('wd_banner_image_function')) {
	function wd_banner_image_function($atts) {
		extract(shortcode_atts(array(
			'image'			=> '',
			'image_size'	=> 'full',
			'hover_style'	=> 'style-1',
			'button_text'	=> 'Shop Now',
			'link_url'		=> "#",
			'target'		=> '_blank',
			'button_position' => 'center',
			'top'			=> '',
			'right'			=> '',
			'bottom'		=> '',
			'left'			=> '',
			'class' 		=> ''
		), $atts));

		$button_style 		= '';
		if ($button_position === 'custom') {
			$button_style 	.= ($top != '') ? 'top:'. esc_attr($top) . ';' : '';
			$button_style 	.= ($bottom != '') ? 'bottom:'. esc_attr($bottom) . ';' : '';
			$button_style 	.= ($left != '') ? 'left:'. esc_attr($left) . ';' : '';
			$button_style 	.= ($right != '') ? 'right:'. esc_attr($top) . ';' : '';
		}
		ob_start(); ?>
			<div class="wd-shortcode-banner <?php echo esc_attr($class); ?>">	
				<div class="wd-banner-image wd-banner-hover--<?php echo esc_attr($hover_style); ?>">
					<a target="<?php echo esc_attr($target);?>" href="<?php echo esc_url($link_url)?>">
						<?php echo apply_filters('wd_filter_image_html', array('attachment' => $image, 'image_size' => $image_size)); ?>
					</a>
				</div>
				<?php if($button_text !== '' ):?>
					<div class="wd-banner-button wd-button-position--<?php echo esc_attr($button_position);?>"<?php echo $button_style ? ' style="'.$button_style.'"' : ''; ?>>
						<a target="<?php echo esc_attr($target);?>" class="button" href="<?php echo esc_url($link_url)?>">
							<?php echo esc_attr($button_text);?>
						</a>
					</div>
				<?php endif ?>
			</div>
		<?php
		$output = ob_get_clean();
		wp_reset_postdata();
		return $output;
	}
}

if (!function_exists('wd_banner_image_vc_map')) {
	function wd_banner_image_vc_map() {
		return array(
			'name' 				=> esc_html__("WD - Banner Image", 'wd_package'),
			'base' 				=> 'wd_banner_image',
			'description' 		=> esc_html__("Simple banner image...", 'wd_package'),
			'category' 			=> esc_html__("WD - Content", 'wd_package'),
			'icon'        		=> 'icon-wpb-single-image',
			"params" => array(
				array(
					"type" 			=> "attach_image",
					"class" 		=> "",
					"heading" 		=> esc_html__("Image", 'wd_package'),
					"param_name" 	=> "image",
					"value" 		=> "",
					"description" 	=> '',
				),
				array(
					'type' 			=> 'dropdown',
					'heading' 		=> esc_html__( 'Image Size', 'wd_package' ),
					'param_name' 	=> 'image_size',
					'admin_label' 	=> true,
					'value' 		=> wd_vc_get_list_image_size(),
					'edit_field_class' => 'vc_col-sm-6',
				),
				array(
					'type' 			=> 'dropdown',
					'heading' 		=> esc_html__( 'Hover Style', 'wd_package' ),
					'param_name' 	=> 'hover_style',
					'admin_label' 	=> true,
					'value' 		=> wd_vc_get_list_style_class(3),
					'description' 	=> '',
					'edit_field_class' => 'vc_col-sm-6',
				),
				array(
					"type" 			=> "textfield",
					"class" 		=> "",
					"heading" 		=> esc_html__("Button text", 'wd_package'),
					"param_name" 	=> "button_text",
					'value' 		=> 'Shop Now',
					"description" 	=> '',
					'edit_field_class' => 'vc_col-sm-6',
				),
				array(
					"type" 			=> "textfield",
					"class" 		=> "",
					"heading" 		=> esc_html__("Link Button", 'wd_package'),
					"param_name" 	=> "link_url",
					'value' 		=> '#',
					"description" 	=> '',
					'edit_field_class' => 'vc_col-sm-6',
					'dependency'  	=> array('element' => "button_text", 'not_empty' => true)
				),
				array(
					"type" 			=> "dropdown",
					"holder" 		=> "div",
					"class" 		=> "",
					"heading" 		=> esc_html__("Link Target", 'wd_package'),
					"param_name" 	=> "target",
					"value" 		=> wd_vc_get_list_link_target(),
					"std" 			=> '_blank',
					"description" 	=> "",
					'edit_field_class' => 'vc_col-sm-6',
					'dependency'  	=> array('element' => "button_text", 'not_empty' => true)
				),
				array(
					"type" 				=> "dropdown",
					"class" 			=> "",
					"heading" 			=> esc_html__("Button Position", 'wd_package'),
					"admin_label" 		=> true,
					"param_name" 		=> "button_position",
					"value" 			=> array(
						esc_html__("Center", 'wd_package') => 'center',
						esc_html__("Static", 'wd_package') => 'static',
						esc_html__("Custom", 'wd_package') => 'custom',
					),
					"description" 		=> "",
					'edit_field_class' 	=> 'vc_col-sm-6',
					'dependency'  		=> array('element' => "button_text", 'not_empty' => true)
				),
				array(
					"type" 			=> "textfield",
					"class" 		=> "",
					"heading" 		=> esc_html__("Top", 'wd_package'),
					"param_name" 	=> "top",
					"description" 	=> esc_html__("ex: 5%", 'wd_package'),
					'edit_field_class' => 'vc_col-sm-3',
					'dependency'  	=> array('element' => "button_position", 'value' => array('custom'))
				),
				array(
					"type" 			=> "textfield",
					"class"			=> "",
					"heading" 		=> esc_html__("Right", 'wd_package'),
					"param_name" 	=> "right",
					"description" 	=> esc_html__("ex: 5%", 'wd_package'),
					'edit_field_class' => 'vc_col-sm-3',
					'dependency'  	=> array('element' => "button_position", 'value' => array('custom'))
				),
				array(
					"type" 			=> "textfield",
					"class"			=> "",
					"heading" 		=> esc_html__("Bottom", 'wd_package'),
					"param_name" 	=> "bottom",
					"description" 	=> esc_html__("ex: 5%", 'wd_package'),
					'edit_field_class' => 'vc_col-sm-3',
					'dependency'  	=> array('element' => "button_position", 'value' => array('custom'))
				),
				array(
					"type" 			=> "textfield",
					"class"			=> "",
					"heading" 		=> esc_html__("Left", 'wd_package'),
					"param_name" 	=> "left",
					"description" 	=> esc_html__("ex: 5%", 'wd_package'),
					'edit_field_class' => 'vc_col-sm-3',
					'dependency'  	=> array('element' => "button_position", 'value' => array('custom'))
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