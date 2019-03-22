<?php
if (!function_exists('wd_nav_user_icons_function')) {
	function wd_nav_user_icons_function($atts) {
		extract(shortcode_atts(array(
			'screen' 	=> 'desktop',
			'class' 	=> ''
		), $atts));

		if ($screen === 'desktop') {
			/**
			 * wd_hook_nav_user_desktop hook.
			 *
			 * @hooked nav_user_desktop - 5
			 */
			do_action('wd_hook_nav_user_desktop');
		} else if ($screen === 'mobile') {
			/**
			 * wd_hook_nav_user_mobile hook.
			 *
			 * @hooked nav_user_mobile - 5
			 */
			do_action('wd_hook_nav_user_mobile');
		} else {
			/**
			 * wd_hook_nav_user_desktop hook.
			 *
			 * @hooked nav_user_desktop - 5
			 */
			do_action('wd_hook_nav_user_desktop');
			/**
			 * wd_hook_nav_user_mobile hook.
			 *
			 * @hooked nav_user_mobile - 5
			 */
			do_action('wd_hook_nav_user_mobile');
		}
		
	}
}

if (!function_exists('wd_nav_user_icons_vc_map')) {
	function wd_nav_user_icons_vc_map() {
		return array(
			'name' 				=> esc_html__("WD - Nav User", 'wd_package'),
			'base' 				=> 'wd_nav_user_icons',
			'description' 		=> esc_html__("Display all nav user icons", 'wd_package'),
			'category' 			=> esc_html__("WD - Nav User", 'wd_package'),
			'icon'        		=> 'vc_icon-vc-gitem-post-author',
			'params' => array(
				array(
					"type" 			=> "dropdown",
					"class" 		=> "",
					"heading" 		=> esc_html__("Screen", 'wd_package'),
					"admin_label" 	=> true,
					"param_name" 	=> "screen",
					"value" 		=> array(
							esc_html__( 'Desktop', 'wd_package' ) 	=> 'desktop',
							esc_html__( 'Mobile', 'wd_package' ) 	=> 'mobile',
							esc_html__( 'Both', 'wd_package' ) 		=> 'both',
						),
					"description" 		=> "",
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