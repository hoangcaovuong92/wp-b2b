<?php
if ( ! function_exists( 'wd_myaccount_form_function' ) ) {
	function wd_myaccount_form_function( $atts ) {
		extract(shortcode_atts( array(
			'form'			=> 'login',
			'style_class'   => 'style-1',
			'fullwidth_mode' => false,
			'class'      	=> '',
		), $atts ));

		$style 		= 'wd-my-account-form-'.$style_class;
		$random_id 	= 'wd-my-account-form-'.mt_rand();

		//Fullwidth mode class (gutenberg)
		$class .= ($fullwidth_mode) ? ' alignfull' : '';
		
		ob_start(); ?>
		<div id="<?php echo esc_attr($random_id); ?>" class="wd-shortcode wd-shortcode-my-account-form-wrapper <?php echo esc_attr($style); ?> <?php echo esc_attr($class); ?>">
			<?php echo apply_filters('wd_filter_myaccount_form', $form); ?>
		</div>
		<?php
		return ob_get_clean();
	}
}

if (!function_exists('wd_myaccount_form_vc_map')) {
	function wd_myaccount_form_vc_map() {
		return array(
			'name'        => __( "WD - My Account (Form)", 'wd_package' ),
			'description' => __( "Custom Login/Register/Forgot Password Form...", 'wd_package' ),
			'base'        => 'wd_myaccount_form',
			"category"    => esc_html__("WD - Content", 'wd_package'),
			'icon'        => 'vc_icon-vc-gitem-post-author',
			'params'      => array(
				array(
					"type" 				=> "dropdown",
					"class" 			=> "",
					"heading" 			=> esc_html__("Form", 'wd_package'),
					"admin_label" 		=> true,
					"param_name" 		=> "form",
					"value" 			=> array(
							esc_html__( 'Login', 'wd_package' ) 			=> 'login',
							esc_html__( 'Register', 'wd_package' ) 			=> 'register',
							esc_html__( 'Forgot Password', 'wd_package' ) 	=> 'forgot-password',
						),
					"description" 		=> "",
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