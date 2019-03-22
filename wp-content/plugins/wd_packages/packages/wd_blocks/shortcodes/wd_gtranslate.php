<?php
if (!function_exists('wd_gtranslate_function')) {
	function wd_gtranslate_function($atts) {
		extract(shortcode_atts(array(
			'class' => '',
		), $atts));
		
		ob_start();
		?>
		<div class="wd-shortcode-gtranslate <?php echo esc_attr($class) ?>">
			<?php echo do_shortcode( '[GTranslate]' ); ?>
		</div>
		<?php
		$content = ob_get_clean();
		wp_reset_query();
		return $content;
	}
}

if (!function_exists('wd_gtranslate_vc_map')) {
	function wd_gtranslate_vc_map() {
		if(!class_exists('GTranslate')) return;
		return array(
			'name' 				=> esc_html__("WD - GTranslate", 'wd_package'),
			'base' 				=> 'wd_gtranslate',
			'description' 		=> esc_html__("Run GTranslate Shortcode", 'wd_package'),
			'category' 			=> esc_html__("WD - Content", 'wd_package'),
			'icon'        		=> 'icon-wpb-vc_gravityform',
			'params' => array(
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