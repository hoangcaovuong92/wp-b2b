<?php
/**
 * Shortcode: wd_megamenu
 */
if (!function_exists('wd_megamenu_function')) {
	function wd_megamenu_function($atts) {
		extract(shortcode_atts(array(), $atts));
		ob_start();
		/**
		 * wd_hook_main_menu hook.
		 *
		 * @hooked display_main_menu - 5
		 */
		do_action('wd_hook_main_menu');
		return ob_get_clean();
	}
}
add_shortcode('wd_megamenu', 'wd_megamenu_function');