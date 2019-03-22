<?php
if ( ! function_exists( 'wd_accordion_function' ) ) {
	function wd_accordion_function( $atts ) {
		extract(shortcode_atts( array(
			'items'					=> '',
			'show_icon'				=> '1',
			'icon_plus'				=> 'fa fa-plus',
			'icon_minus'			=> 'fa fa-minus',
			'style_class'      		=> 'style-1',
			'class'      			=> '',
		), $atts ));
		if (!function_exists('vc_param_group_parse_atts')) return;
		$items 		= vc_param_group_parse_atts( $items );
		$icon_plus 	= ($icon_plus) ? explode(' ', $icon_plus)[1] : '';
		$icon_minus = ($icon_minus) ? explode(' ', $icon_minus)[1] : '';
		$style_class 	= 'wd-accordion-'.$style_class;
		$random_id 	= 'wd-shortcode-accordion-'.mt_rand();
		ob_start(); ?>
		<?php if (count($items)): ?>
			<div id="<?php echo esc_attr($random_id); ?>"  class="wd-shortcode-accordion <?php echo esc_attr($style_class); ?> <?php echo esc_attr($class); ?>">
	        	<?php $random_num = mt_rand(); $i = 0; ?>
	        	<div class="panel-group" id="accordion-<?php echo $random_num; ?>" role="tablist" aria-multiselectable="true" data-icon_plus="<?php echo esc_attr( $icon_plus ); ?>" data-icon_minus="<?php echo esc_attr( $icon_minus ); ?>">
	        		<?php foreach ($items as $item): ?>
	        			<?php if (empty($item['title']) || empty($item['content'])) break; ?>
	        			<div class="panel panel-default">
				            <div class="panel-heading" role="tab" id="heading-<?php echo $random_num.$i; ?>">
				                <h4 class="panel-title">
				                    <a role="button" data-toggle="collapse" data-parent="#accordion-<?php echo $random_num; ?>" href="#collapse-<?php echo $random_num.$i; ?>" aria-expanded="true" aria-controls="collapse-<?php echo $random_num.$i; ?>">
				                    	<?php if ($show_icon && $icon_plus != '' && $icon_minus != ''): ?>
				                        	<i class="wd-accordion-icon fa <?php echo esc_attr( $icon_plus ); ?>"></i>
				                    	<?php endif ?>
										<span class="wd-shortcode-title">
				                       	 <?php echo esc_html( $item['title'] ); ?>
				                        </span>
				                    </a>
				                </h4>
				            </div>
				            <div id="collapse-<?php echo $random_num.$i; ?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading-<?php echo $random_num.$i; ?>">
				                <div class="panel-body wd-shortcode-content">
				                      <?php echo do_shortcode( $item['content'] ); ?>
				                </div>
				            </div>
				        </div>
				        <?php $i++; ?>
	        		<?php endforeach ?>
			    </div><!-- panel-group -->
	        </div>
		<?php endif ?>
		<?php
		wp_reset_postdata();
		return ob_get_clean();
	}
}

if (!function_exists('wd_accordion_vc_map')) {
	function wd_accordion_vc_map() {
		return array(
			'name'        => __( "WD - Accordion", 'wd_package' ),
			'description' => __( "Show Accordion FAQs...", 'wd_package' ),
			'base'        => 'wd_accordion',
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
					"type" 				=> "dropdown",
					"class" 			=> "",
					"heading" 			=> esc_html__("Show Icon", 'wd_package'),
					"admin_label" 		=> true,
					"param_name" 		=> "show_icon",
					"value" 			=> wd_vc_get_list_tvgiao_boolean(),
					"description" 		=> "",
				),
				array(
					'type' 				=> 'iconpicker',
					'heading' 			=> esc_html__( 'Icon Plus', 'wd_package' ),
					'param_name' 		=> 'icon_plus',
					'value' 			=> '',
					'settings' 			=> array(
						'emptyIcon' 		=> false,
						'iconsPerPage' 		=> 4000,
					),
					'std'				=> 'fa fa-plus',
					'description' 		=> esc_html__( 'Select icon from library.', 'wd_package' ),
					'edit_field_class' 	=> 'vc_col-sm-6',
					'dependency'		=> Array('element' => "show_icon", 'value' => array('1'))
				),
				
				array(
					'type' 				=> 'iconpicker',
					'heading' 			=> esc_html__( 'Icon Minus', 'wd_package' ),
					'param_name' 		=> 'icon_minus',
					'value' 			=> '',
					'settings' 			=> array(
						'emptyIcon' 		=> false,
						'iconsPerPage' 		=> 4000,
					),
					'std'				=> 'fa fa-minus',
					'description' 		=> esc_html__( 'Select icon from library.', 'wd_package' ),
					'edit_field_class' 	=> 'vc_col-sm-6',
					'dependency'		=> Array('element' => "excerpt", 'value' => array('1'))
				),
				array(
					'type' 				=> 'dropdown',
					'heading' 			=> esc_html__( 'Style', 'wd_package' ),
					'param_name' 		=> 'style_class',
					'admin_label' 		=> true,
					'value' 			=> wd_vc_get_list_style_class(5),
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