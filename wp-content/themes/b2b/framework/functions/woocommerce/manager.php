<?php 
/**
 * WPDANCE FRAMEWORK 2018.
 *
 * @author : Cao Vuong.
 * -Email  : hoangcaovuong92@gmail.com.
 */

if (!class_exists('WD_WooCommerce_Manager')) {
	class WD_WooCommerce_Manager {
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
			
			$this->include_functions();
		}

		/******************************** REGISTER POST TYPE ***********************************/
		public function include_functions(){
			if(file_exists(WD_THEME_FUNCTIONS."/woocommerce/parts/functions.php")){
				require_once WD_THEME_FUNCTIONS."/woocommerce/parts/functions.php";
			}
			if(file_exists(WD_THEME_FUNCTIONS."/woocommerce/parts/wishlist.php")){
				require_once WD_THEME_FUNCTIONS."/woocommerce/parts/wishlist.php";
			}
			if(file_exists(WD_THEME_FUNCTIONS."/woocommerce/parts/minicart.php")){
				require_once WD_THEME_FUNCTIONS."/woocommerce/parts/minicart.php";
			}
		}
	}
	WD_WooCommerce_Manager::get_instance();  // Start an instance of the plugin class 
}