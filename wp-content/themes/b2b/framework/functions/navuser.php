<?php
/**
 * WPDANCE FRAMEWORK 2018.
 *
 * @author : Cao Vuong.
 * -Email  : hoangcaovuong92@gmail.com.
 */

if (!class_exists('WD_Nav_User')) {
	class WD_Nav_User {
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
			
			// do_action('wd_hook_nav_user_desktop');
			add_action('wd_hook_nav_user_desktop', array($this, 'nav_user_desktop'), 5);

			// do_action('wd_hook_nav_user_mobile');
			add_action('wd_hook_nav_user_mobile', array($this, 'nav_user_mobile'), 5);

			// do_action('wd_hook_nav_user_pushmenu');
			add_action('wd_hook_nav_user_pushmenu', array($this, 'nav_user_pushmenu'), 5);
		}

		public function nav_user_desktop(){ 
			/**
			 * package: nav-user
			 * var: layout_desktop
			 * var: layout_mobile
			 * var: layout_pushmenu
			 * var: show_icon
			 * var: show_text
			 * var: dropdown_position
			 */
			extract(apply_filters('wd_filter_get_data_package', 'nav-user' ));
			if (!empty($layout_desktop)) { 
				echo '<div class="wd-desktop-screen">';
				echo '<ul class="wd-navUser-list wd-navUser-list--desktop">';
				foreach ($layout_desktop as $key => $value) {
					if ($key === 'mini_cart' && $value) {
						if (!wd_is_woocommerce()) continue;
						echo '<li>'.apply_filters('wd_filter_minicart', array('show_icon' => $show_icon, 'show_text' => $show_text, 'dropdown_position' => $dropdown_position, 'class' => '' )).'</li>';
					}elseif ($key === 'mini_account' && $value) {
						if (!wd_is_woocommerce()) continue;
						echo '<li>'.apply_filters('wd_filter_tiny_myaccount', array('show_icon' => $show_icon, 'show_text' => $show_text, 'dropdown_position' => $dropdown_position, 'class' => '' )).'</li>';
					}if ($key === 'wishlist' && $value) {
						if (!wd_is_woocommerce() || !defined( 'YITH_WCWL')) continue;
						echo '<li>'.apply_filters('wd_filter_wishlist_icon', array('show_icon' => $show_icon, 'show_text' => $show_text, 'class' => '' )).'</li>';
					}if ($key === 'search' && $value) {
						echo '<li>'.apply_filters('wd_filter_search_icon', array('show_icon' => $show_icon, 'show_text' => $show_text, 'class' => '' )).'</li>';
					}
				}
				echo '</ul>';
				echo '</div>';
			} 
		}

		public function nav_user_mobile(){
			/**
			 * package: nav-user
			 * var: layout_desktop
			 * var: layout_mobile
			 * var: layout_pushmenu
			 * var: show_icon
			 * var: show_text
			 * var: dropdown_position
			 */
			extract(apply_filters('wd_filter_get_data_package', 'nav-user' ));
			if (!empty($layout_mobile)) { 
				echo '<div class="wd-mobile-screen">';
				echo '<ul class="wd-navUser-list wd-navUser-list--mobile wd-mobile-screen">';
				foreach ($layout_mobile as $key => $value) {
					if ($key === 'mini_cart' && $value) {
						if (!wd_is_woocommerce()) continue;
						echo '<li>'.apply_filters('wd_filter_minicart', array('show_icon' => $show_icon, 'show_text' => $show_text, 'class' => '' )).'</li>';
					}elseif ($key === 'mini_account' && $value) {
						if (!wd_is_woocommerce()) continue;
						echo '<li>'.apply_filters('wd_filter_tiny_myaccount', array('show_icon' => $show_icon, 'show_text' => $show_text, 'class' => '' )).'</li>';
					}if ($key === 'wishlist' && $value) {
						if (!wd_is_woocommerce() || !defined( 'YITH_WCWL')) continue;
						echo '<li>'.apply_filters('wd_filter_wishlist_icon', array('show_icon' => $show_icon, 'show_text' => $show_text, 'class' => '' )).'</li>';
					}if ($key === 'search' && $value) {
						echo '<li>'.apply_filters('wd_filter_search_icon', array('show_icon' => $show_icon, 'show_text' => $show_text, 'class' => '' )).'</li>';
					}
				}
				echo '</ul>';
				echo '</div>';
			} 
		}

		public function nav_user_pushmenu(){
			/**
			 * package: nav-user
			 * var: layout_desktop
			 * var: layout_mobile
			 * var: layout_pushmenu
			 * var: show_icon
			 * var: show_text
			 * var: dropdown_position
			 */
			extract(apply_filters('wd_filter_get_data_package', 'nav-user' ));
			if (!empty($layout_pushmenu)) { 
				echo '<ul class="wd-navUser-list wd-navUser-list--pushmenu">';
				foreach ($layout_pushmenu as $key => $value) {
					if ($key === 'mini_cart' && $value) {
						if (!wd_is_woocommerce()) continue;
						echo '<li>'.apply_filters('wd_filter_minicart', array('show_icon' => $show_icon, 'show_text' => $show_text, 'class' => '' )).'</li>';
					}elseif ($key === 'mini_account' && $value) {
						if (!wd_is_woocommerce()) continue;
						echo '<li>'.apply_filters('wd_filter_tiny_myaccount', array('show_icon' => $show_icon, 'show_text' => $show_text, 'class' => '' )).'</li>';
					}if ($key === 'wishlist' && $value) {
						if (!wd_is_woocommerce() || !defined( 'YITH_WCWL')) continue;
						echo '<li>'.apply_filters('wd_filter_wishlist_icon', array('show_icon' => $show_icon, 'show_text' => $show_text, 'class' => '' )).'</li>';
					}if ($key === 'search' && $value) {
						echo '<li>'.apply_filters('wd_filter_search_icon', array('show_icon' => $show_icon, 'show_text' => $show_text, 'class' => '' )).'</li>';
					}
				}
				echo '</ul>';
			} 
		}
	}
	WD_Nav_User::get_instance();  // Start an instance of the plugin class 
}