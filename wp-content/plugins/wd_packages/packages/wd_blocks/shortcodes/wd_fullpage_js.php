<?php
if (!function_exists('wd_fullpage_js_function')) {
	function wd_fullpage_js_function($atts) {
		extract(shortcode_atts(array(
			'content_group'	=> '',
			'class' 		=> ''
		), $atts));
		if (!function_exists('vc_param_group_parse_atts')) return;
		$content_group 		= vc_param_group_parse_atts( $content_group );
		ob_start(); ?>
			<?php if (count($content_group)) { ?>
				<div class="wd-shortcode-fullpage-wrap <?php echo esc_attr($class); ?>">
					<?php foreach($content_group as $content){ ?>
						<?php if (!empty($content['content']) || !empty($content['background'])): ?>
							<?php 
							$bg_url 		= !empty($content['background']) ? wp_get_attachment_image_src((int)$content['background'], 'full') : '';
							$section_class 	= !empty($content['section_class']) ? $content['section_class'] : ''; ?>
							<div class="section <?php echo esc_attr( $section_class ); ?>" style="background-image: url('<?php echo esc_url($bg_url[0]); ?>'); background-size: cover;">
								<div class="wd-shortcode-fullpage-content"><?php echo do_shortcode( "{$content['content']}" ); ?></div> 
							</div>
						<?php endif ?>
					<?php } ?>
				</div>
			<?php } ?>
		<?php
		$output = ob_get_clean();
		wp_reset_postdata();
		return $output;
	}
}

if (!function_exists('wd_fullpage_js_vc_map')) {
	function wd_fullpage_js_vc_map() {
		return array(
			'name' 				=> esc_html__("WD - Fullpage JS", 'wd_package'),
			'base' 				=> 'wd_fullpage_js',
			'description' 		=> esc_html__("Create page content with Fullpage JS...", 'wd_package'),
			'category' 			=> esc_html__("WD - Content", 'wd_package'),
			'icon'        		=> 'icon-wpb-images-carousel',
			"params" 			=> array(
				array(
					'type' 			=> 'param_group',
					'value' 		=> '',
					'param_name' 	=> 'content_group',
					// Note params is mapped inside param-group:
					'params' 		=> array(
						array(
							"type" 			=> "textarea",
							"class" 		=> "",
							"heading" 		=> esc_html__("Content", 'wd_package'),
							"param_name" 	=> "content",
							"value" 		=> "",
							"description" 	=> esc_html__("HTML/Shortcode on each section. You can create a shortcode from the new page creation interface.", 'wd_package'),
						),
						array(
							"type" 			=> "attach_image",
							"class" 		=> "",
							"heading" 		=> esc_html__("Background", 'wd_package'),
							"param_name" 	=> "background",
							"value" 		=> "",
							"description" 	=> '',
							'edit_field_class' => 'vc_col-sm-6',
						),
						array(
							'type' 			=> 'textfield',
							'class' 		=> '',
							'heading' 		=> esc_html__("Section Class", 'wd_package'),
							'description'	=> esc_html__("", 'wd_package'),
							'admin_label' 	=> true,
							'param_name' 	=> 'section_class',
							'value' 		=> '',
							'edit_field_class' => 'vc_col-sm-6',
						)
					)
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