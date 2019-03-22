<?php 
/**
 * WPDANCE FRAMEWORK 2018.
 *
 * @author : Cao Vuong.
 * -Email  : hoangcaovuong92@gmail.com.
 */

if (!class_exists('WD_Push_Menu')) {
	class WD_Push_Menu {
		/**
		 * Refers to a single instance of this class.
		 */
		private static $instance = null;

		// Ensure construct function is called only once
		private static $called = false;

		public static function get_instance() {
			if ( null == self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}


		public function __construct(){
			// Ensure construct function is called only once
			if ( static::$called ) return;
			static::$called = true;
			
			/* do_action('wd_hook_pushmenu_mobile_toggle'); */
			add_action('wd_hook_pushmenu_mobile_toggle', array($this, 'pushmenu_mobile_toggle'), 5);

			/* do_action('wd_hook_after_opening_body_tag'); */
			add_action('wd_hook_after_opening_body_tag', array($this, 'pushmenu_mobile'), 15);
		}

		public function pushmenu_mobile_toggle() {  ?>
			<a data-panel-target="#wd-pushmenu-mobile" class="wd-navUser-action wd-navUser-action--pushmenu wd-panel-action wd-panel-action--pushmenu">
				<i class="fa fa-bars wd-icon"></i>
			</a>
			<?php 
		}

		public function pushmenu_mobile() {
			/**
			 * package: pushmenu
			 * var: show_logo_title
			 * var: logo_default
			 * var: logo_url
			 * var: pushmenu_panel_position
			 */
			extract(apply_filters('wd_filter_get_data_package', 'pushmenu' ));
			$current_user 	= wp_get_current_user();
			$class_login	= ($current_user && $current_user->ID) ? "wp-user-login-mobile" : ''; ?>
			<div id="wd-pushmenu-mobile" class="wd-panel-mobile-wrap wd-panel--<?php echo esc_attr($pushmenu_panel_position);?> <?php echo esc_attr($class_login);?>">
				<div class="wd-panel-title wd-panel-title--pushmenu">
					<?php echo apply_filters('wd_filter_logo', array('logo_url' => $logo_url, 'logo_default' => $logo_default, 'show_logo_title' => $show_logo_title)); ?>
					<?php 
					/**
					 * wd_hook_nav_user_pushmenu hook.
					 *
					 * @hooked nav_user_pushmenu - 5
					 */
					do_action('wd_hook_nav_user_pushmenu'); ?>
				</div>

				<?php 
				/**
				 * wd_hook_banner_pushmenu_before hook.
				 */
				do_action('wd_hook_banner_pushmenu_before'); ?>

				<?php echo apply_filters('wd_filter_main_menu', 'mobile'); ?>
				
				<?php 
				/**
				 * wd_hook_banner_pushmenu_after hook.
				 */
				do_action('wd_hook_banner_pushmenu_after'); ?>

				<?php 
				/**
				 * wd_hook_form_user_mobile hook.
				 *
				 * @hooked get_form_user_mobile - 5
				 */
				do_action('wd_hook_form_user_mobile'); ?>
			</div>
		<?php 
		}
	}
	WD_Push_Menu::get_instance();  // Start an instance of the plugin class 
}