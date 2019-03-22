<?php
if ( ! function_exists( 'wd_tabs_function' ) ) {
	function wd_tabs_function( $atts ) {
		extract(shortcode_atts( array(
			'items'					=> '',
			'style_class'			=> 'style-1',
			'class'      			=> '',
		), $atts ));
		if (!function_exists('vc_param_group_parse_atts')) return;
		$items 		= vc_param_group_parse_atts( $items );
		$style_class 	= 'wd-tabs-'.$style_class;
		$random_id 	= 'wd-shortcode-tabs-'.mt_rand();
		ob_start(); ?>
		<?php if (count($items)): ?>
			<div id="<?php echo esc_attr($random_id); ?>"  class="wd-shortcode-tabs <?php echo esc_attr($style_class); ?> <?php echo esc_attr($class); ?>">
	        	<?php echo wd_tab_bootstrap($items); ?>
	        </div>
		<?php endif ?>
        
		<?php
		wp_reset_postdata();
		return ob_get_clean();
	}
}

if (!function_exists('wd_tabs_vc_map')) {
	function wd_tabs_vc_map() {
		return array(
			'name'        => __( "WD - Tabs", 'wd_package' ),
			'description' => __( "Show content with tabs...", 'wd_package' ),
			'base'        => 'wd_tabs',
			"category"    => esc_html__("WD - Content", 'wd_package'),
			'icon'        => 'icon-wpb-ui-accordion',
			'params'      => array(
				array(
					'type' 			=> 'param_group',
					'value' 		=> '',
					'param_name' 	=> 'items',
					// Note params is mapped inside param-group:
					'params' 		=> array(
						array(
							"type" 			=> "textfield",
							"holder" 		=> "div",
							"class" 		=> "",
							"heading" 		=> esc_html__("Title", 'wd_package'),
							"param_name" 	=> "title",
							"value" 		=> "",
							"description" 	=> esc_html__("", 'wd_package')
						),
						array(
							"type" 			=> "textarea",
							"holder" 		=> "div",
							"class" 		=> "",
							"heading" 		=> esc_html__("Content", 'wd_package'),
							"param_name" 	=> "content",
							"value" 		=> "",
							"description" 	=> esc_html__("HTML/Shortcode/Text is allowed.", 'wd_package')
						),
					)
				),
				array(
					'type' 				=> 'dropdown',
					'heading' 			=> esc_html__( 'Style', 'wd_package' ),
					'param_name' 		=> 'style_class',
					'admin_label' 		=> true,
					'value' 			=> wd_vc_get_list_style_class(1),
					'description' 		=> '',
				),
				array(
					'type' 				=> 'textfield',
					'class' 			=> '',
					'heading' 			=> esc_html__("Extra class name", 'wd_package'),
					'description'		=> esc_html__("Style particular content element differently - add a class name and refer to it in custom CSS.", 'wd_package'),
					'admin_label' 		=> true,
					'param_name' 		=> 'class',
					'value' 			=> ''
				),
			)
		);
	}
}