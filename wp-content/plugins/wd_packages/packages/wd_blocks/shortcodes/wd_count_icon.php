<?php
if (!function_exists('wd_count_icon_function')) {
	function wd_count_icon_function($atts) {
		extract(shortcode_atts(array(
			'show_icon'			=> '1',
			'icon_fontawesome' 	=>	'fa fa-adjust',
			'color_icon'		=> '#cccccc',
			'start'				=> "0",
			'finish'			=> '1000',
			'color_number'		=> '#cccccc',
			'text_infomation'	=> '',
			'color_text'		=> '#cccccc',
			'fullwidth_mode'	=> false,
			'class' 			=> ''
		), $atts));

		//Fullwidth mode class (gutenberg)
		$class .= ($fullwidth_mode) ? ' alignfull' : '';

		ob_start(); ?>
			<div class="wd-shortcode wd-shortcode-count-icon <?php echo esc_attr($class); ?>">				
				<?php if($show_icon): ?>
					<div class="feature_icon fa fa-3x <?php echo esc_attr($icon_fontawesome); ?>" style="color: <?php echo esc_attr($color_icon); ?>"></div>
				<?php endif; ?>
				<div class="counter" data-count="<?php echo esc_attr($finish); ?>" style="color: <?php echo esc_attr($color_number); ?>"><?php echo esc_attr($start); ?></div>
				<div class="wd-information-text" style="color: <?php echo esc_attr($color_text); ?>"><?php echo esc_attr($text_infomation); ?></div>
			</div>
			<script type="text/javascript">
				jQuery(document).ready(function($) {
					"use strict";
					$('.wd-shortcode-count-icon .counter').each(function() {
		 				var $this = $(this),
			  			countTo = $this.attr('data-count');
						$({ countNum: $this.text()}).animate({
							countNum: countTo
						},{
							duration: 15000,
							easing:'linear',
							step: function() {
							  $this.text(Math.floor(this.countNum));
							},
							complete: function() {
							  $this.text(this.countNum);
							  //alert('finished');
							}
				  		});  
					});
				});		  
			</script>
		<?php
		$output = ob_get_clean();
		wp_reset_postdata();
		return $output;
	}
}

if (!function_exists('wd_count_icon_vc_map')) {
	function wd_count_icon_vc_map() {
		return array(
			'name' 				=> esc_html__("WD - Count Icon", 'wd_package'),
			'base' 				=> 'wd_count_icon',
			'description' 		=> esc_html__("Display Count Icon...", 'wd_package'),
			'category' 			=> esc_html__("WD - Content", 'wd_package'),
			'icon'        		=> 'icon-wpb-vc_icon',
			"params" => array(
				array(
					"type" 			=> "dropdown",
					"class" 		=> "",
					"heading" 		=> esc_html__("Show Icon", 'wd_package'),
					"admin_label" 	=> true,
					"param_name" 	=> "show_icon",
					'value' 		=> wd_vc_get_list_tvgiao_boolean(),
					"description" 	=> "",
				),
				array(
					'type' 			=> 'iconpicker',
					'heading' 		=> esc_html__( 'Icon', 'wd_package' ),
					'param_name' 	=> 'icon_fontawesome',
					'value' 		=> 'fa fa-adjust',
					'settings' 		=> array(
						esc_html__('emptyIcon', 'wd_package') 		=> false,
						esc_html__('iconsPerPage', 'wd_package') 	=> 4000,
					),
					'description' 	=> esc_html__( 'Select icon from library.', 'wd_package' ),
				),
				array(
					"type" 			=> "colorpicker",
					"class" 		=> "",
					"heading" 		=> esc_html__("Color Icon", 'wd_package'),
					"param_name" 	=> "color_icon",
					"value" 		=> "#cccccc",
					"description" 	=> '',
				),
				array(
					'type' 			=> 'textfield',
					'class' 		=> '',
					'heading' 		=> esc_html__("Start", 'wd_package'),
					'description'	=> esc_html__("", 'wd_package'),
					'admin_label' 	=> true,
					'param_name' 	=> 'start',
					'value' 		=> '',
					'edit_field_class' => 'vc_col-sm-6',
				),
				array(
					'type' 			=> 'textfield',
					'class' 		=> '',
					'heading' 		=> esc_html__("Finish", 'wd_package'),
					'description'	=> esc_html__("", 'wd_package'),
					'admin_label' 	=> true,
					'param_name' 	=> 'finish',
					'value' 		=> '',
					'edit_field_class' => 'vc_col-sm-6',
				),
				array(
					"type" 			=> "colorpicker",
					"class" 		=> "",
					"heading" 		=> esc_html__("Color Number", 'wd_package'),
					"param_name" 	=> "color_number",
					"value" 		=> "#cccccc",
					"description" 	=> '',
				),
				array(
					'type' 			=> 'textfield',
					'class' 		=> '',
					'heading' 		=> esc_html__("Text Infomation", 'wd_package'),
					'description'	=> esc_html__("", 'wd_package'),
					'admin_label' 	=> true,
					'param_name' 	=> 'text_infomation',
					'value' 		=> ''
				),
				array(
					"type" 			=> "colorpicker",
					"class" 		=> "",
					"heading" 		=> esc_html__("Color Text", 'wd_package'),
					"param_name" 	=> "color_text",
					"value" 		=> "#cccccc",
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