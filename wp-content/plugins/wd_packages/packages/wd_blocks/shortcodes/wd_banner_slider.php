<?php
if (!function_exists('wd_banner_slider_function')) {
	function wd_banner_slider_function($atts) {
		extract(shortcode_atts(array(
			'image_link'	=> '0',
			'slider'		=> '',
			'images'		=> '',
			'image_size'	=> 'full',
			'target'		=> '_blank',
			'columns'		=> '1',
			'center_mode'	=> '0',
			'show_nav'		=> '1',
			'show_dot'		=> '1',
			'auto_play'		=> '1',
			'fullwidth_mode'=> false,
			'class' 		=> ''
		), $atts));

		if (!function_exists('vc_param_group_parse_atts')) return;
		$slider = array();
		$slider_images = array();

		//$image_link : true - open url when click image , false : open lightbox with full image
		if ($image_link) {
			$slider_images = vc_param_group_parse_atts( $slider );
		}else{
			$images = explode(',', $images);
			if (!empty($images)) {
				foreach ($images as $id) {
					$image_full = wp_get_attachment_image_src($id, 'full');
					if (!empty($image_full) && is_array($image_full)) {
						$image_full 	= $image_full[0];
					}

					$slider_images[] = array(
						'image' => $id,
						'link'	=> $image_full,
					);
				}
			}
		}

		$lightbox_class = (!$image_link) ? 'wd-fancybox-image-gallery' : '';
		$lightbox_group = (!$image_link) ? 'data-fancybox-group="wd-banner-slider-'.mt_rand().'"' : '';
		
		//Fullwidth mode class (gutenberg)
		$class .= ($fullwidth_mode) ? ' alignfull' : '';
		
		ob_start(); ?>
			<?php if (count($slider_images)) {
				$slider_options = json_encode(array(
					'slider_type' => 'slick',
					'column_desktop' => esc_attr($columns),
					'arrows' => $show_nav ? true : false,
					'autoplay' => $auto_play ? true : false,
					'dots' => $show_dot ? true : false,
					'centerMode' => $center_mode ? true : false,
				));
				?>
				<div class="wd-shortcode wd-shortcode-banner-slider <?php echo esc_attr($class); ?>">
					<div class="wd-slider-wrap wd-slider-wrap--banner-slider"
						data-slider-options='<?php echo esc_attr( $slider_options ); ?>'">
						<?php foreach($slider_images as $image){ ?>
							<a class="<?php echo esc_attr($lightbox_class); ?>" <?php echo $lightbox_group; ?> href="<?php echo esc_url($image['link']); ?>"  target='<?php echo esc_attr($target); ?>'>
								<?php echo apply_filters('wd_filter_image_html', array('attachment' => $image['image'], 'image_size' => $image_size)); ?>
							</a>
						<?php } ?>
					</div>
				</div>
			<?php } ?>
		<?php
		$output = ob_get_clean();
		wp_reset_postdata();
		return $output;
	}
}

if (!function_exists('wd_banner_slider_vc_map')) {
	function wd_banner_slider_vc_map() {
		return array(
		'name' 				=> esc_html__("WD - Banner Slider", 'wd_package'),
		'base' 				=> 'wd_banner_slider',
		'description' 		=> esc_html__("Custom Image, Link, Slick slider style...", 'wd_package'),
		'category' 			=> esc_html__("WD - Content", 'wd_package'),
		'icon'        		=> 'icon-wpb-images-carousel',
		"params" 			=> array(
			array(
				"type" 			=> "dropdown",
				"class" 		=> "",
				"heading" 		=> esc_html__("Image Link", 'wd_package'),
				"admin_label" 	=> true,
				"param_name" 	=> "image_link",
				"std"			=> '0',
				'value' 		=> wd_vc_get_list_tvgiao_boolean(),
				"description" 	=> esc_html__("Choose yes if each image is exist 1 link.", 'wd_package'),
				'edit_field_class' => 'vc_col-sm-3',
			),
			array(
                'type' 			=> 'param_group',
                'value' 		=> '',
                'param_name' 	=> 'slider',
                // Note params is mapped inside param-group:
                'params' 		=> array(
                	array(
						"type" 			=> "attach_image",
						"class" 		=> "",
						"heading" 		=> esc_html__("Image", 'wd_package'),
						"param_name" 	=> "image",
						"value" 		=> "",
						"description" 	=> '',
					),
					array(
						'type' 			=> 'textfield',
						'class' 		=> '',
						'heading' 		=> esc_html__("URL", 'wd_package'),
						'description'	=> esc_html__("Add link to image slider.", 'wd_package'),
						'admin_label' 	=> true,
						'param_name' 	=> 'link',
						'value' 		=> '#'
					)
				),
				'dependency'  	=> array('element' => "image_link", 'value' => array('1'))
			),
			array(
				"type" 			=> "attach_images",
				"class" 		=> "",
				"heading" 		=> esc_html__("Images", 'wd_package'),
				"param_name" 	=> "images",
				"value" 		=> "",
				"description" 	=> '',
				'dependency'  	=> array('element' => "image_link", 'value' => array('0'))
			),
            array(
				'type' 			=> 'dropdown',
				'heading' 		=> esc_html__( 'Image Size', 'wd_package' ),
				'param_name' 	=> 'image_size',
				'admin_label' 	=> true,
				'value' 		=> wd_vc_get_list_image_size(),
				'std'			=> 'full',
				'description' 	=> '',
				'edit_field_class' => 'vc_col-sm-6',
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
			),
            array(
				'type' 			=> 'dropdown',
				'heading' 		=> esc_html__( 'Columns', 'wd_package' ),
				'param_name' 	=> 'columns',
				'admin_label' 	=> true,
				'value' 		=> wd_vc_get_list_tvgiao_columns(),
				'std'			=> '1',
				'description' 	=> '',
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
				"type" 			=> "dropdown",
				"class" 		=> "",
				"heading" 		=> esc_html__("Center Mode", 'wd_package'),
				"admin_label" 	=> true,
				"param_name" 	=> "center_mode",
				'value' 		=> wd_vc_get_list_tvgiao_boolean(),
				'std'			=> '0',
				"description" 	=> esc_html__("Create highlighter for item at between", 'wd_package'),
				'edit_field_class' => 'vc_col-sm-3',
			),
			array(
				"type" 			=> "dropdown",
				"class" 		=> "",
				"heading" 		=> esc_html__("Show Nav", 'wd_package'),
				"admin_label" 	=> true,
				"param_name" 	=> "show_nav",
				'value' 		=> wd_vc_get_list_tvgiao_boolean(),
				"description" 	=> "",
				'edit_field_class' => 'vc_col-sm-3',
			),
			array(
				"type" 			=> "dropdown",
				"class" 		=> "",
				"heading" 		=> esc_html__("Show Dot", 'wd_package'),
				"admin_label" 	=> true,
				"param_name" 	=> "show_dot",
				'value' 		=> wd_vc_get_list_tvgiao_boolean(),
				"description" 	=> "",
				'edit_field_class' => 'vc_col-sm-3',
			),
			array(
				"type" 			=> "dropdown",
				"class" 		=> "",
				"heading" 		=> esc_html__("Auto Play", 'wd_package'),
				"admin_label" 	=> true,
				"param_name" 	=> "auto_play",
				'value' 		=> wd_vc_get_list_tvgiao_boolean(),
				'std'			=> '0',
				"description" 	=> "",
				'edit_field_class' => 'vc_col-sm-3',
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