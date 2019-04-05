<?php
if (!function_exists('wd_banner_slider_function')) {
	function wd_banner_slider_function($atts) {
		extract(shortcode_atts(array(
			'slider'		=> '',
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
		$slider 		= vc_param_group_parse_atts( $slider );
		$show_nav 		= ($show_nav == '1') 	? 'true' : 'false';
		$show_dot 		= ($show_dot == '1') 	? 'true' : 'false';
		$center_mode 	= ($center_mode == '1') ? 'true' : 'false';
		$auto_play 		= ($auto_play == '1') 	? 'true' : 'false';

		$title_image	= get_bloginfo('name');
		$random_id 		= 'wd-banner-slider-'.mt_rand();
		
		//Fullwidth mode class (gutenberg)
		$class .= ($fullwidth_mode) ? ' alignfull' : '';
		
		ob_start(); ?>
			<?php if (count($slider)) { ?>
				<div id="<?php echo esc_attr( $random_id ); ?>" class="wd-shortcode wd-shortcode-banner-slider <?php echo esc_attr($class); ?>">
					<?php foreach($slider as $image){ ?>
						<a href="<?php echo esc_url($image['link']); ?>"  target='<?php echo esc_attr($target); ?>' title="<?php echo esc_attr($title_image); ?>">
							<?php echo apply_filters('wd_filter_image_html', array('attachment' => $image['image'], 'image_size' => $image_size)); ?>
						</a>
					<?php } ?>
				</div>
				<script type="text/javascript">
					jQuery(document).ready(function() {
						"use strict";	
						var $_this = jQuery('#<?php echo esc_attr( $random_id ); ?>');
						$_this.slick({
						  	arrows			: <?php echo esc_html($show_nav); ?>,
						  	dots 			: <?php echo esc_html($show_dot); ?>,
						  	centerMode 		: <?php echo esc_html($center_mode); ?>,
						  	infinite 		: true,
						  	autoplay 		: <?php echo esc_html($auto_play); ?>,
						  	autoplaySpeed	: 2000,
						  	speed			: 300,
						  	slidesToShow	: <?php echo esc_attr($columns); ?>,
						  	slidesToScroll	: <?php echo esc_attr($columns); ?>,
						  	responsive		: [
							    {
							      	breakpoint			: 1024,
							      	settings 			: {
								        slidesToShow	: <?php echo esc_attr($columns); ?>,
								        slidesToScroll	: <?php echo esc_attr($columns); ?>,
								        infinite		: true,
								        dots 			: <?php echo esc_attr($show_dot); ?>,
							      	}
							    },
							    {
							      	breakpoint			: 600,
							      	settings 			: {
								        slidesToShow	: (<?php echo esc_attr($columns); ?> > 1) ? <?php echo esc_attr($columns); ?> - 1 : 1,
								        slidesToScroll	: (<?php echo esc_attr($columns); ?> > 1) ? <?php echo esc_attr($columns); ?> - 1 : 1
							      	}
							    },
							    {
								    breakpoint			: 480,
								    settings 			: {
								        slidesToShow	: 1,
								        slidesToScroll	: 1
								    }
							    }
						  	]
						});
					});	
				</script>
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
                )
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