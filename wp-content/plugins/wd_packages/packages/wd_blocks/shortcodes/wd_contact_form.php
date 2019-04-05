<?php
if (!function_exists('wd_contact_form_function')) {
	function wd_contact_form_function($atts) {
		if (!wd_is_contact_form_7()) return;
		extract(shortcode_atts(array(
			'slug'			=> '',
			'fullwidth_mode'		=> false,
			'class' 		=> ''
		), $atts));

		//var_export(get_post_meta(5, '_mail', true));
		//var_export(get_post_meta(5, '_mail_2', true));
		//var_export(get_post_meta(5, '_messages', true));

		$id 	= wd_get_post_id_by_slug($slug, 'wpcf7_contact_form');
		$title  = wd_get_post_title_by_slug($slug, 'wpcf7_contact_form');
		$shortcode = '[contact-form-7 id="'.$id.'" title="'.$title.'"]';

		//Fullwidth mode class (gutenberg)
		$class .= ($fullwidth_mode) ? ' alignfull' : '';

		ob_start(); ?>
			<div class="wd-shortcode wd-shortcode-contact-form <?php echo esc_attr($class); ?>">	
				<?php echo do_shortcode($shortcode); ?>
			</div>
		<?php
		$output = ob_get_clean();
		wp_reset_postdata();
		return $output;
	}
}

if (!function_exists('wd_contact_form_vc_map')) {
	function wd_contact_form_vc_map() {
		if (!wd_is_contact_form_7()) return;
		return array(
			'name' 				=> esc_html__("WD - Contact Form", 'wd_package'),
			'base' 				=> 'wd_contact_form',
			'description' 		=> esc_html__("Display contact form 7 form...", 'wd_package'),
			'category' 			=> esc_html__("WD - Content", 'wd_package'),
			'icon'        		=> 'icon-wpb-contactform7',
			"params" => array(
				array(
					"type" 			=> "dropdown",
					"holder" 		=> "div",
					"class" 		=> "",
					"heading" 		=> esc_html__("Contact Form", 'wd_package'),
					"param_name" 	=> "slug",
					"value" 		=> wd_vc_get_data_by_post_type('wpcf7_contact_form', array(), 'slug'),
					"std" 			=> '_blank',
					"description" 	=> "",
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