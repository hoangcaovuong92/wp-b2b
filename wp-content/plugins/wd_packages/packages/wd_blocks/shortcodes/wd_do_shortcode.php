<?php
if (!function_exists('wd_do_shortcode_function')) {
	function wd_do_shortcode_function($atts) {
		extract(shortcode_atts(array(
			'shortcode'		=> '',
			'class' 		=> '',
		), $atts));
		ob_start();
		?>
		<div class="wd-shortcode-do-shortcode <?php echo esc_attr($class) ?>">
			<?php if ($shortcode): ?>
				<?php 
				$shortcode = str_replace('`{`', '[', $shortcode);
				$shortcode = str_replace('`}`', ']', $shortcode);
				echo do_shortcode( $shortcode ); ?>
			<?php endif ?>
		</div>
		<?php
		$content = ob_get_clean();
		wp_reset_query();
		return $content;
	}
}

if (!function_exists('wd_do_shortcode_vc_map')) {
	function wd_do_shortcode_vc_map() {
		return array(
			'name' 				=> esc_html__("WD - Do Shortcode", 'wd_package'),
			'base' 				=> 'wd_do_shortcode',
			'description' 		=> esc_html__("Executes shortcode...", 'wd_package'),
			'category' 			=> esc_html__("WD - Content", 'wd_package'),
			'icon'        		=> 'icon-wpb-atm',
			'params' => array(
				array(
					"type" 			=> "textarea",
					"class" 		=> "",
					"heading" 		=> esc_html__("Shortcode", 'wd_package'),
					"description" 	=> esc_html__("Paste your shortcode here", 'wd_package'),
					"param_name" 	=> "shortcode",
					"value" 		=> "",
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