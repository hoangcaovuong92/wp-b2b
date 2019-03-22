<?php
if(!function_exists('wd_social_fanpage_likebox_function')){
	function wd_social_fanpage_likebox_function($atts){
		extract(shortcode_atts(array(
			'fanpage_url'	=> '',
			'width'			=> '320',
			'height'		=> '230',
			'class'			=> '',
		),$atts));
		ob_start();
		?>
		<?php if ($fanpage_url): ?>
			<div class="fb-like-box <?php echo esc_attr($class) ?>">
				<iframe src="http://www.facebook.com/plugins/likebox.php?href=<?php echo esc_url($fanpage_url); ?>&amp;width=<?php echo esc_html($width); ?>&amp;colorscheme=light&amp;show_faces=true&amp;connections=9&amp;stream=false&amp;header=false&amp;height=<?php echo esc_html($height); ?>" scrolling="no" frameborder="0" scrolling="no"></iframe>
			</div>
    	<?php endif ?>
		<?php
		$output = ob_get_clean();
		wp_reset_query();
		return $output;
	}
}

if (!function_exists('wd_social_fanpage_likebox_vc_map')) {
	function wd_social_fanpage_likebox_vc_map() {
		return array(
			'name' 				=> esc_html__("WD - Fanpage Likebox", 'wd_package'),
			'base' 				=> 'wd_social_fanpage_likebox',
			'description' 		=> esc_html__("Display fanpage facebook likebox with custom width, height...", 'wd_package'),
			'category' 			=> esc_html__("WD - Content", 'wd_package'),
			'icon'        		=> 'icon-wpb-balloon-facebook-left',
			'params' => array(
				array(
					'type' 			=> 'textfield',
					'class' 		=> '',
					'heading' 		=> esc_html__("Fanpage URL", 'wd_package'),
					'description' 	=> esc_html__("URL of facebook fanpage", 'wd_package'),
					'admin_label' 	=> true,
					'param_name' 	=> 'fanpage_url',
					'value' 		=> ''
				),
				array(
					'type' 			=> 'textfield',
					'class' 		=> '',
					'heading' 		=> esc_html__("Width", 'wd_package'),
					'description' 	=> esc_html__("Unit: pixel", 'wd_package'),
					'admin_label' 	=> true,
					'param_name' 	=> 'width',
					'value' 		=> '320',
					'edit_field_class' => 'vc_col-sm-6',
				),
				array(
					'type' 			=> 'textfield',
					'class' 		=> '',
					'heading' 		=> esc_html__("Height", 'wd_package'),
					'description' 	=> esc_html__("Unit: pixel", 'wd_package'),
					'admin_label' 	=> true,
					'param_name' 	=> 'height',
					'value' 		=> '230',
					'edit_field_class' => 'vc_col-sm-6',
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