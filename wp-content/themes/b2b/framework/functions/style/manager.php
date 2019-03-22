<?php 
/**
 * WPDANCE FRAMEWORK 2018.
 *
 * @author : Cao Vuong.
 * -Email  : hoangcaovuong92@gmail.com.
 */

if (!class_exists('WD_Style_Manager')) {
	class WD_Style_Manager{
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

		private $arr_functions;

		public function __construct(){
			// Ensure construct function is called only once
			if ( static::$called ) return;
			static::$called = true;
			
			add_action('init', array($this, 'init_setting'));
			$this->functions();
        }

        public function init_setting(){
			$this->arr_functions = array(
				'vc_post_type/banner_handle',
				'vc_post_type/template_handle',
			);
			foreach($this->arr_functions as $function){
				if(file_exists(WD_THEME_STYLE_MANAGER."/{$function}.php")){
					require_once WD_THEME_STYLE_MANAGER."/{$function}.php";
				}	
			}
		}

		public function functions(){
			$this->arr_functions = array(
				'sass/manager',
				'config_xml/manager',
				'enqueue_font',
				'enqueue_scripts',
			);
			foreach($this->arr_functions as $function){
				if(file_exists(WD_THEME_STYLE_MANAGER."/{$function}.php")){
					require_once WD_THEME_STYLE_MANAGER."/{$function}.php";
				}	
			}
		}
	}
	WD_Style_Manager::get_instance();  // Start an instance of the plugin class 
}